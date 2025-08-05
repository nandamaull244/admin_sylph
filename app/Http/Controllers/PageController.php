<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PageController extends Controller
{
    public function index()
    {
        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.service_key');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];

        // Fetch the banner data
        $bannerRes = Http::withHeaders($headers)
            ->get($supabaseUrl . '/rest/v1/produk?select=*');

        $bannerData = $bannerRes->json();

        return view('landing_page.index', compact('bannerData'));
    }
    public function privacyPolicy()
    {
        return view('landing_page.privacy');
    }
    public function blank()
    {
        return view('engvelop.404');
    }


}
