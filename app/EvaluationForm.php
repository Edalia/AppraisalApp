<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationForm extends Model
{
    protected $table = 'evaluationform';

    protected $fillable = [
        'evaluator',
        'employee',
        'start_period',
        'end_period',
        'evaluation_date',
        'isSubmitted',
        'isArchived',
        'archived_date',
        'comment',
        'final_rate',
        'titlename',
        'updated_at',
    ];

    // public function evaluationForm() {
    //     return $this->belongsTo(EvaluationForm::class);
    // }
}
