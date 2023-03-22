<?php

use SeoAnalyzer\Metric\File\RobotsMetric;
use SeoAnalyzer\Metric\File\SitemapMetric;
return [
    [
        'file.robots',
        "User-agent: *\nDisallow:\n",
        [
            'class' => '\\' . RobotsMetric::class,
            'value' => "User-agent: *\nDisallow:\n",
            'impact' => 0,
            'analysis' => 'yes'
        ]
    ],
    [
        'file.robots',
        false,
        [
            'class' => '\\' . RobotsMetric::class,
            'value' => false,
            'impact' => 1,
            'analysis' => 'no'
        ]
    ],
    [
        'file.robots',
        'Disallow: /*',
        [
            'class' => '\\' . RobotsMetric::class,
            'value' => 'Disallow: /*',
            'impact' => 5,
            'analysis' => 'Robots.txt file blocks some parts of your site'
        ]
    ],

    [
        'file.sitemap',
        '<?xml version="1.0" encoding="UTF-8"?>',
        [
            'class' => '\\' . SitemapMetric::class,
            'value' => '<?xml version="1.0" encoding="UTF-8"?>',
            'impact' => 0,
            'analysis' => 'yes'
        ]
    ],
    [
        'file.sitemap',
        false,
        [
            'class' => '\\' . SitemapMetric::class,
            'value' => false,
            'impact' => 1,
            'analysis' => 'You should consider adding a sitemap.xml'
        ]
    ]
];
