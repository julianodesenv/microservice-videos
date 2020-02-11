<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Traits\Uuid;

class Genre extends Model
{

    use SoftDeletes, Uuid;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'is_active'];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];

    public $incrementing = false;

}
