<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description' , 'section_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'module_user');
    }
    public function devoirs()
    {
        return $this->hasMany(Devoir::class);
    }
}
