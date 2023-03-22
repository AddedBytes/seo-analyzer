<?php

namespace SeoAnalyzer;

class Factor
{
    final public const PAGE = 'page';
    final public const PARSED = 'parsed';
    final public const PATH = 'path';
    final public const HOST = 'host';
    final public const LENGTH = 'length';
    final public const SSL = 'SSL';
    final public const LOAD_TIME = 'loadTime';
    final public const REDIRECT = 'redirect';
    final public const HEADERS = 'headers';
    final public const TEXT = 'text';
    final public const ALTS = 'alts';
    final public const HTML = 'html';
    final public const SIZE = 'size';
    final public const RATIO = 'ratio';
    final public const DESCRIPTION = 'description';

    final public const URL = 'url';
    final public const URL_PARSED = self::URL . '.' . self::PARSED;
    final public const URL_PARSED_PATH = self::URL . '.' . self::PARSED . '.' . self::PATH;
    final public const URL_PARSED_HOST = self::URL . '.' . self::PARSED . '.' . self::HOST;
    final public const URL_LENGTH = self::URL . '.' . self::LENGTH;

    final public const META = 'meta';
    final public const TITLE = 'title';
    final public const META_META = self::META . '.' . self::META;
    final public const META_TITLE = self::META . '.' . self::TITLE;
    final public const META_DESCRIPTION = self::META . '.' . self::DESCRIPTION;

    final public const CONTENT = 'content';
    final public const CONTENT_HTML = self::CONTENT . '.' . self::HTML;
    final public const CONTENT_SIZE = self::CONTENT . '.' . self::SIZE;
    final public const CONTENT_RATIO = self::CONTENT . '.' . self::RATIO;

    final public const DENSITY = 'density';
    final public const DENSITY_PAGE = self::DENSITY . '.' . self::PAGE;
    final public const DENSITY_HEADERS = self::DENSITY . '.' . self::HEADERS;

    final public const KEYWORD = 'keyword';
    final public const KEYWORDS = 'keywords';
    final public const KEYWORD_URL = self::KEYWORD . '.' . self::URL;
    final public const KEYWORD_PATH = self::KEYWORD . '.' . self::PATH;
    final public const KEYWORD_TITLE = self::KEYWORD . '.' . self::TITLE;
    final public const KEYWORD_DESCRIPTION = self::KEYWORD . '.' . self::DESCRIPTION;
    final public const KEYWORD_HEADERS = self::KEYWORD . '.' . self::HEADERS;
    final public const KEYWORD_DENSITY = self::KEYWORD . '.' . self::DENSITY;
}
