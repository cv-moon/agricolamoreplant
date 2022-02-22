<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'usuario')
            ->get();
        return $users;
    }

    public function profile()
    {
        $user = User::where('id', Auth::user()->id)
            ->first();
        return $user;
    }
}
