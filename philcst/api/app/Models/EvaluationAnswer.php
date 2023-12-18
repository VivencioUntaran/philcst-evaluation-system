<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
};

class EvaluationAnswer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function evaluationEntry () {
      return $this->belongsTo(EvaluationEntry::class);
    }

    public function criteria () {
      return $this->belongsTo(Criteria::class);
    }

    public function question () {
      return $this->belongsTo(Question::class);
    }

    public function choice () {
      return $this->belongsTo(Choice::class);
    }

}


