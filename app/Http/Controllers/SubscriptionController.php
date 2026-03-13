<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    
public function index()
{
    return response()->json(Subscription::latest()->get());
}

public function store(Request $request)
{
    $request->validate([
        'price' => 'required|numeric',
        'month' => 'required'
    ]);

    $subscription = Subscription::create($request->all());

    return response()->json($subscription);
}

public function update(Request $request, $id)
{
    $subscription = Subscription::findOrFail($id);

    $subscription->update([
        'price' => $request->price,
        'month' => $request->month
    ]);

    return response()->json(['message' => 'Updated']);
}

public function destroy($id)
{
    Subscription::findOrFail($id)->delete();
    return response()->json(['message' => 'Deleted']);
}
}