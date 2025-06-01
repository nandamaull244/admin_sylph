<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ARTrackingController extends Controller
{
    public function getUserArtworks($id)
    {
        $userId = $id;

        $response = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/artworks?user_id=eq.{$userId}&select=video_id,mind_id");

        $artwork = $response->json()[0] ?? null;

        if (!$artwork) {
            return response()->json(['error' => 'Artwork not found'], 404);
        }

        $video = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/videos?id=eq.{$artwork['video_id']}&select=video_url")->json();

        $mind = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/mind_files?id=eq.{$artwork['mind_id']}&select=mind_url")->json();

        return response()->json([
            'mind_url' => $mind[0]['mind_url'] ?? null,
            'video_url' => $video[0]['video_url'] ?? null,
        ]);
    }
}
