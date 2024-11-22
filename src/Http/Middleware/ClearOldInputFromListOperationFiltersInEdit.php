<?php

namespace Winex01\BackpackFilter\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearOldInputFromListOperationFiltersInEdit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if True then its probably came from the list operation and have active filters, so we reset or forget the old session old values
        // so it wont affect the value in edit.
        if (
            request()->isMethod('get') &&
            parse_url(url()->previous(), component: PHP_URL_QUERY) &&
            !session()->getOldInput('_save_action') &&
            session()->getOldInput('search')
        ) {
            session()->forget('_old_input');
        }

        return $next($request);
    }
}
