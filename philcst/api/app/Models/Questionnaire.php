<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\Models\{
    Criteria,
    EvaluationEntry
};

class Questionnaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function criterias () {
        return $this->hasMany(Criteria::class);
    }
    
    public function evaluationEntries () {
        return $this->hasMany(EvaluationEntry::class);
      }
  
}
