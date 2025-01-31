<?php

declare(strict_types=1);

namespace SeoAnalyzer\Metric\Page;

use SeoAnalyzer\Metric\AbstractMetric;

class RedirectMetric extends AbstractMetric
{
    public string $description = 'Does the main URL redirects to other?';

    /**
     * @inheritdoc
     */
    public function analyze(): string
    {
        if (! empty($this->value)) {
            $this->impact = 2;

            return 'You should avoid redirects, as this could have impact on SEO';
        }

        return 'no';
    }
}
