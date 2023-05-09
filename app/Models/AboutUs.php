<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AboutUs extends Model
{

    use HasFactory;
    protected $table = 'about_us';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'title_en',
        'detail',
        'detail_en',
        'level',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
