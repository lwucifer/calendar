<?php

namespace App\Http\Middleware;

use Closure;

class CheckManager extends CheckRole
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
		if($this->isManager() || $this->isAdmin()) {
			return $next($request);
		}
		
		return $this->unauthorized();
	}
}
