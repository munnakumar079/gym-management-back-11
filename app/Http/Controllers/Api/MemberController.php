<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'mobile' => 'required'
    ]);

    $user = auth()->user();

    $gymName = strtoupper(str_replace(' ', '', $user->gym_detail->gym_name));

    $randomNumber = rand(100000, 999999);

    $memberId = $gymName . $randomNumber;

    $member = Member::create([
        'user_id' => $user->id,
        'member_id' => $memberId,
        'name' => $request->name,
        'mobile' => $request->mobile,
        'email' => $request->email,
        'gender' => $request->gender,
        'age' => $request->age,
        'address' => $request->address,
        'plan' => $request->plan,
        'batch' => $request->batch,
        'trainer' => $request->trainer,
        'total_fees' => $request->total_fees,
        'paid_amount' => $request->paid_amount,
        'pending_amount' => $request->pending_amount,
        'payment_mode' => $request->payment_mode
    ]);

    return response()->json([
        "success" => true,
        "message" => "Member Added Successfully",
        "data" => $member
    ]);
}

}
