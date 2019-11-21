<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin extends CheckRole
{
	
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if($this->isAdmin()) {
			return $next($request);
		}
		
		return $this->unauthorized();
	}
}
