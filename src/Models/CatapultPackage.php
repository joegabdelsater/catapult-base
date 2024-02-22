<?php

namespace Joeabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatapultPackage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'catapult_packages';
}
