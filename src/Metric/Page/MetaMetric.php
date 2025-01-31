<?php

declare(strict_types=1);

namespace SeoAnalyzer\Metric\Page;

use SeoAnalyzer\Factor;
use SeoAnalyzer\Metric\AbstractMetric;

class MetaMetric extends AbstractMetric
{
    public string $description = 'Html meta tags information';

    protected array $results = [
        'no_tags' => [
            self::IMPACT => 8,
            self::MESSAGE => 'Missing page title and description meta tags. You should add the title meta tag at least',
        ],
        'title_length' => [
            self::IMPACT => 5,
            self::MESSAGE => 'The page title length should be between 10 and 60 characters.' .
                ' Title should also include your main keyword',
        ],
        'missing_description' => [
            self::IMPACT => 5,
            self::MESSAGE => 'Missing page meta description tag. We strongly recommend to add it.' .
                ' It should be between 30 and 120 characters and should include your main keyword',
        ],
        'description_length' => [
            self::IMPACT => 3,
            self::MESSAGE => 'The page meta description length should be between 30 and 120 characters.' .
                ' Description should also include your main keyword',
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
    public function analyze(): string
    {
        return $this->checkTheResults('The site meta tags look good');
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    protected function setUpResultsConditions(array $conditions = []): bool
    {
        $conditions = ['no_tags' => empty($this->value)];
        if (empty($this->value)) {
            return parent::setUpResultsConditions($conditions);
        }
        $conditions = [...$conditions, 'title_length' => $this->checkTitleTag(), 'missing_description' => isset($this->value['meta']) && empty($this->value['meta'][self::DESCRIPTION]), 'description_length' => $this->checkMetaDescriptionTag()];

        return parent::setUpResultsConditions($conditions);
    }

    private function checkTitleTag(): bool
    {
        return isset($this->value[Factor::TITLE])
            && (strlen((string) $this->value[Factor::TITLE]) < 10 || strlen((string) $this->value[Factor::TITLE]) > 60);
    }

    private function checkMetaDescriptionTag(): bool
    {
        return isset($this->value[Factor::META][self::DESCRIPTION])
            && (strlen((string) $this->value[Factor::META][self::DESCRIPTION]) < 30
                || strlen((string) $this->value[Factor::META][self::DESCRIPTION]) > 120);
    }
}
