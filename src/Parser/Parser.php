<?php

declare(strict_types=1);

namespace SeoAnalyzer\Parser;

class Parser extends AbstractParser
{
    /**
     * @inheritDoc
     */
    public function getMeta(): array
    {
        $meta = [];
        foreach ($this->getDomElements('meta') as $domElement) {
            $meta[$domElement->getAttribute('name')] = trim($domElement->getAttribute('content'));
        }

        return $meta;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        $headers = [];
        for ($x = 1; $x <= 5; ++$x) {
            foreach ($this->getDomElements('h' . $x) as $item) {
                $headers['h' . $x][] = trim((string) $item->nodeValue);
            }
        }

        return $headers;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        if ($this->getDomElements('title')->length > 0) {
            return trim((string) $this->getDomElements('title')->item(0)->nodeValue);
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function getAlts(): array
    {
        $alts = [];
        if ($this->getDomElements('img')->length > 0) {
            foreach ($this->getDomElements('img') as $domElement) {
                $alts[] = trim($domElement->getAttribute('alt'));
            }
        }

        return $alts;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        $this->removeTags('script');
        $this->removeTags('style');
        $text = strip_tags($this->dom->saveHTML());

        return preg_replace('!\s+!', ' ', strip_tags($text));
    }
}
