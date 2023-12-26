<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['list_case_id', 'description', 'sumPVC', 'sumCutting', 'countParts',
        'sumGroove', 'sumChamfers', 'cutting', 'chamfer', 'groove', 'pvc'];

    /**
     * Returns a HasOne relationship for the listCase.
     */
    public function listCase(): HasOne
    {
        return $this->hasOne(ListCase::class, 'id', 'list_case_id');
    }
}
