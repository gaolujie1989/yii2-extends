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
                    'xxxActionA' => [
                        'label' => 'xxxActionA',
                        'sort' => 10,
                        'actionKeys' => ['xxxAction2', 'xxxAction3'],
                        'permissionKeys' => ['xxxModule2/xxxController2/xxxAction2', 'xxxModule3/xxxController3/xxxAction3'],
                    ],
                    'xxxActionB' => [
                        'label' => 'xxxActionA',
                        'sort' => 20,
                        'actionKeys' => ['xxxAction3'],
                        'permissionKeys' => ['xxxModule3/xxxController3/xxxAction3'],
                    ],
                ]
            ]
        ]
    ],
];