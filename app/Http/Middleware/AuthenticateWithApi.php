<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthenticateWithApi
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Log middleware execution
        Log::info('Auth middleware checking: ' . $request->path());
        
        if (!$this->authService->check()) {
            Log::warning('Auth check failed in middleware');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'response_code' => '401',
                    'status' => 'error'
                ], 401);
            }
            
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}