<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{

    use HasFactory;
    protected $table = 'banner';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'title_en',
        'banner_file',
        'banner_en_file',
        'link_url',
        'link_url_en',
        'level',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
