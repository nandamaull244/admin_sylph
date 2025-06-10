<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ARTrackingController extends Controller
{
    public function getUserArtworks($id)
    {
        $userId = $id;

        // Ambil semua artworks milik user
        $artworks = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/artworks?user_id=eq.{$userId}")
          ->json();

        if (empty($artworks)) {
            return response()->json(['error' => 'No artworks found'], 404);
        }

        $results = [];

        foreach ($artworks as $artwork) {
            $videoId = $artwork['video_id'] ?? null;
            $mindId = $artwork['mind_id'] ?? null;
            $imageId = $artwork['image_target_id'] ?? null;

            // Ambil video URL
            $videoResponse = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->get(config('services.supabase.url') . "/rest/v1/videos?id=eq.{$videoId}&select=video_url")->json();

            // Ambil mind URL
            $mindResponse = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->get(config('services.supabase.url') . "/rest/v1/mind_files?id=eq.{$mindId}&select=mind_url")->json();

            // Ambil image URL
            $imageResponse = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->get(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$imageId}&select=image_url")->json();

            //ambil width dan height dari image
            $widthResponse = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->get(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$imageId}&select=plane_width")->json();

            $heightResponse = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->get(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$imageId}&select=plane_height")->json();

            $results[] = [
                'title' => $artwork['title'] ?? 'Untitled',
                'mind_url' => $mindResponse[0]['mind_url'] ?? null,
                'video_url' => $videoResponse[0]['video_url'] ?? null,
                'image_url' => $imageResponse[0]['image_url'] ?? null,
                'plane_width' => $widthResponse[0]['plane_width'] ?? null,
                'plane_height' => $heightResponse[0]['plane_height'] ?? null,
            ];
        }

        return response()->json($results);
    }
}
