<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'section_id'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
