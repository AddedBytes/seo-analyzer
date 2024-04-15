<?php

namespace SeoAnalyzer;

class Factor
{
    final public const string PAGE        = 'page';
    final public const string PARSED      = 'parsed';
    final public const string PATH        = 'path';
    final public const string HOST        = 'host';
    final public const string LENGTH      = 'length';
    final public const string SSL         = 'SSL';
    final public const string LOAD_TIME   = 'loadTime';
    final public const string REDIRECT    = 'redirect';
    final public const string HEADERS     = 'headers';
    final public const string TEXT        = 'text';
    final public const string ALTS        = 'alts';
    final public const string HTML        = 'html';
    final public const string SIZE        = 'size';
    final public const string RATIO       = 'ratio';
    final public const string DESCRIPTION = 'description';

    final public const string URL             = 'url';
    final public const string URL_PARSED      = self::URL . '.' . self::PARSED;
    final public const string URL_PARSED_PATH = self::URL . '.' . self::PARSED . '.' . self::PATH;
    final public const string URL_PARSED_HOST = self::URL . '.' . self::PARSED . '.' . self::HOST;
    final public const string URL_LENGTH      = self::URL . '.' . self::LENGTH;

    final public const string META             = 'meta';
    final public const string TITLE            = 'title';
    final public const string META_META        = self::META . '.' . self::META;
    final public const string META_TITLE       = self::META . '.' . self::TITLE;
    final public const string META_DESCRIPTION = self::META . '.' . self::DESCRIPTION;

    final public const string CONTENT       = 'content';
    final public const string CONTENT_HTML  = self::CONTENT . '.' . self::HTML;
    final public const string CONTENT_SIZE  = self::CONTENT . '.' . self::SIZE;
    final public const string CONTENT_RATIO = self::CONTENT . '.' . self::RATIO;

    final public const string DENSITY         = 'density';
    final public const string DENSITY_PAGE    = self::DENSITY . '.' . self::PAGE;
    final public const string DENSITY_HEADERS = self::DENSITY . '.' . self::HEADERS;

    final public const string KEYWORD             = 'keyword';
    final public const string KEYWORDS            = 'keywords';
    final public const string KEYWORD_URL         = self::KEYWORD . '.' . self::URL;
    final public const string KEYWORD_PATH        = self::KEYWORD . '.' . self::PATH;
    final public const string KEYWORD_TITLE       = self::KEYWORD . '.' . self::TITLE;
    final public const string KEYWORD_DESCRIPTION = self::KEYWORD . '.' . self::DESCRIPTION;
    final public const string KEYWORD_HEADERS     = self::KEYWORD . '.' . self::HEADERS;
    final public const string KEYWORD_DENSITY     = self::KEYWORD . '.' . self::DENSITY;
}
