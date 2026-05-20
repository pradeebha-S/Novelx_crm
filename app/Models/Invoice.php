<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model

{

    protected $fillable = [

        'invoice_no',

        'invoice_date',

        'project_id',

        'client_name',

        'mobile',

        'address',

        'bank_id',

        'subtotal',

        'tax',

        'discount',

           'tax_percentage',

        'discount_percentage',

        'paid_amount',

        'total',

        'remarks',
          'status',

    ];

    public function items()

    {

        return $this->hasMany(InvoiceItem::class);

    }

    public function payments()

    {

        return $this->hasMany(PaymentHistory::class);

    }

    public function project()

    {

        return $this->belongsTo(Project::class);

    }

    public function bank()

    {

        return $this->belongsTo(Bank::class);

    }

    public function getBalanceAttribute()

    {

        return $this->total - $this->paid_amount;

    }

}

