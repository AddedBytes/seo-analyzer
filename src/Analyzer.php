<?php

declare(strict_types=1);

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
    public Page $page;

    public string $locale = 'en_GB';

    public array $metrics = [];

    public Translator $translator;

    public function __construct(Page|null $page = null, public ClientInterface|Client|null $client = null)
    {
        if (! $this->client instanceof \SeoAnalyzer\HttpClient\ClientInterface) {
            $this->client = new Client();
        }

        if ($page instanceof \SeoAnalyzer\Page) {
            $this->page = $page;
        }
    }

    /**
     * Analyzes page at specified url.
     * @param  string  $url  Url to analyze
     * @return array
     * @throws HttpException
     * @throws ReflectionException
     */
    public function analyzeUrl(string $url, string|null $keyword = null, string|null $locale = null): array
    {
        if ($locale !== null && $locale !== '' && $locale !== '0') {
            $this->locale = $locale;
        }
        $this->page = new Page($url, $locale, $this->client);
        if ($keyword !== null && $keyword !== '' && $keyword !== '0') {
            $this->page->keyword = $keyword;
        }

        return $this->analyze();
    }

    /**
     * Analyzes html document from file.
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
        if ($this->metrics === []) {
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
            )),
        ];
    }

    /**
     * Downloads file from Page's host.
     * @param $url
     * @param $filename
     * @return bool|string
     */
    protected function getFileContent(string $url, string $filename): bool|string
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
        $yamlFileLoader = new YamlFileLoader();
        $this->translator->addLoader('yaml', $yamlFileLoader);
        $localeFilename = dirname(__DIR__) . '/locale/' . $locale . '.yml';
        if (is_file($localeFilename)) {
            $this->translator->addResource('yaml', $localeFilename, $locale);
        }
    }

    /**
     * Formats metric analysis results.
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
