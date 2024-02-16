<?php
return [
    'spatie_translatable' => [
        "dev" => false,
        'package_name' => 'spatie/laravel-translatable',
        'composer_require' => 'spatie/laravel-translatable',
        'post_install' => [],
        'model' => [
            'imports' => [
                'use Spatie\Translatable\HasTranslations;',
            ],
            'traits' => [
                'use HasTranslations;',
            ],
            'extends' => [],
            'implements' => [],
            'methods' => [],
            'properties' => [
                'public $translatable = [];',
            ],
        ]
    ],
    'spatie_sluggable' => [
        "dev" => false,
        'package_name' => 'spatie/laravel-sluggable',
        'composer_require' => 'spatie/laravel-sluggable',
        'post_install' => [],
        'model' => [
            'imports' => [
                'use Spatie\Sluggable\HasSlug;',
                'use Spatie\Sluggable\SlugOptions;'
            ],
            'traits' => [
                'use HasSlug;',
            ],
            'extends' => [],
            'implements' => [],
            'methods' => ["public function getSlugOptions() : SlugOptions
            {
                return SlugOptions::create()
                    ->generateSlugsFrom('name')
                    ->saveSlugsTo('slug');
            }"],
            'properties' => [],
        ]
    ]
];
