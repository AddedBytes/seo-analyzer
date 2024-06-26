<?php

declare(strict_types=1);

namespace Tests\TestCase\Metric;

use SeoAnalyzer\Metric\Page\HeadersMetric;

return [
    [
        'page.headers',
        ['h1' => ['Lorem ipsum'], 'h2' => ['Lorem ipsum', 'dolor sit'], 'h3' => ['lorem ipsum']],
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => ['h1' => ['Lorem ipsum'], 'h2' => ['Lorem ipsum', 'dolor sit'], 'h3' => ['lorem ipsum']],
            'impact' => 0,
            'analysis' => 'The headers structure on the site looks very good'
        ]
    ],
    [
        'page.headers',
        false,
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => false,
            'impact' => 7,
            'analysis' => 'Looks the site has no headers at all'
        ]
    ],
    [
        'page.headers',
        ['h3' => ['lorem ipsum', 'dolor sit']],
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => ['h3' => ['lorem ipsum', 'dolor sit']],
            'impact' => 5,
            'analysis' => 'There is no H1 header on the site'
        ]
    ],
    [
        'page.headers',
        ['h1' => ['lorem ipsum dolor sit lorem ipsum dolor sit']],
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => ['h1' => ['lorem ipsum dolor sit lorem ipsum dolor sit']],
            'impact' => 3,
            'analysis' => 'The H1 header is too long'
        ]
    ],
    [
        'page.headers',
        ['h1' => ['lorem ipsum', 'dolor sit lorem']],
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => ['h1' => ['lorem ipsum', 'dolor sit lorem']],
            'impact' => 3,
            'analysis' => 'There are multiple H1 headers on the site'
        ]
    ],
    [
        'page.headers',
        ['h1' => ['lorem ipsum dolor sit']],
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => ['h1' => ['lorem ipsum dolor sit']],
            'impact' => 3,
            'analysis' => 'There are no H2 headers on the site'
        ]
    ],
    [
        'page.headers',
        ['h1' => ['lorem ipsum dolor sit'], 'h2' => ['a', 'b', 'c', 'd', 'e', 'f']],
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => ['h1' => ['lorem ipsum dolor sit'], 'h2' => ['a', 'b', 'c', 'd', 'e', 'f']],
            'impact' => 1,
            'analysis' => 'There are a lot of H2 headers'
        ]
    ],
    [
        'page.headers',
        ['h1' => ['lorem ipsum dolor sit'], 'h2' => ['a', 'b']],
        [
            'class' => '\\' . HeadersMetric::class,
            'value' => ['h1' => ['lorem ipsum dolor sit'], 'h2' => ['a', 'b']],
            'impact' => 1,
            'analysis' => 'There are no H3 header on the site'
        ]
    ]
];
