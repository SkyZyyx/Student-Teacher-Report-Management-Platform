<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devoir extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'name','title','description','ressources','due_date'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function compterendus()
    {
        return $this->hasMany(Compterendu::class);
    }
}
