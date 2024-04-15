<?php

namespace SeoAnalyzer\Metric\File;

use Override;
use SeoAnalyzer\Metric\AbstractMetric;

class SitemapMetric extends AbstractMetric
{
    public string $description = 'Does the site use a site map file "sitemap.xml"?';

    /**
     * @inheritdoc
     */
    #[Override]
    public function analyze(): string
    {
        if (empty($this->value)) {
            $this->impact = 1;

            return 'You should consider adding a sitemap.xml file, as this could help with indexing';
        }

        return 'yes';
    }
}
