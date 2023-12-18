<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\Models\{
    Questionnaire
};

class EvaluationEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function evaluator () {
        return $this->belongsTo(User::class, 'evaluator_id', 'id');
    }

    public function evaluatee () {
        return $this->hasOne(User::class, 'id', 'evaluatee_id');
    }

    public function questionnaire () {
        return $this->belongsTo(Questionnaire::class);
    }

    public function academicYear () {
        return $this->belongsTo(AcademicYear::class);
    }

    public function evaluationAnswers () {
        return $this->belongsTo(EvaluationAnswer::class);
    }
}
