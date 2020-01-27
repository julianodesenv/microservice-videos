<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Traits\Uuid;

class Category extends Model
{

    use SoftDeletes, Uuid;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;

}
