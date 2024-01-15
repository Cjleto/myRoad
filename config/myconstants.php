<?php


return [
    'initial_roles' => [
        'admin' => 'admin',
        'editor' => 'editor',
    ],
    'initial_permissions' => [
        'admin' => [
            'can_create_travels',
            'can_create_tours'
        ],
        'editor' => [
            'can_update_travels'
        ],
    ],
    'initial_moods' => [
        'nature',
        'relax',
        'history',
        'culture',
        'party',
    ]
];
