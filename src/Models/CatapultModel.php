<?php

namespace Joeabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatapultModel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'packages' => 'array',
        'extends' => 'array',
        'implements' => 'array',
        'traits' => 'array',
        'imports' => 'array',
        'properties' => 'array',
    ];

    protected $table = 'catapult_models';
    public function relationships()
    {
        return $this->hasMany(CatapultRelationship::class);
    }

    public function migration()
    {
        return $this->hasOne(CatapultMigration::class);
    }

    public function fields()
    {
        return $this->hasMany(CatapultField::class);
    }
}
