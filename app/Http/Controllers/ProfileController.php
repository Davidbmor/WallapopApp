<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $sales = Sale::where('user_id', $user->id)->with('category')->get();
        return view('profile', compact('sales'));
    }
}