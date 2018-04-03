<?php

return [
    'name' => 'User',
    'admin.widget' => Modules\User\Http\Widget\UserWidget::class,
    'admin.navigation' => [
        'sale' => [
            \Modules\User\Http\Navigation\AdminUserNavigation::class
        ],
    ]
];
