<?php

namespace Joeabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatapultController extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'catapult_controllers';

    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
