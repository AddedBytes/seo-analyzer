<?php

declare(strict_types=1);

namespace SeoAnalyzer\Parser;

use DOMDocument;
use DOMElement;
use DOMNodeList;

abstract class AbstractParser implements ParserInterface
{
    protected DOMDocument $dom;

    public function __construct(string|null $html = null)
    {
        $this->dom = new DOMDocument();
        if ($html !== null && $html !== '' && $html !== '0') {
            $this->setContent($html);
        }
    }

    public function setContent(mixed $html): void
    {
        $internalErrors = libxml_use_internal_errors(true);
        $this->dom->loadHTML($html, LIBXML_NOWARNING);
        libxml_use_internal_errors($internalErrors);
    }

    protected function removeTags(string $tag): void
    {
        $tagsToRemove = [];
        foreach ($this->getDomElements($tag) as $tag) {
            $tagsToRemove[] = $tag;
        }
        foreach ($tagsToRemove as $tagToRemove) {
            $tagToRemove->parentNode->removeChild($tagToRemove);
        }
    }

    protected function getDomElements(string $name): DOMNodeList|DOMElement
    {
        return $this->dom->getElementsByTagName($name);
    }
}
