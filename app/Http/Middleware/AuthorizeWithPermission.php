<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class AuthorizeWithPermission
{
    /** @var Auth */
    protected $auth;

    /**
     * AuthorizeWithPermission constructor.
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (canViewTrainings()) {
            return $next($request);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json('Unauthorised', 403);
        }

        return abort(403);
    }
}
