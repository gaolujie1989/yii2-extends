<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'xxxModule' => [
        'label' => 'xxxModule',
        'sort' => 10,
        'groups' => [
            'xxxController' => [
                'label' => 'xxxController',
                'sort' => 10,
                'permissions' => [
                    'xxxAction' => [
                        'label' => 'xxxAction',
                        'sort' => 10,
                        'actionKeys' => ['xxxAction2', 'xxxAction3'],
                        'permissionsKeys' => ['xxxModule2/xxxController2/xxxAction2', 'xxxModule3/xxxController3/xxxAction3'],
                    ]
                ]
            ]
        ]
    ],
];