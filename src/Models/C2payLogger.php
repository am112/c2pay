<?php

namespace Am112\C2pay\Models;

use Illuminate\Database\Eloquent\Model;

class C2payLogger extends Model
{
    protected $table = 'c2pay_loggers';

    protected $fillable = [
        'invoice_no',
        'type',
        'request_data',
        'response_data',
    ];
}