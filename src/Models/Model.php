<?php

namespace Joegabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];

    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    public function migration()
    {
        return $this->hasOne(CatapultMigration::class);
    }
}
