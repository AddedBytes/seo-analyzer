<?php

declare(strict_types=1);

namespace SeoAnalyzer\Parser;

class ExampleCustomParser extends Parser
{
    /**
     * @inheritDoc
     */
    #[\Override]
    public function getAlts(): array
    {
        $alts = [];
        if ($this->getDomElements('img')->length > 0) {
            foreach ($this->getDomElements('img') as $domElement) {
                $alts[] = [
                    'alt' => trim($domElement->getAttribute('alt')),
                    'src' => trim($domElement->getAttribute('src')),
                ];
            }
        }

        return $alts;
    }
}
