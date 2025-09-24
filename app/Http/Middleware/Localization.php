<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Localization constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->header('Accept-Language');
        if (!$locale) {
            $locale = $this->app->config->get('app.locale');
        }

        if (!in_array($locale, $this->app->config->get('app.available_locales'))) {
            $locale = 'en';
        }

        if(str_contains($locale, 'ar'))
        {
            $locale = 'ar';
        }

        $this->app->setLocale($locale);
        $response = $next($request);
        $response->headers->set('Accept-Language', $locale);

        return $response;
    }
}
