<?php

namespace App\Http\Controllers;

use App\Services\BarangService;
use App\Services\JenisBarangService;
use App\Services\SatuanService;
use App\Services\GudangService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $barangService;
    protected $jenisBarangService;
    protected $satuanService;
    protected $gudangService;

    public function __construct(
        BarangService $barangService,
        JenisBarangService $jenisBarangService,
        SatuanService $satuanService,
        GudangService $gudangService
    ) {
        $this->barangService = $barangService;
        $this->jenisBarangService = $jenisBarangService;
        $this->satuanService = $satuanService;
        $this->gudangService = $gudangService;
    }

    public function index()
    {
        try {
            $counts = [
                'barangs' => $this->barangService->getCount(),
                'jenisbarangs' => $this->jenisBarangService->getCount(),
                'satuans' => $this->satuanService->getCount(),
                'gudangs' => $this->gudangService->getCount(),
                // 'users' => $this->userService->getCount(), // Uncomment if needed
            ];

            return view('frontend.dashboard', $counts);
        } catch (\Exception $e) {
            return view('frontend.dashboard')->withErrors([
                'message' => 'Failed to load dashboard data: ' . $e->getMessage()
            ]);
        }
    }
}