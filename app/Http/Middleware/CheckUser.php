<?php

namespace App\Http\Middleware;

use Closure;

class CheckUser extends CheckRole
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if($this->isUser() || $this->isAdmin() || $this->isManager()) {
			return $next($request);
		}
		
		return $this->unauthorized();
	}
}
