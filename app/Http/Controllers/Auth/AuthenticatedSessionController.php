<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        //Adding notification in the user profile page to display a message when ever the login on his/her profile bu uisng the toastr function
        $notification = array(
        'message' => 'Login Succesfully',
        'alert-type' => 'success'
        );

        $url = '';
        if($request->user()->role === 'admin'){
            $url = 'admin/dashboard';
        } elseif ($request->user()->role === 'vendor'){
            $url = 'vendor/dashboard';
        } elseif ($request->user()->role === 'user'){
            $url = '/dashboard';
        }

        return redirect()->intended($url)->with($notification);
        //return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
