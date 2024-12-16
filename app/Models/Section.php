<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
