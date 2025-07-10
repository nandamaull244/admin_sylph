<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class TrafficChartController extends Controller
{
    public function getUserImageCountChart()
    {
        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.service_key');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];

        // Ambil user + relasi image_target
        $imageData = Http::withHeaders($headers)->get("$supabaseUrl/rest/v1/image_targets?select=user_id,users(name)&order=user_id")?->json();

        $counts = [];

        foreach ($imageData as $item) {
            $username = $item['users']['name'] ?? 'Unknown';
            $counts[$username] = ($counts[$username] ?? 0) + 1;
        }

        return response()->json([
            'users' => array_keys($counts),
            'counts' => array_values($counts),
        ]);
    }

}
