<?php

namespace Modules\Adwords\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Modules\Adwords\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::group([
            'middleware' => ['web', 'auth', 'admin', 'role:adwords'],
            'namespace'  => 'Modules\Adwords\Http\Controllers\Admin',
        ], function ($router)
        {
            require __DIR__ . '/../Routes/admin.php';
        });
    }
}
