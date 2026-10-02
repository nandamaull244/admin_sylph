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
        $response = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/artworks?user_id=eq.{$userId}");

        $artworks = $response->json();

        if (empty($artworks) || !is_array($artworks)) {
            return response()->json([]);
        }

        $results = [];

        foreach ($artworks as $artwork) {
            $videoId = $artwork['video_id'] ?? null;
            $mindId = $artwork['mind_id'] ?? null;
            $imageId = $artwork['image_target_id'] ?? null;

            $videoUrl = null;
            if ($videoId) {
                $videoResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                ])->get(config('services.supabase.url') . "/rest/v1/videos?id=eq.{$videoId}&select=video_url")->json();

                if (is_array($videoResponse) && !empty($videoResponse)) {
                    $videoUrl = $videoResponse[0]['video_url'] ?? null;
                }
            }

            $mindUrl = null;
            if ($mindId) {
                $mindResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                ])->get(config('services.supabase.url') . "/rest/v1/mind_files?id=eq.{$mindId}&select=mind_url")->json();

                if (is_array($mindResponse) && !empty($mindResponse)) {
                    $mindUrl = $mindResponse[0]['mind_url'] ?? null;
                }
            }

            $imageUrl = null;
            $planeWidth = null;
            $planeHeight = null;
            if ($imageId) {
                $imageResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                ])->get(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$imageId}&select=image_url,plane_width,plane_height")->json();

                if (is_array($imageResponse) && !empty($imageResponse)) {
                    $imageUrl = $imageResponse[0]['image_url'] ?? null;
                    $planeWidth = $imageResponse[0]['plane_width'] ?? null;
                    $planeHeight = $imageResponse[0]['plane_height'] ?? null;
                }
            }

            $results[] = [
                'title' => $artwork['title'] ?? 'Untitled',
                'mind_url' => $mindUrl,
                'video_url' => $videoUrl,
                'image_url' => $imageUrl,
                'plane_width' => $planeWidth,
                'plane_height' => $planeHeight,
            ];
        }

        return response()->json($results);
    }
}
