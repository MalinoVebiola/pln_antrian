<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$akun)
    {
        if (in_array(auth()->user()->id_role, $akun)) {
            return $next($request);
        }

        return redirect('login');
    }
    }

