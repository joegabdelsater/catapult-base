<?php

namespace Joeabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Route extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'catapult_controller_routes';
}
