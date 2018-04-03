<?php

return [
    'name' => 'Newsletter',
    'admin.navigation' => [
        'personalize' => [
            \Modules\Newsletter\Http\Navigation\AdminNewsletterNavigation::class
        ]
    ]
];
