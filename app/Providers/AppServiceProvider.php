<?php

namespace App\Providers;

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
      if(config('APP_ENV') === 'production') {
          \URL::forceScheme('https');
      }

      \Response::macro('attachment', function ($content, $fname) {
          $headers = [
              'Content-type'        => 'text/csv',
              'Content-Disposition' => 'attachment; filename="' . $fname . '"',
          ];
          return \Response::make($content, 200, $headers);
      });
    }
}
