<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    protected $dates = ['deleted_at'];
    protected $table = 'cat_module';
    protected $primaryKey = 'id';

    protected $fillable =
    [
        "name",
        "key",
        "description",
        "active"
    ];
}
