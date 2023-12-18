<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\Models\{
    Questionnaire,
    CriteriaPoint,
    Question
};

class Criteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function questionnaire () {
        return $this->belongsTo(Questionnaire::class);
    }

    public function criteriaPoints () {
        return $this->hasMany(CriteriaPoint::class);
    }

    public function questions () {
        return $this->hasMany(Question::class);
    }

    public function evaluationAnswers () {
        return $this->hasMany(EvaluationAnswer::class);
    }
}
