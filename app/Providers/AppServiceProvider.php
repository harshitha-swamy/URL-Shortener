<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ShortUrl;
use App\Policies\ShortUrlPolicy;



class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        ShortUrl::class => ShortUrlPolicy::class,
        Invitation::class => InvitationPolicy::class,
    ];
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
        //
    }
}
