<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Bank extends Model

{

      protected $fillable = [

        'bank_name',

        'account_number',

        'holder_name',

        'ifsc_code',

        'branch_name',

        'is_active',

        'gst',
         'status',
          'upi'

    ];

}

