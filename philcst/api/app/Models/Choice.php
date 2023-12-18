<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\Models\{
    CriteriaPoint
};

class Choice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function criteriaPoint () {
        return $this->belongsTo(CriteriaPoint::class);
    }

    public function evaluationAnswer () {
        return $this->hasOne(EvaluationAnswer::class);
    }
}
