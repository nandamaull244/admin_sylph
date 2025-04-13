<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\ImageTarget;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $response = Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->get(config('services.supabase.url') . '/rest/v1/users?select=*');
            
            $users = $response->json();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    return $user['name'] ?? '-';
                })
                ->addColumn('email', function ($user) {
                    return $user['email'] ?? '-';
                })
                ->addColumn('image_url', function ($user) {
                    $images = Http::withHeaders([
                        'apikey' => config('services.supabase.service_key'),
                        'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    ])->get(config('services.supabase.url') . "/rest/v1/image_targets?user_id=eq." . $user['id'] . "&select=*")->json();

                    $html = '<div style="display:flex; flex-wrap: wrap; gap: 5px;">';
                    foreach ($images as $img) {
                        $html .= '<a href="' . $img['image_url'] . '" target="_blank">'
                               . '<img src="' . $img['image_url'] . '" alt="' . $img['name'] . '" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">'
                               . '</a>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('action', function ($user) {
                    $edit = '<a href="' . route('users.edit', $user['id']) . '" class="btn btn-warning btn-sm">Edit</a> ';
                    $delete = '<form action="' . route('users.destroy', $user['id']) . '" method="POST" style="display:inline-block;">'
                        . csrf_field() . method_field('DELETE') .
                        '<button class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus user ini?\')">Hapus</button></form>';
                    return $edit . $delete;
                })
                ->rawColumns(['image_url', 'action'])
                ->make(true);
        }

        return view('users.index');
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // 1. Register user ke Supabase Auth
        $authResponse = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->post(config('services.supabase.url') . '/auth/v1/admin/users', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $user_id = $authResponse->json('user.id') ?? (string) Str::uuid();

        // 2. Simpan data ke table users (tanpa password dan image_url)
        Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            'Content-Type' => 'application/json'
        ])->post(config('services.supabase.url') . '/rest/v1/users', [
            'id' => $user_id,
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password)
        ]);

        // 3. Upload semua images ke Supabase Storage dan simpan ke image_targets
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = "image_targets/{$user_id}/{$filename}";

                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type' => $file->getMimeType()
                ])->withBody(
                    file_get_contents($file),
                    $file->getMimeType()
                )->put(config('services.supabase.url') . "/storage/v1/object/media/{$path}");

                // Public URL
                $image_url = config('services.supabase.url') . "/storage/v1/object/public/media/{$path}";

                // Simpan ke table image_targets
                $response = Http::withHeaders([
                    'apikey' => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type' => 'application/json'
                ])->post(config('services.supabase.url') . '/rest/v1/image_targets', [
                    'id' => (string) Str::uuid(),
                    'user_id' => $user_id,
                    'name' => $filename,
                    'image_url' => $image_url
                ]);

                logger('Image target insert response:');
                logger($response->status());
                logger($response->body());
                }
        }

        if ($authResponse->failed()) {
            return redirect()->back()->withErrors(['error' => 'Gagal mendaftarkan user ke Supabase Auth.'])->withInput();
        }

        return redirect()->route('users.index')->with('success', 'User dan image target berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $response = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/users?id=eq.{$id}&select=*");

        $user = $response->json()[0] ?? null;

        if (!$user) abort(404);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            'Content-Type' => 'application/json'
        ])->patch(config('services.supabase.url') . "/rest/v1/users?id=eq.{$id}", [
            'name' => $request->name,
            'email' => $request->email
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $images = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/image_targets?user_id=eq.{$id}&select=*")->json();

        foreach ($images as $image) {
            $path = str_replace(config('services.supabase.url') . "/storage/v1/object/public/media/", '', $image['image_url']);
            Storage::disk('supabase')->delete($path);

            Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$image['id']}");
        }

        Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->delete(config('services.supabase.url') . "/rest/v1/users?id=eq.{$id}");

        return redirect()->route('users.index')->with('success', 'User dan semua image target dihapus.');
    }
}
