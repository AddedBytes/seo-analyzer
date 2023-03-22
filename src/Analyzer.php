<?php

namespace SeoAnalyzer;

use InvalidArgumentException;
use ReflectionException;
use SeoAnalyzer\HttpClient\Client;
use SeoAnalyzer\HttpClient\ClientInterface;
use SeoAnalyzer\HttpClient\Exception\HttpException;
use SeoAnalyzer\Metric\MetricFactory;
use SeoAnalyzer\Metric\MetricInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class Analyzer
{
    /**
     * @var Page Web page to analyze
     */
    public Page $page;

    /**
     * @var string Default locale to use for translations
     */
    public string $locale = 'en_GB';

    /**
     * @var array Metrics array
     */
    public array $metrics = [];

    /**
     * @var ClientInterface
     */
    public ClientInterface|Client|null $client;

    /**
     * @var Translator
     */
    public Translator $translator;

    /**
     * @param Page|null $page Page to analyze
     * @param ClientInterface|null $client
     */
    public function __construct(Page|null $page = null, ClientInterface|null $client = null)
    {
        $this->client = $client;
        if (empty($client)) {
            $this->client = new Client();
        }

        if (!empty($page)) {
            $this->page = $page;
        }
    }

    /**
     * Analyzes page at specified url.
     * @param  string  $url  Url to analyze
     * @param  string|null  $keyword
     * @param  string|null  $locale
     * @return array
     * @throws HttpException
     * @throws ReflectionException
     */
    public function analyzeUrl(string $url, string|null $keyword = null, string|null $locale = null): array
    {
        if (!empty($locale)) {
            $this->locale = $locale;
        }
        $this->page = new Page($url, $locale, $this->client);
        if (!empty($keyword)) {
            $this->page->keyword = $keyword;
        }
        return $this->analyze();
    }

    /**
     * Analyzes html document from file.
     * @param  string  $filename
     * @param  string|null  $locale
     * @return array
     * @throws HttpException
     * @throws ReflectionException
     */
    public function analyzeFile(string $filename, string|null $locale = null): array
    {
        $this->page = new Page(null, $locale, $this->client);
        $this->page->content = file_get_contents($filename);
        return $this->analyze();
    }

    /**
     * Analyzes html document from string.
     * @param  string  $htmlString
     * @param  string|null  $locale
     * @return array
     * @throws HttpException
     * @throws ReflectionException
     */
    public function analyzeHtml(string $htmlString, string|null $locale = null): array
    {
        $this->page = new Page(null, $locale, $this->client);
        $this->page->content = $htmlString;
        return $this->analyze();
    }

    /**
     * Starts analysis of a Page.
     * @return array
     * @throws ReflectionException
     * @throws HttpException
     */
    public function analyze(): array
    {
        if (empty($this->page)) {
            throw new InvalidArgumentException('No Page to analyze');
        }
        if (empty($this->metrics)) {
            $this->metrics = $this->getMetrics();
        }
        $results = [];
        foreach ($this->metrics as $metric) {
            if ($analysisResult = $metric->analyze()) {
                $results[$metric->name] = $this->formatResults($metric, $analysisResult);
            }
        }
        return $results;
    }

    /**
     * Returns available metrics list for a Page
     * @throws ReflectionException
     * @throws HttpException
     */
    public function getMetrics(): array
    {
        return [...$this->page->getMetrics(), ...$this->getFilesMetrics()];
    }

    /**
     * Returns file related metrics.
     *
     * @throws ReflectionException
     */
    public function getFilesMetrics(): array
    {
        return [
            'robots' => MetricFactory::get('file.robots', $this->getFileContent(
                $this->page->getFactor('url.parsed.scheme') . '://' . $this->page->getFactor('url.parsed.host'),
                'robots.txt'
            )),
            'sitemap' => MetricFactory::get('file.sitemap', $this->getFileContent(
                $this->page->getFactor('url.parsed.scheme') . '://' . $this->page->getFactor('url.parsed.host'),
                'sitemap.xml'
            ))
        ];
    }

    /**
     * Downloads file from Page's host.
     * @param $url
     * @param $filename
     * @return bool|string
     */
    protected function getFileContent($url, $filename): bool|string
    {
        $cache = new Cache();
        $cacheKey = 'file_content_' . base64_encode($url . '/' . $filename);
        if ($value = $cache->get($cacheKey)) {
            return $value;
        }
        try {
            $response = $this->client->get($url . '/' . $filename);
            $content = $response->getBody()->getContents();
        } catch (HttpException) {
            return false;
        }
        $cache->set($cacheKey, $content, 300);
        return $content;
    }

    /**
     * Sets up the translator for current locale.
     */
    public function setUpTranslator(string $locale): void
    {
        $this->translator = new Translator($locale);
        $this->translator->setFallbackLocales(['en_GB']);
        $yamlLoader = new YamlFileLoader();
        $this->translator->addLoader('yaml', $yamlLoader);
        $localeFilename = dirname(__DIR__) . '/locale/' . $locale . '.yml';
        if (is_file($localeFilename)) {
            $this->translator->addResource('yaml', $localeFilename, $locale);
        }
    }

    /**
     * Formats metric analysis results.
     * @param  MetricInterface  $metric
     * @param  string  $results
     * @return array
     */
    protected function formatResults(MetricInterface $metric, string $results): array
    {
        if (empty($this->translator)) {
            $this->setUpTranslator($this->locale);
        }
        return [
            'analysis' => $this->translator->trans($results),
            'name' => $metric->name,
            'description' => $this->translator->trans($metric->description),
            'value' => $metric->value,
            'negative_impact' => $metric->impact,
        ];
    }
}
