<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['list_case_id', 'description', 'chamfer', 'gazor_hinge', 'groove',
        'pvc', 'qty', 'dimensions', 'sortable'];
}
