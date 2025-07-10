<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\ImageTarget;
use App\Models\Video;

class AdminController extends Controller
{
    
    /**
     * Tampilkan dashboard admin dengan data user, artwork, dan penggunaan storage.
     */
    public function index()
    {
        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.service_key');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];

        // Jumlah user
        $userRes = Http::withHeaders($headers)
            ->get($supabaseUrl . '/auth/v1/admin/users');

        $userCount = 0;
        if ($userRes->successful()) {
            $userData = $userRes->json();
            $userCount = $userData['total'] ?? count($userData['users'] ?? []);
        }

        // Jumlah artwork
        $artworkRes = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/artworks?select=id');

        $artworkCount = 0;
        if ($artworkRes->successful()) {
            $artworkCount = count($artworkRes->json());
        }

        // Harga artwork
        $price = 0;
        $hargaRes = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/harga_artwork?select=price');

        if ($hargaRes->successful()) {
            $hargaData = $hargaRes->json();
            if (isset($hargaData[0]['price'])) {
                $price = $hargaData[0]['price'];
            }
        }

        // Kalkulasi pendapatan
        $totalRevenue = $price * $artworkCount;

        //ambil data image target
        $imageTargetRes = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/image_targets?select=id');

        $imageTargetCount = 0;
        if ($imageTargetRes->successful()) {
            $imageTargetCount = count($imageTargetRes->json());
        }


        // Tampilkan view dengan data yang telah diambil
        return view('home', [
            'userCount' => $userCount,
            'artworkCount' => $artworkCount,
            'totalRevenue' => $totalRevenue,
            'imageTargetCount' => $imageTargetCount,
        ]);
    }

}
