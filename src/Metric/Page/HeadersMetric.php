<?php

namespace SeoAnalyzer\Metric\Page;

use Override;
use SeoAnalyzer\Metric\AbstractMetric;

class HeadersMetric extends AbstractMetric
{
    public string $description = 'Html headers metric';

    protected array $results = [
        'no_headers' => [
            self::IMPACT  => 7,
            self::MESSAGE => 'Looks the site has no headers at all.' .
                ' You should rebuild your page structure as html headers have strong impact on SEO',
        ],
        'no_H1' => [
            self::IMPACT  => 5,
            self::MESSAGE => 'There is no H1 header on the site.' .
                ' You should rebuild your page to use main headers as this has strong impact on SEO',
        ],
        'multi_H1' => [
            self::IMPACT  => 3,
            self::MESSAGE => 'There are multiple H1 headers on the site.' .
                ' You should use only one main header on the site',
        ],
        'too_long_H1' => [
            self::IMPACT  => 3,
            self::MESSAGE => 'The H1 header is too long.' .
                ' You should consider changing it to something shorter including your main keyword',
        ],
        'no_H2' => [
            self::IMPACT  => 3,
            self::MESSAGE => 'There are no H2 headers on the site.' .
                ' You should consider rebuild your page to use proper headers structure',
        ],
        'too_many_H2' => [
            self::IMPACT  => 1,
            self::MESSAGE => 'There are a lot of H2 headers on the site. You should limit number of H2 headers',
        ],
        'no_H3' => [
            self::IMPACT  => 1,
            self::MESSAGE => 'There are no H3 header on the site. Using proper headers structure can improve the SEO',
        ],
    ];

    public function __construct(mixed $inputData)
    {
        parent::__construct($inputData);
        $this->setUpResultsConditions();
    }

    /**
     * @inheritdoc
     */
    #[Override]
    public function analyze(): string
    {
        return $this->checkTheResults('The headers structure on the site looks very good');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function setUpResultsConditions(array $conditions = []): bool
    {
        $conditions = [
            'no_headers' => empty($this->value),
        ];
        if (! empty($this->value)) {
            $conditions = array_merge($conditions, [
                'no_H1'       => empty($this->value['h1']) || empty($this->value['h1'][0]),
                'no_H2'       => empty($this->value['h2']) || empty($this->value['h2'][0]),
                'too_many_H2' => ! empty($this->value['h2']) && (is_countable($this->value['h2']) ? count($this->value['h2']) : 0) > 5,
                'no_H3'       => empty($this->value['h3']) || empty($this->value['h3'][0]),
            ]);
            if (! empty($this->value['h1'])) {
                $conditions = array_merge($conditions, [
                    'multi_H1'    => (is_countable($this->value['h1']) ? count($this->value['h1']) : 0) > 1,
                    'too_long_H1' => strlen((string) $this->value['h1'][0])                             > 35,
                ]);
            }
        }

        return parent::setUpResultsConditions($conditions);
    }
}
