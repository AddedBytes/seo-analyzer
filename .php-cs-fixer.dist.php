<?php

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = Symfony\Component\Finder\Finder::create()
                                         ->in([
                                             __DIR__ . '/src',
                                         ])
                                         ->exclude(['config', 'build', 'vendor'])
                                         ->name('*.php')
                                         ->name('.php_cs.dist')
                                         ->name('_ide_helper')
                                         ->name('*.php')
                                         ->ignoreDotFiles(true)
                                         ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS2.0:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'trailing_comma_in_multiline' => true,
        'phpdoc_scalar' => true,
        'unary_operator_spaces' => true,
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_var_without_name' => true,
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
            ],
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => true,
        ],
        'single_trait_insert_per_statement' => true,
        'nullable_type_declaration_for_default_null_value' => true,
    ])
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setFinder($finder);
