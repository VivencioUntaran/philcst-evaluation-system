<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\Models\{
    Criteria
};

class CriteriaPoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function criteria () {
        return $this->belongsTo(Criteria::class);
    }

    public function evaluationAnswers () {
        return $this->hasMany(EvaluationAnswer::class);
    }
}
