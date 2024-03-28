<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Structure extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['deleted_at'];
    protected $table = 'cat_structure';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'acronym',
        'color',
        'active'
    ];

    public function usuario()
    {
        return $this->hasMany(User::class, 'structure_id');
    }
}
