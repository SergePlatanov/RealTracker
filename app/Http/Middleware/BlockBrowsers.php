<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

//use Pataar\BrowserDetect\Facades\Browser;
//use hisorange\BrowserDetect\Facades\Browser;
use Browser; // Импорт прямого класса парсера

class BlockBrowsers
{
    public function handle(Request $request, Closure $next): Response
    {
        // Считываем настройки (рекомендуется через config(), как настроили на прошлом шаге)
        $minVersions = config('services.blocked_browsers', []);
        
        $browserName = Browser::browserFamily();
        $browserVersion = Browser::browserVersion();

        if (array_key_exists($browserName, $minVersions)) {
            $minRequired = $minVersions[$browserName];

            if (version_compare($browserVersion, $minRequired, '<')) {
                
                // Данные, которые мы прокидываем во Vue-компонент
                $pageData = [
                    'browser' => $browserName,
                    'version' => $browserVersion,
                    'reference' => env('BROWSER_REFERENCE','Please update your browser.')
                ];

                // Метод toResponse($request) корректно преобразует Inertia-компонент в HTTP-ответ,
                // будь то JSON для SPA-перехода или полноценный HTML для первой загрузки страницы.
                return Inertia::render('Errors/BrowserBlocked', $pageData)
                    ->toResponse($request)
                    ->setStatusCode(403);            }
        }

        return $next($request);
    }
}
