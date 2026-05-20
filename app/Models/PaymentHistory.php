<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class PaymentHistory extends Model

{

     protected $fillable = [

        'invoice_id',

        'amount',

        'receipt',

        'status',

    ];



    public function invoice()

    {

        return $this->belongsTo(Invoice::class);

    }

}

