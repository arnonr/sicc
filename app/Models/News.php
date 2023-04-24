<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{

    use HasFactory;
    protected $table = 'news';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'title_en',
        'news_file',
        'news_en_file',
        'news_type_id',
        'detail',
        'detail_en',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
