<?php

return [
    'name' => 'Admin',
    'admin.navigation' => [
        'configuring' => [
            \Modules\Admin\Http\Navigation\AdminUsersNavigation::class,
            \Modules\Admin\Http\Navigation\AdminSettingsNavigation::class
        ]
    ]
];
