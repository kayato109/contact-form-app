<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $creator = new CreateNewUser;
        $user = $creator->create($request->all());

        Auth::login($user);

        return redirect('/admin');
    }
}
