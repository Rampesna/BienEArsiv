<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\Response as ResponseTrait;

class SubscriptionWeb
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $customer = auth()->user()->customer()->first();

        if (!$customer) {
            auth()->logout();
            return redirect()->route('web.user.authentication.login');
        }

        if (!$customer->subscription) {
            if ($customer->created_at->diffInDays() > 15) {
                return redirect()->route('web.user.subscription.index');
            }
        } else {
            if ($customer->subscription->subscription_expiry_date < now()) {
                return redirect()->route('web.user.subscription.index');
            }
        }

        return $next($request);
    }
}
