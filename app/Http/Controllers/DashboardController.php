<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        // Debug the session state when dashboard is accessed
        Log::info('Dashboard accessed - Token in session: ' . (session()->has('token') ? 'YES' : 'NO'));
        
        // Check if user is authenticated
        if (!$this->authService->check()) {
            Log::warning('Dashboard accessed without auth, redirecting to login');
            return redirect()->route('login');
        }
        
        $user = $this->authService->getAuthenticatedUser();
        Log::info('User authenticated, displaying dashboard');
        
        return view('frontend.dashboard', compact('user'));
    }
}