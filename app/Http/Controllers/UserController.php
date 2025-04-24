<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

Carbon::setLocale('id');
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
        'name'  => 'required',
        'email' => 'required|email',
        'password' => 'required',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // 1. Daftarkan user ke Supabase Auth (Admin API)
    $authResponse = Http::withHeaders([
        'apikey'        => config('services.supabase.service_key'),
        'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        'Content-Type'  => 'application/json',
    ])->post(config('services.supabase.url') . '/auth/v1/admin/users', [
        'email'    => $request->email,
        'password' => $request->password,
        'email_confirm' => true, // agar user tidak perlu verifikasi email
    ]);

    if (! $authResponse->successful()) {
        return back()
            ->withErrors(['error' => 'Gagal mendaftarkan user ke Supabase Auth: ' . $authResponse->body()])
            ->withInput();
    }

    // 2. Ambil ID langsung dari root JSON
    $json = $authResponse->json();
    $user_id = $json['id'] ?? abort(500, 'Tidak bisa mendapat ID dari Supabase Auth');

    // 3. Tandai email sudah diverifikasi
    $verifiedAt = now()->toIso8601String();
    Http::withHeaders([
        'apikey'        => config('services.supabase.service_key'),
        'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        'Content-Type'  => 'application/json',
    ])->patch(config('services.supabase.url') . "/auth/v1/admin/users/{$user_id}", [
        'email_confirmed_at' => $verifiedAt,
    ]);

    // 4. Simpan ke tabel users (tanpa password)
    $userResponse = Http::withHeaders([
        'apikey'        => config('services.supabase.service_key'),
        'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        'Content-Type'  => 'application/json',
    ])->post(config('services.supabase.url') . '/rest/v1/users', [
        'id'    => $user_id,
        'email' => $request->email,
        'name'  => $request->name,
    ]);

    if (! $userResponse->successful()) {
        Log::error('Supabase insert users failed: '.$userResponse->status().' '.$userResponse->body());
        return back()
            ->withErrors(['error' => 'Gagal menyimpan data user: '.$userResponse->body()])
            ->withInput();
    }

    // 5. Upload image_targets jika ada
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = "image_targets/{$user_id}/{$filename}";

            // Upload ke Supabase Storage
            Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                'Content-Type'  => $file->getMimeType(),
            ])->withBody(
                file_get_contents($file),
                $file->getMimeType()
            )->put(config('services.supabase.url') . "/storage/v1/object/media/{$path}");

            // Simpan metadata image
            $imageUrl = config('services.supabase.url') . "/storage/v1/object/public/media/{$path}";
            Http::withHeaders([
                'apikey'        => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                'Content-Type'  => 'application/json',
            ])->post(config('services.supabase.url') . '/rest/v1/image_targets', [
                'id'        => (string) Str::uuid(),
                'user_id'   => $user_id,
                'name'      => $filename,
                'image_url' => $imageUrl,
            ]);
        }
    }

    return redirect()->route('users.index')
        ->with('success', 'User dan image target berhasil ditambahkan.');
}



    public function edit($id)
    {
        $response = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/users?id=eq.{$id}&select=*");

        $authResponse = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . '/auth/v1/admin/users');

        $user = $response->json()[0] ?? null;
        $authUser = $authResponse->json()[0] ?? null;

        $images = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/image_targets?user_id=eq.{$id}&select=*")->json();

        if (!$user) abort(404);

        
        return view('users.edit', compact('user', 'images'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'password' => 'nullable|min:6', // password boleh kosong, tapi jika diisi, minimal 6 karakter
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 1. Ambil email lama dari tabel users
        $oldUser = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/users?id=eq.{$id}&select=email")->json()[0] ?? null;

        if (!$oldUser) {
            return back()->withErrors(['error' => 'User tidak ditemukan.']);
        }

        // 2. Update tabel users
        $updateUser = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            'Content-Type'  => 'application/json',
        ])->patch(config('services.supabase.url') . "/rest/v1/users?id=eq.{$id}", [
            'name'  => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($updateUser->failed()) {
            return back()->withErrors(['error' => 'Gagal update data user: ' . $updateUser->body()]);
        }

        // 3. Siapkan payload Auth update
        $authPayload = [];

        if ($request->email !== $oldUser['email']) {
            $authPayload['email'] = $request->email;
        }

        if (!empty($request->password)) {
            $authPayload['password'] = $request->password;
        }

        if (!empty($authPayload)) {
            $authUpdate = Http::withHeaders([
                'apikey'        => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                'Content-Type'  => 'application/json',
            ])->put(config('services.supabase.url') . "/auth/v1/admin/users/{$id}", $authPayload);

            if ($authUpdate->failed()) {
                return back()->withErrors(['error' => 'Gagal update Auth user: ' . $authUpdate->body()]);
            }
        }

        // 4. Upload gambar baru jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = "image_targets/{$id}/{$filename}";

                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => $file->getMimeType(),
                ])->withBody(
                    file_get_contents($file),
                    $file->getMimeType()
                )->put(config('services.supabase.url') . "/storage/v1/object/media/{$path}");

                Http::withHeaders([
                    'apikey'        => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => 'application/json',
                ])->post(config('services.supabase.url') . '/rest/v1/image_targets', [
                    'id'        => (string) Str::uuid(),
                    'user_id'   => $id,
                    'name'      => $filename,
                    'image_url' => config('services.supabase.url') . "/storage/v1/object/public/media/{$path}",
                ]);
            }
        }

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
            Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/storage/v1/object/media/{$path}");
            

            Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$image['id']}");
        }
        // Hapus user dari tabel users
        $deleteUser = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->delete(config('services.supabase.url') . "/rest/v1/users?id=eq.{$id}");

        // Hapus user dari Supabase Auth
        $deleteAuth = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->delete(config('services.supabase.url') . "/auth/v1/admin/users/{$id}");

            // 5. Cek hasil
        if ($deleteUser->successful() && $deleteAuth->successful()) {
            return redirect()->route('users.index')->with('success', 'User dan semua image target berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus user.')->withErrors([
            'deleteUser' => $deleteUser->body(),
            'deleteAuth' => $deleteAuth->body(),
        ]);
    }
    
    public function deleteImage($id)
    {
        // 1. Ambil data image dari Supabase DB
        $image = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$id}&select=*")->json()[0] ?? null;

        if (!$image) {
            return redirect()->back()->with('error', 'Image tidak ditemukan.');
        }

        // 2. Ambil path file dari URL public-nya
        $parsedUrl = parse_url($image['image_url']);
        $pathInStorage = str_replace('/storage/v1/object/public/media/', '', $parsedUrl['path']);

        // 3. Hapus file di Storage
        $deleteFile = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->delete(config('services.supabase.url') . "/storage/v1/object/media/{$pathInStorage}");

        // 4. Hapus data dari table image_targets
        $deleteData = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->delete(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$id}");

        // 5. Cek keberhasilan
        if (($deleteFile->successful() || $deleteFile->status() === 404) && $deleteData->successful()) {
            return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus gambar.')->withErrors([
            'deleteFile' => $deleteFile->body(),
            'deleteData' => $deleteData->body(),
        ]);
    }

    







    // public function deleteImage($id)
    // {
    //     $image = Http::withHeaders([
    //         'apikey' => config('services.supabase.service_key'),
    //         'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
    //     ])->get(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$id}&select=*")->json()[0] ?? null;
    
    //     if (!$image) {
    //         return response()->json(['error' => 'Image tidak ditemukan.'], 404);
    //     }
    
    //     $path = str_replace(env('SUPABASE_URL') . "/storage/v1/object/public/media/", '', $image['image_url']);
    
    //     $deleteFile = Http::withHeaders([
    //         'apikey' => config('services.supabase.service_key'),
    //         'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
    //     ])->delete(config('services.supabase.url') . "/storage/v1/object/media/{$path}");
    
    //     $deleteData = Http::withHeaders([
    //         'apikey' => env('SUPABASE_SERVICE_KEY'),
    //         'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
    //     ])->delete(config('services.supabase.url') . "/rest/v1/image_targets?id=eq.{$id}");
    
    //     if ($deleteFile->successful() && $deleteData->successful()) {
    //         return response()->json(['message' => 'Gambar berhasil dihapus.']);
    //     }
    
    //     return response()->json(['error' => 'Gagal menghapus gambar.'], 500);
    // }
    
}
