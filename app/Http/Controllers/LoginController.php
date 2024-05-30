<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
   
class LoginController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

    public function showLoginForm(){
        if(auth()->user()) {
            $this->guard()->logout();
            session()->flush();
            return redirect('/login');
        }
        return view('welcome');
    }

     public function authenticate(Request $request)
     {
         $credentials = $request->validate([
             'email' => 'required|email:dns',
             'password' => 'required',
         ]);
         if (Auth::attempt($credentials)) {
             $user = User::where('email', $request->email)->first();
             $request->session()->regenerate();

             return redirect()->intended('dashboard/pengguna');
             
         }
  
         return back()->with([
             'loginError' => 'The provided credentials do not match our records.',
         ]);
     }
}