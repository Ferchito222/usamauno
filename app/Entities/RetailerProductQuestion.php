<?php

namespace Sibas\Entities;

use Illuminate\Database\Eloquent\Model;

class RetailerProductQuestion extends Model
{

    protected $table = 'ad_retailer_product_questions';

    protected $casts = [
        'response'      => 'boolean',
        'response_text' => 'boolean',
    ];


    public function question()
    {
        return $this->belongsTo(Question::class, 'ad_question_id', 'id');
    }
}
