<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ImageTarget;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Video;

class AdminController extends Controller
{
    private function getMediaBucketSize($supabaseUrl, $headers): float
    {
        $bucket = 'media';
        $totalSize = 0;

        // Panggil fungsi rekursif untuk menjelajahi semua folder
        $totalSize += $this->recursiveSize($supabaseUrl, $headers, $bucket, '');

        return round($totalSize / 1024 / 1024, 2); // Convert ke MB
    }

    private function recursiveSize($supabaseUrl, $headers, $bucket, $prefix): int
    {
        $total = 0;

        $res = Http::withHeaders($headers)->post("$supabaseUrl/storage/v1/object/list/$bucket", [
            'prefix' => $prefix,
            'limit' => 1000,
        ]);

        if (!$res->successful()) return 0;

        $items = $res->json();

        foreach ($items as $item) {
            if (str_ends_with($item['name'], '/')) {
                // Ini folder, telusuri isinya lagi
                $total += $this->recursiveSize($supabaseUrl, $headers, $bucket, $item['name']);
            } else {
                // Ini file, ambil size-nya
                if (isset($item['metadata']['size'])) {
                    $total += $item['metadata']['size'];
                }
            }
        }

        return $total;
        dd($items);

    }
    


    public function index(){
        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.service_key');

        $headers = [
            'apiKey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];

        $userRes = Http::withHeaders($headers)
            ->get($supabaseUrl . '/auth/v1/admin/users');

        $userCount = 0;
        if ($userRes->successful()) {
            $userData = $userRes->json();
            $userCount = $userData['total'] ?? count($userData['users'] ?? []);
        }

        // Ambil jumlah artwork dari tabel public Supabase
        $artworkRes = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/artworks?select=id');

        $artworkCount = 0;
        if ($artworkRes->successful()) {
            $artworkCount = count($artworkRes->json());
        }
        $mediaStorageMb = $this->getMediaBucketSize($supabaseUrl, $headers);
        dd($mediaStorageMb);
        return view('home', [
            'userCount' => $userCount,
            'artworkCount' => $artworkCount,
            'mediaStorageMb' => $mediaStorageMb,
        ]);

    }

}
