<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
//        dd($user->wallet);
        return view('dashboard', ['user' => $user]);
    }
}
