<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class [ModelName] extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
[ModelFields]
    ];

    public static $searchable = [
[ModelFields]
    ];
}
