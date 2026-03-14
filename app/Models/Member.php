<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
   protected $fillable = [
'user_id',
'member_id',
'name',
'mobile',
'email',
'gender',
'age',
'address',
'plan',
'batch',
'trainer',
'total_fees',
'paid_amount',
'pending_amount',
'payment_mode'
];

}
