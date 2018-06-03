<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class QuestionAnswer extends MyBaseModel
{

    protected $fillable = [
        'question_id',
        'event_id',
        'attendee_id',
        'account_id',
        'answer_text',
        'questionable_id',
        'questionable_type',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function event()
    {
        return $this->belongsToMany(\App\Models\Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(\App\Models\Question::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attendee()
    {
        return $this->belongsTo(\App\Models\Attendee::class);
    }

    public function getAnswerTextAttribute($value) {
        try {
            return Crypt::decrypt($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }

    public function setAnswerTextAttribute($value) {
        return $this->attributes['answer_text'] = Crypt::encrypt($value);
    }
}
