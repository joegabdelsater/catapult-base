<?php

return [
    'supported' => [
        'one_to_one' => 'hasOne',
        'one_to_many' => 'hasMany',
        'many_to_one' => 'belongsTo',
    ],
    'function_parameters' => [
        'one_to_one' => ['foreign_key', 'local_key'],
        'one_to_many' => ['foreign_key', 'local_key'],
        'many_to_one' => ['foreign_key', 'owner_key']
    ],
];
