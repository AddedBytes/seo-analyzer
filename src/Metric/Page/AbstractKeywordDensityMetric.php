<?php

namespace SeoAnalyzer\Metric\Page;

use SeoAnalyzer\Metric\AbstractMetric;
use SeoAnalyzer\Metric\KeywordBasedMetricInterface;

abstract class AbstractKeywordDensityMetric extends AbstractMetric implements KeywordBasedMetricInterface
{
    public string $description = 'Keyword density';

    public mixed $keyword;

    public function __construct(mixed $inputData)
    {
        if (! is_array($inputData) || $inputData === []) {
            // Unit tests fail here because sometimes inputData is not an array,
            // so set some default params for when there's a breaking input
            $inputData = ['locale' => 'en_GB',];
        }
        if (empty($inputData['stop_words'])) {
            $stopWordsFilename = dirname(__DIR__, 3) . '/locale/' . $inputData['locale'] . '_stop_words.yml';
            if (is_file($stopWordsFilename)) {
                $inputData['stop_words'] = file($stopWordsFilename);
            }
        }
        if (! empty($inputData['keyword'])) {
            $this->keyword = $inputData['keyword'];
        }
        parent::__construct($inputData);
    }

    /**
     * Returns most popular keywords used in text with it's use percentage.
     *
     * @param int $maxPhraseWords
     * @param int $minCount Minimum keyword count
     * @return array
     */
    protected function analyseKeywords(string $text, array $stopWords, int|null $maxPhraseWords = null, int|null $minCount = null): array
    {
        $maxPhraseWords ??= 4;
        $minCount       ??= 0;
        $words               = $this->getWords($text, $stopWords);
        $keywords            = $this->getKeywords($words, $maxPhraseWords);
        $keywordsPercentages = [];
        for ($phraseWordCount = 1; $phraseWordCount <= $maxPhraseWords; $phraseWordCount++) {
            if (! empty($keywords[$phraseWordCount])) {
                $keywordsPercentages[$phraseWordCount] = $this->calculateKeywordsPercentage($keywords[$phraseWordCount], $minCount, 10);
            }
        }

        return $keywordsPercentages;
    }

    /**
     * Cleans the text input and returns array of words.
     *
     * @return array
     */
    protected function getWords(string $text, array $stopWords = []): array
    {
        $text      = html_entity_decode($text);
        $stopWords = array_map(static fn (string $word) => trim((string)$word), $stopWords);
        $stopWords = array_merge($stopWords, ['\'', '"', "-", "_"]);
        $text      = strtolower((string)preg_replace('/[^a-zA-Z0-9\s]/', '', $text));
        $words     = str_word_count($text, 1);
        $words     = array_diff($words, $stopWords);

        return array_values(array_filter($words, static fn (string $word) => strlen((string)$word) > 2));
    }

    protected function getKeywords(array $words, int $maxPhraseWords): array
    {
        $count    = count($words);
        $keywords = [];
        for ($i = 0; $i < $count; $i++) {
            for ($x = 1; $x <= $maxPhraseWords; $x++) {
                if ($i + $x <= $count) {
                    $phrase = [];
                    for ($y = 0; $y < $x; $y++) {
                        $phrase[] = $words[$i + $y];
                    }
                    $keywords[$x][] = implode(" ", $phrase);
                }
            }
        }

        return $keywords;
    }

    protected function calculateKeywordsPercentage(array $keywords, int|null $min_count = null, int|null $limit = null): array
    {
        $min_count ??= 0;
        $limit     ??= 10;
        $keywords = array_count_values($keywords);
        arsort($keywords);
        $keywords      = array_filter($keywords, static fn (int $count) => $count >= (int)$min_count);
        $keywordsCount = array_sum($keywords);
        foreach ($keywords as $keyword => $count) {
            $keywords[$keyword] = round($count / $keywordsCount * 100);
        }

        return array_slice($keywords, 0, $limit);
    }

    /**
     * Returns overused keywords based on max count specified.
     *
     * @return array
     */
    protected function getOverusedKeywords(array $keywords, int $maxPercentage = 10, int $maxPhraseWords = 4): array
    {
        $overusedWords = [];
        for ($i = 1; $i <= $maxPhraseWords; $i++) {
            if (! empty($keywords[$i])) {
                foreach ($keywords[$i] as $keyword => $percentage) {
                    $actualMaxPercentage = $maxPercentage * $i;
                    if ($actualMaxPercentage > 100) {
                        $actualMaxPercentage = 100;
                    }
                    if ($percentage > $actualMaxPercentage) {
                        $overusedWords[] = $keyword;
                    }
                }
            }
        }

        return $overusedWords;
    }
}
