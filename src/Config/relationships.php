<?php

return [
    'supported' => [
        'one_to_one' => 'hasOne(Model::class)',
        'one_to_many' => 'hasMany(Model::class)',
        'many_to_one' => 'belongsTo(Model::class)',
    ],
    
];