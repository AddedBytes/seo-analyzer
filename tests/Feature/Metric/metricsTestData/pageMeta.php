<?php

declare(strict_types=1);

namespace Tests\TestCase\Metric;

use SeoAnalyzer\Metric\Page\MetaMetric;

return [
    [
        'page.meta',
        ['title' => 'Lorem ipsum dolor', 'meta' => ['description' => 'Lorem ipsum dolor sit ipsum dolor sit']],
        [
            'class' => '\\' . MetaMetric::class,
            'value' => ['title' => 'Lorem ipsum dolor', 'meta' => ['description' => 'Lorem ipsum dolor sit ipsum dolor sit']],
            'impact' => 0,
            'analysis' => 'The site meta tags look good'
        ]
    ],
    [
        'page.meta',
        [],
        [
            'class' => '\\' . MetaMetric::class,
            'value' => [],
            'impact' => 8,
            'analysis' => 'Missing page title and description meta tags. You should add the title'
        ]
    ],
    [
        'page.meta',
        ['title' => false, 'meta' => false],
        [
            'class' => '\\' . MetaMetric::class,
            'value' => ['title' => false, 'meta' => false],
            'impact' => 5,
            'analysis' => 'The page title length should be between'
        ]
    ],
    [
        'page.meta',
        ['title' => 'Lorem ipsum dolor sit', 'meta' => false],
        [
            'class' => '\\' . MetaMetric::class,
            'value' => ['title' => 'Lorem ipsum dolor sit', 'meta' => false],
            'impact' => 5,
            'analysis' => 'Missing page meta description tag'
        ]
    ],
    [
        'page.meta',
        ['title' => false, 'meta' => ['description' => 'Lorem ipsum dolor sit ipsum dolor sit']],
        [
            'class' => '\\' . MetaMetric::class,
            'value' => ['title' => false, 'meta' => ['description' => 'Lorem ipsum dolor sit ipsum dolor sit']],
            'impact' => 5,
            'analysis' => 'The page title length should be between'
        ]
    ],
    [
        'page.meta',
        [
            'title' => 'Lorem ipsum dolor sit ipsum dolor sit ipsum dolor sit ipsum dolor sit ipsum dolor sit',
            'meta' => ['description' => 'Lorem ipsum dolor sit']
        ],
        [
            'class' => '\\' . MetaMetric::class,
            'impact' => 5,
            'analysis' => 'The page title length should be between'
        ]
    ],
    [
        'page.meta',
        [
            'title' => 'Lorem ipsum dolor sit',
            'meta' => ['description' => 'Lorem ipsum dolor sit ipsum dolor sit ipsum dolor sit ipsum dolor sit ipsum dolor sit ipsum dolor sit ipsum dolor sit ipsum dolor sit']
        ],
        [
            'class' => '\\' . MetaMetric::class,
            'impact' => 3,
            'analysis' => 'The page meta description length should be between'
        ]
    ]
];
