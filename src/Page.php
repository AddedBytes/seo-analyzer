<?php

declare(strict_types=1);

namespace SeoAnalyzer;

use ReflectionException;
use SeoAnalyzer\HttpClient\Client;
use SeoAnalyzer\HttpClient\ClientInterface;
use SeoAnalyzer\HttpClient\Exception\HttpException;
use SeoAnalyzer\Metric\KeywordBasedMetricInterface;
use SeoAnalyzer\Metric\MetricFactory;
use SeoAnalyzer\Parser\Parser;
use SeoAnalyzer\Parser\ParserInterface;

class Page
{
    final public const string LOCALE = 'locale';
    final public const string STOP_WORDS = 'stop_words';
    final public const string KEYWORD = 'keyword';
    final public const string IMPACT = 'impact';
    final public const string TEXT = 'text';
    final public const string HEADERS = 'headers';

    /**
     * @var string URL of web page
     */
    public string $url;

    /**
     * @var array Configuration
     */
    public array $config;

    /**
     * @var string Page locale
     */
    public string $locale = 'en_GB';

    /**
     * @var string Keyword to use in analyse
     */
    public string $keyword;

    /**
     * @var array Stop word used in keyword density analyse
     */
    public array $stopWords = [];

    /**
     * @var string Web page content (html)
     */
    public string $content;

    /**
     * @var array Web page factors values
     */
    public array $factors = [];

    /**
     * @var ClientInterface
     */
    public Client|ClientInterface $client;

    /**
     * @var ParserInterface
     */
    public Parser|ParserInterface $parser;

    /**
     * Page constructor.
     * @param  array|string|null  $config  Due to the backwards compatibility: locale if string, config if array
     * @throws HttpException
     */
    public function __construct(
        string|null $url = null,
        array|string|null $config = null,
        ClientInterface|null $client = null,
        ParserInterface|null $parser = null
    ) {
        $config ??= [];
        $this->client = $client ?? new Client();
        $this->parser = $parser ?? new Parser();
        if (is_string($config)) { // Due to the backwards compatibility
            $config = [self::LOCALE => $config];
        }
        if ($config === []) { // Due to the backwards compatibility
            $config = [self::LOCALE => $this->locale];
        }
        $this->setConfig($config);
        if ($url !== null && $url !== '' && $url !== '0') {
            $this->url = $this->setUpUrl($url);
            $this->getContent();
        }
    }

    /**
     * Verifies URL and sets up some basic metrics.
     *
     * @return string URL
     */
    protected function setUpUrl(string $url): string
    {
        $parsedUrl = parse_url($url);
        if (empty($parsedUrl['scheme'])) {
            $url = 'http://' . $url;
            $parsedUrl = parse_url($url);
        }
        $this->setFactor(Factor::URL_PARSED, $parsedUrl);

        if (strcmp($parsedUrl['scheme'], 'https') === 0) {
            $this->setFactor(Factor::SSL, true);
        }
        $this->setFactor(
            Factor::URL_LENGTH,
            strlen($this->getFactor(Factor::URL_PARSED_HOST) . $this->getFactor(Factor::URL_PARSED_PATH))
        );

        return $url;
    }

    /**
     * Sets configuration.
     */
    public function setConfig(array $config): void
    {
        $this->config = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config/default.php';
        foreach ($this->config as $configItemKey => $configItemValue) {
            if (isset($config[$configItemKey])) {
                $this->config[$configItemKey] = $configItemValue;
            }
        }
    }

    /**
     * Downloads page content from URL specified and sets up some base metrics.
     * @throws HttpException
     */
    public function getContent(): void
    {
        $pageLoadFactors = $this->getPageLoadFactors();
        $this->setFactor(Factor::LOAD_TIME, $pageLoadFactors['time']);
        $this->content = $pageLoadFactors['content'];
        $this->setFactor(Factor::REDIRECT, $pageLoadFactors['redirect']);
        if (empty($this->getFactor(Factor::SSL)) && $this->getSSLResponseCode() == 200) {
            $this->setFactor(Factor::SSL, true);
        }
    }

    /**
     * Sets page load related factors.
     * @param  int  $ttl  Cache ttl in seconds.
     * @throws HttpException
     */
    protected function getPageLoadFactors(int $ttl = 300): array
    {
        $cache = new Cache();

        return $cache->remember('page_content_' . base64_encode($this->url), function (): array {
            $starTime = microtime(true);
            $response = $this->client->get($this->url);
            $loadTime = number_format((microtime(true) - $starTime), 4);
            $redirect = null;
            if ($response->getHeader('X-Guzzle-Redirect-History') !== []) {
                $array = $response->getHeader('X-Guzzle-Redirect-History');
                $redirect = end($array);
            }

            return [
                'content' => $response->getBody()->getContents(),
                'time' => $loadTime,
                'redirect' => $redirect,
            ];
        }, $ttl);
    }

    /**
     * Returns https response code.
     *
     * @param int $ttl Cache ttl in seconds.
     * @return int|false Http code or false on failure.
     */
    protected function getSSLResponseCode(int $ttl = 300): int|false
    {
        $cache = new Cache();

        return $cache->remember(
            'https_response_code_' . base64_encode('https://' . $this->url),
            function (): int|false {
                try {
                    return $this->client->get(str_replace('http://', 'https://', $this->url))->getStatusCode();
                } catch (HttpException) {
                    return false;
                }
            },
            $ttl
        );
    }

    /**
     * Parses page's html content setting up related metrics.
     * @throws HttpException
     */
    public function parse(): void
    {
        if (! isset($this->content) || ($this->content === '' || $this->content === '0')) {
            $this->getContent();
        }
        $this->parser->setContent($this->content);
        $this->setFactors([
            Factor::META_META => $this->parser->getMeta(),
            Factor::HEADERS => $this->parser->getHeaders(),
            Factor::META_TITLE => $this->parser->getTitle(),
            Factor::TEXT => $this->parser->getText(),
            Factor::ALTS => $this->parser->getAlts(),
        ]);
    }

    /**
     * Returns page metrics.
     * @throws ReflectionException|HttpException
     */
    public function getMetrics(): array
    {
        $this->initializeFactors();

        return $this->setUpMetrics($this->config['factors']);
    }

    /**
     * Sets up and returns page metrics based on configuration specified.
     * @return array
     * @throws HttpException
     * @throws ReflectionException *@throws HttpException
     */
    public function setMetrics(array $config): array
    {
        $this->initializeFactors();

        return $this->setUpMetrics($config);
    }

    /**
     * @throws HttpException
     */
    private function initializeFactors(): void
    {
        if (empty($this->dom)) {
            $this->parse();
        }
        $this->setUpContentFactors();
        if (isset($this->keyword) && ($this->keyword !== '' && $this->keyword !== '0')) {
            $this->setUpContentKeywordFactors($this->keyword);
        }
    }

    /**
     * Sets up page content related factors for page metrics.
     */
    public function setUpContentFactors(): void
    {
        $this->setFactors([
            Factor::CONTENT_HTML => $this->content,
            Factor::CONTENT_SIZE => strlen($this->content),
            Factor::CONTENT_RATIO => [
                'content_size' => strlen((string) preg_replace('!\s+!', ' ', (string) $this->getFactor(Factor::TEXT))),
                'code_size' => strlen($this->content),
            ],
            Factor::DENSITY_PAGE => [
                self::TEXT => $this->getFactor(Factor::TEXT),
                self::LOCALE => $this->locale,
                self::STOP_WORDS => $this->stopWords,
            ],
            Factor::DENSITY_HEADERS => [
                self::HEADERS => $this->getFactor(Factor::HEADERS),
                self::LOCALE => $this->locale,
                self::STOP_WORDS => $this->stopWords,
            ],
        ]);
    }

    /**
     * Sets up page content factors keyword related.
     */
    public function setUpContentKeywordFactors(string $keyword): void
    {
        $this->setFactors([
            Factor::KEYWORD_URL => [
                self::TEXT => $this->getFactor(Factor::URL_PARSED_HOST),
                self::KEYWORD => $keyword,
                self::IMPACT => 5,
                'type' => 'URL',
            ],
            Factor::KEYWORD_PATH => [
                self::TEXT => $this->getFactor(Factor::URL_PARSED_PATH),
                self::KEYWORD => $keyword,
                self::IMPACT => 3,
                'type' => 'UrlPath',
            ],
            Factor::KEYWORD_TITLE => [
                self::TEXT => $this->getFactor(Factor::TITLE),
                self::KEYWORD => $keyword,
                self::IMPACT => 5,
                'type' => 'Title',
            ],
            Factor::KEYWORD_DESCRIPTION => [
                self::TEXT => $this->getFactor(Factor::META_DESCRIPTION),
                self::KEYWORD => $keyword,
                self::IMPACT => 3,
                'type' => 'Description',
            ],
            Factor::KEYWORD_HEADERS => [self::HEADERS => $this->getFactor(Factor::HEADERS), self::KEYWORD => $keyword],
            Factor::KEYWORD_DENSITY => [
                self::TEXT => $this->getFactor(Factor::TEXT),
                self::LOCALE => $this->locale,
                self::STOP_WORDS => $this->stopWords,
                self::KEYWORD => $keyword,
            ],
        ]);
    }

    /**
     * Sets up page metrics.
     *
     * @param array $config Metrics config
     * @return array
     * @throws ReflectionException
     */
    public function setUpMetrics(array $config = []): array
    {
        $metrics = [];
        foreach ($config as $factor) {
            $metric = $factor;
            if (is_array($factor)) {
                $metric = current($factor);
                $factor = key($factor);
            }
            $metricObject = MetricFactory::get('page.' . $metric, $this->getFactor($factor));
            if (! $metricObject instanceof KeywordBasedMetricInterface || isset($this->keyword) && ($this->keyword !== '' && $this->keyword !== '0')) {
                $metrics['page_' . str_replace('.', '_', (string) $metric)] = $metricObject;
            }
        }

        return $metrics;
    }

    public function setFactor(string $name, mixed $value): int|array
    {
        if (count(explode('.', $name)) > 1) {
            $this->setArrayByDot($this->factors, $name, $value);
        } else {
            $this->factors[$name] = $value;
        }

        return $this->factors;
    }

    /**
     * Sets array values using string with dot notation.
     * @param  array  $array  Array to be updated
     * @param  string  $path  Dot notated string
     * @param  mixed  $val  Value to be set in array
     * @return mixed
     */
    protected function setArrayByDot(array &$array, string $path, mixed $val): mixed
    {
        $loc = &$array;
        foreach (explode('.', $path) as $step) {
            $loc = &$loc[$step];
        }

        return $loc = $val;
    }

    /**
     * Sets multiple page factors values at once.
     */
    public function setFactors(array $factors): void
    {
        foreach ($factors as $factorName => $factorValue) {
            $this->setFactor($factorName, $factorValue);
        }
    }

    /**
     * Returns factor data collected by it's key name.
     * @return mixed
     */
    public function getFactor(string $name): mixed
    {
        if (str_contains($name, '.')) {
            return $this->getNestedFactor($name);
        }
        if (! empty($this->factors[$name])) {
            return $this->factors[$name];
        }

        return false;
    }

    /**
     * Returns factor data collected by it's key name.
     * @return mixed
     */
    protected function getNestedFactor(string $name): mixed
    {
        $keys = explode('.', $name);
        $value = $this->factors;
        foreach ($keys as $key) {
            if (! array_key_exists($key, $value)) {
                return false;
            }
            $value = $value[$key];
        }

        return $value;
    }
}
