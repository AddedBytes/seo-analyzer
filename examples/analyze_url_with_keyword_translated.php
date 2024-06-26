<?php
use SeoAnalyzer\HttpClient\Exception\HttpException;
/**
 * Web page analyze example
 *
 * Executes web page full seo analyze.
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use SeoAnalyzer\Analyzer;

try {
    $results = (new Analyzer())->analyzeUrl('https://www.msn.com/pl-pl', 'msn', 'pl_PL');
} catch (HttpException $e) {
    echo "Error loading page: " . $e->getMessage();
} catch (ReflectionException $e) {
    echo "Error loading metric file: " . $e->getMessage();
}

print_r($results);
