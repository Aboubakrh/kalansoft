<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Response
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('dashboard'));
        }

        if ($user->hasRole('professeur')) {
            return redirect()->intended(route('professeur.dashboard'));
        }

        if ($user->hasRole('parent')) {
            return redirect()->intended(route('parent.dashboard'));
        }

        if ($user->hasRole('eleve')) {
            return redirect()->intended(route('eleve.dashboard'));
        }

        return redirect()->intended('/');
    }
}
