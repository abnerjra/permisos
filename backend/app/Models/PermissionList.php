<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionList extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    protected $dates = ['deleted_at'];
    protected $table = 'cat_permission_list';
    protected $primaryKey = 'id';

    protected $fillable =
    [
        "name",
        "key",
        "active"
    ];
}
