<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{

    use HasFactory;
    protected $table = 'team';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'team_file',
        'prefix',
        'firstname',
        'surname',
        'prefix_en',
        'firstname_en',
        'surname_en',
        'email',
        'department_id',
        'position_type',
        'position',
        'position_type_en',
        'position_en',
        'level',
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
