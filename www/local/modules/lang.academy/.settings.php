<?

return [
    'ui.entity-selector' => [
        'value' => [
            'entities' => [
                [
                    'entityId' => 'lang-user',
                    'provider' => [
                        'moduleId' => 'lang.academy',
                        'className' => \Lang\Academy\UsersChatProvider::class,
                    ],
                ],
            ],
            'filters' => [
                [
                    'entityId' => 'lang-user',
                    'id' => 'lang.academy.imUsersFilter',
                    'className' => \Lang\Academy\ImUsersFilter::class,
                ],
            ],
        ],
        'readonly' => true,
    ],
];