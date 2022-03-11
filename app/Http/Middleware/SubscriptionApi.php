<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\Response as ResponseTrait;

class SubscriptionApi
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
        $customer = $request->user()->customer;

        if (!$customer) {
            return $this->error('Customer not found.', 404);
        }

        if (!$customer->subscription) {
            if ($customer->created_at->diffInDays() > 15) {
                return $this->error('You need to subscribe to use this feature.', 403);
            }
        } else {
            if ($customer->subscription->subscription_expiry_date < now()) {
                return $this->error('Your subscription is not active.', 403);
            }
        }

        return $next($request);
    }
}
