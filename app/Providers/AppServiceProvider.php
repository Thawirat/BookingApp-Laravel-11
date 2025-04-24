<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('th');
        // ถ้าอยากใช้ภาษาไทยเต็มๆ เพิ่ม locale system ด้วย
        setlocale(LC_TIME, 'th_TH.utf8');
    }
}
