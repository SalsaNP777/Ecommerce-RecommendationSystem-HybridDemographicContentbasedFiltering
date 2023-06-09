<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function  show_profile(){
        $user = Auth::user();
        return view('show_profile', compact('user'));
    }

    public function edit_profile(User $user, Request $request){
        $request->validate([
            'name'=>'required',
            'password'=>'required|min:8|confirmed'
        ]);
        
        $user = Auth::user();

        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::back();
    }
}
?>