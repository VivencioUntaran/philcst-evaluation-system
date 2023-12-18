<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\Models\{
    Choice
};

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function criteria () {
        return $this->belongsTo(Criteria::class);
    }

    public function choices () {
        return $this->hasMany(Choice::class);
    }

    public function evaluationAnswers () {
        return $this->hasMany(EvaluationAnswer::class);
    }
}
