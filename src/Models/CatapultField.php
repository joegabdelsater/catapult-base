<?php

namespace Joeabdelsater\CatapultBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatapultField extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'column_config' => 'array',
        'admin_column_config' => 'array',
        'admin_field_config' => 'array',
    ];

    protected $table = 'catapult_model_fields';

    public function model()
    {
        return $this->belongsTo(CatapultModel::class);
    }
    
}
