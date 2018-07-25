<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Ohcasey\Ohcasey;
use App\Ohcasey\Cart;
use App\Models\Shop\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set(config('app.timezone'));
        Order::observe(OrderObserver::class);

        Relation::morphMap([
            'Product' => 'App\Models\Shop\Product',
            'Offer' => 'App\Models\Shop\Offer',
        ]);

        \View::composer('*', function($view){
            $menu = \App\Models\MenuLink::whereNull('parent_id')->orderBy('sort')->with(['children' => function($q) {
                $q->orderBy('sort')->with(['children' => function($q) {
                    $q->orderBy('sort');
                }]);
            }])->get();
            $view->with('menu', $menu);
        });

        \View::composer('site.layouts.app', function ($view) {
            $settings = Setting::pluck('value', 'key');
            $view->with('settings', $settings);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Ohcasey::class, function () {
            return new Ohcasey();
        });

        $this->app->singleton(Cart::class, function () {
            return new Cart();
        });

        $this->app->bind('payment', function () {
            return new \Idma\Robokassa\Payment(
                config('payment.login'),
                config('payment.password1'),
                config('payment.password2'),
                config('payment.debug')
            );
        });

        \View::composer('site.layouts.app', function (\Illuminate\View\View $view) {
            $menu = \Cache::remember(\App\Models\MenuLink::GLOBAL_CACHE_KEY, 360, function () {
                return \App\Models\MenuLink::whereNull('parent_id')
                    ->orderBy('sort')
                    ->with(['children' => function($q) {
                        $q->orderBy('sort')
                            ->with(['children' => function($q) {
                                $q->orderBy('sort');
                            }]);
                    }])
                    ->get();

            });
            $view->with('menu', $menu);
        });

        /** @var Cart $cartHelper */
        $cartHelper = app(Cart::class);
        \View::share('cartHelper', $cartHelper);
    }
}
