<?php

namespace SeoAnalyzer\Metric\Page;


use SeoAnalyzer\Metric\AbstractMetric;
use SeoAnalyzer\Metric\KeywordBasedMetricInterface;

class KeywordMetric extends AbstractMetric implements KeywordBasedMetricInterface
{
    public string $description = 'Does it contain a key phrase?';

    /**
     * @inheritdoc
     */
    
    public function analyze(): string
    {
        $this->name = 'Keyword' . $this->value['type'];
        if (stripos((string) $this->value['text'], (string) $this->value['keyword']) === false) {
            $this->impact = $this->value['impact'];

            return 'Can not find the keyword phrase. Adding it could improve SEO';
        }

        return 'Good! Found the keyword phrase';
    }
}
