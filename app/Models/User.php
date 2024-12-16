<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'role',
        'groupe_id',
        'section_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupe_id');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_user');
    }
    public function compterendus()
    {
        return $this->belongsToMany(Compterendu::class, 'compterendu_user');
    }
}
