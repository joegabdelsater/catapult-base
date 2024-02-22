<?php

namespace Joeabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class CatapultMigration extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'catapult_migrations';
    public function model()
    {
        return $this->belongsTo(Model::class);
    }
}
