<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsGallery extends Model
{

    use HasFactory;
    protected $table = 'news_gallery';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'news_id',
        'news_gallery_file',
        'secret_key',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
