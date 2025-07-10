<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class ArtworkController extends Controller
{
        public function index(Request $request)
        {
            if ($request->ajax()) {
                $artworks = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                ])->get(config('services.supabase.url') . '/rest/v1/artworks?select=*')->json();

                return DataTables::of($artworks)
                    ->addIndexColumn()
                    ->addColumn('user.email', function ($artwork) {
                        $user = Http::withHeaders([
                            'apikey' => config('services.supabase.service_key'),
                            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                        ])->get(config('services.supabase.url') . '/rest/v1/users?id=eq.' . $artwork['user_id'] . '&select=email')->json();

                        return $user[0]['email'] ?? '-';
                    })
                    ->addColumn('image_url', function ($artwork) {
                        if (!$artwork['image_target_id']) return '-';

                        $image = Http::withHeaders([
                            'apikey' => config('services.supabase.service_key'),
                            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                        ])->get(config('services.supabase.url') . '/rest/v1/image_targets?id=eq.' . $artwork['image_target_id'] . '&select=*')->json();

                        if (empty($image)) return '-';

                        return '<a href="' . $image[0]['image_url'] . '" target="_blank">'
                            . '<img src="' . $image[0]['image_url'] . '" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">'
                            . '</a>';
                    })
                    ->addColumn('video_url', function ($artwork) {
                        if (!$artwork['video_id']) return '-';

                        $video = Http::withHeaders([
                            'apikey' => config('services.supabase.service_key'),
                            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                        ])->get(config('services.supabase.url') . '/rest/v1/videos?id=eq.' . $artwork['video_id'] . '&select=*')->json();

                        if (empty($video)) return '-';

                        return '<a href="' . $video[0]['video_url'] . '" target="_blank">Lihat Video</a>';
                    })
                    ->addColumn('mind_files', function ($artwork) {
                        if (!$artwork['mind_id']) return '-';

                        $mind = Http::withHeaders([
                            'apikey' => config('services.supabase.service_key'),
                            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                        ])->get(config('services.supabase.url') . '/rest/v1/mind_files?id=eq.' . $artwork['mind_id'] . '&select=*')->json();

                        if (empty($mind)) return '-';

                        return '<a href="' . $mind[0]['mind_url'] . '" target="_blank">' . $mind[0]['name'] . '</a>';
                    })
                    ->addColumn('action', function ($artwork) {
                        $delete = '<form action="' . route('artworks.destroy', $artwork['id']) . '" method="POST" style="display:inline-block;">'
                                . csrf_field() . method_field('DELETE') .
                                '<button class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus data ini?\')">Hapus</button></form>';
                        return $delete;
                    })
                    ->rawColumns(['image_url', 'video_url', 'mind_files', 'action'])
                    ->make(true);
            }

            return view('artwork.index'); // pastikan ada view ini
        }
       public function destroy($id)
        {
            // Ambil detail artwork terlebih dahulu
            $artwork = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->get(config('services.supabase.url') . "/rest/v1/artworks?id=eq.$id&select=*")->json();

            if (empty($artwork)) {
                return response()->json(['error' => 'Data artwork tidak ditemukan'], 404);
            }

            $artwork = $artwork[0]; // Ambil satu artwork

            // Simpan sementara detail video jika ada
            $video = null;
            if (!empty($artwork['video_id'])) {
                $videoResponse = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                ])->get(config('services.supabase.url') . "/rest/v1/videos?id=eq." . $artwork['video_id'] . "&select=*")->json();

                if (!empty($videoResponse)) {
                    $video = $videoResponse[0];
                }
            }

            // Hapus artwork dari tabel artworks
            $deleteArtwork = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/rest/v1/artworks?id=eq.$id");

            // Setelah artwork dihapus, lanjut hapus video dan file jika ada
            if ($video) {
                // Ekstrak path file dari URL Supabase
                $parsedUrl = parse_url($video['video_url']);
                $path = $parsedUrl['path'] ?? '';
                $filePath = str_replace('/storage/v1/object/public/media/', '', $path);

                // Hapus file dari Supabase Storage
                $deleteStorage = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                ])->delete(config('services.supabase.url') . "/storage/v1/object/media/{$filePath}");
                if ($deleteStorage->failed()) {
                    Log::error('Failed to delete video file from storage: ' . $deleteStorage->body());
                }

                // Hapus entri video dari tabel videos
                $deleteVideo = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                ])->delete(config('services.supabase.url') . "/rest/v1/videos?id=eq." . $video['id']);
            }
            return redirect()->route('artwork.index')->with('success', 'Artwork dan video terkait berhasil dihapus.');
        }



}
