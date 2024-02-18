<?php
return [
    'spatie_translatable' => [
        "dev" => false,
        'package_name' => 'spatie/laravel-translatable',
        'composer_require' => 'spatie/laravel-translatable',
        'version' => null,
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
        'version' => null,
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
    ],
    'filament' => [
        "dev" => false,
        'package_name' => 'laravel-filament',
        'composer_require' => 'laravel/filament',
        'version' => '^3.2',
        'post_install' => ['@php artisan filament:install --panels'],
        'model' => [
            'imports' => [
                'use Filament\Models\Contracts\FilamentUser;',
                'use Filament\Panel;',
                'use Illuminate\Foundation\Auth\User as Authenticatable;'
            ],
            'traits' => [],
            'extends' => [], 
            'implements' => [],
            'methods' => ['public function canAccessPanel(Panel $panel): bool
            {
                return str_ends_with($this->email, "@yourdomain.com") && $this->hasVerifiedEmail();
            }'],
            'properties' => [],
        ]
    ]
];
