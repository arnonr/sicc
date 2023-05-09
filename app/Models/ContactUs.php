<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactUs extends Model
{

    use HasFactory;
    protected $table = 'contact_us';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'title_en',
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
