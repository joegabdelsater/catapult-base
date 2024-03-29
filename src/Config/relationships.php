<?php

return [
    'supported' => [
        'one_to_one' => 'hasOne',
        'one_to_many' => 'hasMany',
        'many_to_one' => 'belongsTo',
        'many_to_many' => 'belongsToMany',
        'polymorphic_morph_to' => 'morphTo',
        'polymorphic_morph_one' => 'morphOne',
        'polymorphic_morph_many' => 'morpMany',

    ],
    'function_parameters' => [
        'one_to_one' => ['foreign_key', 'local_key'],
        'one_to_many' => ['foreign_key', 'local_key'],
        'many_to_one' => ['foreign_key', 'owner_key'],
        'many_to_many' => ['table', 'model_foreign_key', 'related_model_foreign_key'],
        'polymorphic_morph_to' => [],
        'polymorphic_morph_one' => ['polymorphic_relation'],
        'polymorphic_morph_many' => ['polymorphic_relation'],
    ],
];
