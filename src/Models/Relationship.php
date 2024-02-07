<?php

namespace Joegabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Relationship extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'relationships';
    
    public function model()
    {
        return $this->belongsTo(Model::class);
    }
}
