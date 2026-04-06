<?php

namespace App\Providers;

use App\Models\Blacklist;
use App\Models\Category;
use App\Models\Keyword;
use App\Models\SystemNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
                
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('*', function ($view) {
            $view->with([
                'pendingCount' => Keyword::whereIn('status', ['pending'])->count(),
                'totalCategories' => Category::count(),
                'blacklistedCount' => Blacklist::count(),
                'unreadCount' => SystemNotification::where('is_read', false)->count(),
                'recentNotifications' => SystemNotification::latest()->take(5)->get()
            ]);
        });
    }
}
