<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Compterendu extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'ressources', 'devoir_id', 'user_id','note' , 'comment'];

    public function devoir()
    {
        return $this->belongsTo(Devoir::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'compterendu_user');
    }
}
