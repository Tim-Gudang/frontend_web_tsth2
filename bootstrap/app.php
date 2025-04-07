    <?php

    use App\Http\Middleware\Authenticated;
use App\Http\Middleware\AuthSession;
use Illuminate\Foundation\Application;
    use Illuminate\Foundation\Configuration\Exceptions;
    use Illuminate\Foundation\Configuration\Middleware;

    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__.'/../routes/web.php',
            commands: __DIR__.'/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {
            $middleware->alias([
                'auth.token' => \App\Http\Middleware\Authenticated::class,
                'auth.session' => \App\Http\Middleware\AuthSession::class,
            ]);
        })
        ->withExceptions(function (Exceptions $exceptions) {
            //
        })->create();
