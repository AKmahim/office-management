<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event; // Ensure you import the Event model if needed
use App\Models\Content;
use Illuminate\Support\Facades\Auth; // Import Auth if you need to check user authentication
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Carbon\Carbon; // Import Carbon for date handling if needed

class DashboardController extends Controller
{
    //
    // ===================== last 10 days content statictics bar chart  ==================
    public function lastTenDaysContent()
    {
        $today = date('Y-m-d');
        $lastTenDays = [];
        $lastTenDaysContent = [];

        for ($i = 0; $i < 10; $i++) {
            $date = date('Y-m-d', strtotime($today . ' -' . $i . ' days'));
            $lastTenDays[] = $date;
            $ContentCount = Content::whereDate('created_at', $date)->count();
            $lastTenDaysContent[] = $ContentCount;
        }

        return response()->json([
            'last_ten_days' => array_reverse($lastTenDays),
            'last_ten_days_content' => array_reverse($lastTenDaysContent),
        ], 200);
    }

    // ===================== storage statistics  ==================
    public function storageStatistics()
    {
        //get the server storage
        $totalStorage = disk_total_space('/');
        $freeStorage = disk_free_space('/');
        $usedStorage = $totalStorage - $freeStorage;
        $usedStoragePercentage = ($usedStorage / $totalStorage) * 100;
        $freeStoragePercentage = 100 - $usedStoragePercentage;
        return response()->json([
            'total_storage' => $this->formatBytes($totalStorage),
            'free_storage' => $this->formatBytes($freeStorage),
            'used_storage' => $this->formatBytes($usedStorage),
            'used_storage_percentage' => round($usedStoragePercentage, 2),
            'free_storage_percentage' => round($freeStoragePercentage, 2),
        ], 200);
    }

    // Helper function to format bytes as KB, MB, GB, etc.
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
