<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;


class AccountPaymentGateway extends MyBaseModel
{

    use softDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_gateway_id',
        'config'
    ];

    /**
     * Account associated with gateway
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class);
    }

    /**
     * Parent payment gateway
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment_gateway()
    {
        return $this->belongsTo(\App\Models\PaymentGateway::class, 'payment_gateway_id', 'id');
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getConfigAttribute($value)
    {
        try {
            return json_decode(Crypt::decrypt($value), true);
        } catch (DecryptException $e) {
            return $value;
        }
    }

    public function setConfigAttribute($value)
    {
        $this->attributes['config'] = Crypt::encrypt(json_encode($value));
    }
}
