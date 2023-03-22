<?php

use SeoAnalyzer\Metric\Page\SSLMetric;
use SeoAnalyzer\Metric\Page\Content\SizeMetric;
use SeoAnalyzer\Metric\Page\Content\RatioMetric;
use SeoAnalyzer\Metric\Page\AltsMetric;
use SeoAnalyzer\Metric\Page\LoadTimeMetric;
use SeoAnalyzer\Metric\Page\Url\LengthMetric;
use SeoAnalyzer\Metric\Page\RedirectMetric;
return  [
    [
        'page.ssl',
        false,
        [
            'class' => '\\' . SSLMetric::class,
            'value' => false,
            'impact' => 3,
            'analysis' => 'You should use encrypted connection'
        ]
    ],
    [
        'page.ssl',
        true,
        [
            'class' => '\\' . SSLMetric::class,
            'value' => true,
            'impact' => 0,
            'analysis' => 'yes'
        ]
    ],

    [
        'page.content.size',
        4795,
        [
            'class' => '\\' . SizeMetric::class,
            'value' => 4795,
            'impact' => 0,
            'analysis' => 'The size of your page is ok'
        ]
    ],
    [
        'page.content.size',
        0,
        [
            'class' => '\\' . SizeMetric::class,
            'value' => 0,
            'impact' => 10,
            'analysis' => 'Looks that your site content is empty'
        ]
    ],
    [
        'page.content.size',
        false,
        [
            'class' => '\\' . SizeMetric::class,
            'value' => false,
            'impact' => 10,
            'analysis' => 'Can not read your page content'
        ]
    ],
    [
        'page.content.size',
        30001,
        [
            'class' => '\\' . SizeMetric::class,
            'value' => 30001,
            'impact' => 1,
            'analysis' => 'You should consider some optimisation'
        ]
    ],
    [
        'page.content.size',
        80001,
        [
            'class' => '\\' . SizeMetric::class,
            'value' => 80001,
            'impact' => 3,
            'analysis' => 'The site is very big. You should consider rebuilding'
        ]
    ],
    [
        'page.content.ratio',
        ['content_size' => 1980, 'code_size' => 6960],
        [
            'class' => '\\' . RatioMetric::class,
            'value' => 28.0,
            'impact' => 0,
            'analysis' => 'Page has good content to code ratio'
        ]
    ],
    [
        'page.content.ratio',
        false,
        [
            'class' => '\\' . RatioMetric::class,
            'value' => 0,
            'impact' => 8,
            'analysis' => 'Content to code ratio is too low'
        ]
    ],
    [
        'page.content.ratio',
        ['content_size' => 19, 'code_size' => 100],
        [
            'class' => '\\' . RatioMetric::class,
            'value' => 19.0,
            'impact' => 5,
            'analysis' => 'Consider adding more text to your page or remove unnecessary html code'
        ]
    ],
    [
        'page.content.ratio',
        ['content_size' => 8, 'code_size' => 100],
        [
            'class' => '\\' . RatioMetric::class,
            'value' => 8.0,
            'impact' => 8,
            'analysis' => 'Content to code ratio is too low'
        ]
    ],
    [
        'page.alts',
        ['description1', 'description2', 'description3'],
        [
            'class' => '\\' . AltsMetric::class,
            'value' => ['description1', 'description2', 'description3'],
            'impact' => 0,
            'analysis' => 'Good! All images on site have alternate descriptions'
        ]
    ],
    [
        'page.alts',
        ['description1', '', ''],
        [
            'class' => '\\' . AltsMetric::class,
            'value' => ['description1', '', ''],
            'impact' => 3,
            'analysis' => 'You should optimise your site adding missing alt descriptions'
        ]
    ],
    [
        'page.alts',
        ['desc1', '', '', 'desc2', '', '', '', '', '', 'desc3', '', '', '', ''],
        [
            'class' => '\\' . AltsMetric::class,
            'value' => ['desc1', '', '', 'desc2', '', '', '', '', '', 'desc3', '', '', '', ''],
            'impact' => 5,
            'analysis' => 'There is a lot of images without alternate texts'
        ]
    ],
    [
        'page.alts',
        false,
        [
            'class' => '\\' . AltsMetric::class,
            'impact' => 0,
            'analysis' => 'There is nothing to do here as there is no images on the site'
        ]
    ],

    [
        'page.loadTime',
        0.34,
        [
            'class' => '\\' . LoadTimeMetric::class,
            'value' => 0.34,
            'impact' => 0,
            'analysis' => 'The site loads very fast'
        ]
    ],
    [
        'page.loadTime',
        1.26,
        [
            'class' => '\\' . LoadTimeMetric::class,
            'value' => 1.26,
            'impact' => 2,
            'analysis' => 'You should optimise your site for faster loading'
        ]
    ],
    [
        'page.loadTime',
        4.11,
        [
            'class' => '\\' . LoadTimeMetric::class,
            'value' => 4.11,
            'impact' => 8,
            'analysis' => 'The site takes very long to load'
        ]
    ],

    [
        'page.url.length',
        16,
        [
            'class' => '\\' . LengthMetric::class,
            'value' => 16,
            'impact' => 0,
            'analysis' => 'The size of URL is ok'
        ]
    ],
    [
        'page.url.length',
        30,
        [
            'class' => '\\' . LengthMetric::class,
            'value' => 30,
            'impact' => 1,
            'analysis' => 'You should consider using some shorter URL'
        ]
    ],
    [
        'page.url.length',
        44,
        [
            'class' => '\\' . LengthMetric::class,
            'value' => 44,
            'impact' => 4,
            'analysis' => 'The site URL is very long'
        ]
    ],
    [
        'page.redirect',
        false,
        [
            'class' => '\\' . RedirectMetric::class,
            'impact' => 0,
            'analysis' => 'no'
        ]
    ],
    [
        'page.redirect',
        true,
        [
            'class' => '\\' . RedirectMetric::class,
            'impact' => 2,
            'analysis' => 'You should avoid redirects'
        ]
    ]
];
