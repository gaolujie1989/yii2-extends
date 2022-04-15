<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    'xxxModuleA' => [
        'label' => 'xxxModuleA',
        'sort' => 10,
        'groups' => [
            'xxxControllerA' => [
                'label' => 'xxxControllerA',
                'sort' => 10,
                'permissions' => [
                    'xxxAction' => [
                        'label' => 'xxxAction',
                        'sort' => 10,
                        'actionKeys' => ['xxxAction2', 'xxxAction3'],
                        'permissionKeys' => ['xxxModule2_xxxController2_xxxAction2', 'xxxModule3_xxxController3_xxxAction3'],
                    ],
                    'xxxActionA' => [
                        'label' => 'xxxActionA',
                        'sort' => 20,
                        'actionKeys' => ['xxxAction2'],
                        'permissionKeys' => ['xxxModule2_xxxController2_xxxAction2'],
                    ]
                ]
            ]
        ]
    ],
];