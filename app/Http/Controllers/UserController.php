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
                ->addColumn('mind_files', function ($user) {
                    $mindFiles = Http::withHeaders([
                        'apikey' => config('services.supabase.service_key'),
                        'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    ])->get(config('services.supabase.url') . "/rest/v1/mind_files?user_id=eq." . $user['id'] . "&select=*")->json();

                    if (empty($mindFiles)) {
                        return '<span class="text-muted">Tidak ada file .mind</span>';
                    }

                    $html = '<ul style="padding-left: 15px;">';
                    foreach ($mindFiles as $mind) {
                        $html .= '<li><a href="' . $mind['mind_url'] . '" target="_blank">' . $mind['name'] . '</a></li>';
                    }
                    $html .= '</ul>';

                    return $html;
                })

                ->addColumn('action', function ($user) {
                    $edit = '<a href="' . route('users.edit', $user['id']) . '" class="btn btn-warning btn-sm">Edit</a> ';
                    $delete = '<form action="' . route('users.destroy', $user['id']) . '" method="POST" style="display:inline-block;">'
                        . csrf_field() . method_field('DELETE') .
                        '<button class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus user ini?\')">Hapus</button></form>';
                    return $edit . $delete;
                })
                ->rawColumns(['image_url','mind_files','action'])
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
            'name'     => 'required',
            'email'    => 'required|email',
            'password' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'minds.*'  => 'required', // pastikan file .mind diizinkan
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
        $imageTargets = [];
        // 5. Upload image_targets jika ada
        if ($request->hasFile('images') ) {
            foreach ($request->file('images') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = now()->format('YmdHis') . '.' . $file->getClientOriginalName();
                list($width, $height) = getimagesize($file);
                $normalizedWidth = 1.0; // Normalisasi lebar ke 1.0
                $normalizedHeight = round($height / $width, 2); // Normalisasi tinggi berdasarkan lebar
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
                $imageId = (string) Str::uuid();
         
                Http::withHeaders([
                    'apikey'        => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => 'application/json',
                ])->post(config('services.supabase.url') . '/rest/v1/image_targets', [
                    'id'        => $imageId,
                    'user_id'   => $user_id,
                    'name'      => $filename,
                    'image_url' => $imageUrl,
                    'plane_width' => $normalizedWidth,
                    'plane_height' => $normalizedHeight,
                ]);

                // Simpan ke array untuk digunakan nanti
                $imageTargets[$originalName] = [
                    'id'        => $imageId,
                    'user_id'   => $user_id,
                    'name'      => $filename,
                    'image_url' => $imageUrl,
                    'plane_width' => $normalizedWidth,
                    'plane_height' => $normalizedHeight,
                ];
            }
        }
        // ✅ Upload file .mind (bisa banyak)
        if ($request->hasFile('minds')) {
            $mindFiles = $request->file('minds');
            if(count($mindFiles) !== count($imageTargets)) {
                return back()->withErrors(['error' => 'Jumlah file .mind harus sama dengan jumlah image.']);
            }
            foreach ($mindFiles as $mind) {
                $mindNameOnly = pathinfo($mind->getClientOriginalName(), PATHINFO_FILENAME);
                $MindFileName = now()->format('YmdHis') . '_' . pathinfo($mind->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $mind->getClientOriginalExtension();
                $path = "minds/{$user_id}/{$MindFileName}";

                // Upload ke Supabase Storage
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => $mind->getMimeType(),
                ])->withBody(
                    file_get_contents($mind),
                    $mind->getMimeType()
                )->put(config('services.supabase.url') . "/storage/v1/object/media/{$path}");

                // Simpan metadata file .mind
                $mindUrl = config('services.supabase.url') . "/storage/v1/object/public/media/{$path}";

                //temukan image target yang sesuai dengan nama file mind
                $linkedImageTarget = $imageTargets[$mindNameOnly] ?? null;
                $imageTargetId = $linkedImageTarget['id'] ?? null;

                // Simpan metadata file .mind ke Supabase DB
                $mindResponse = Http::withHeaders([
                    'apikey'        => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => 'application/json',
                ])->post(config('services.supabase.url') . '/rest/v1/mind_files', [
                    'id'        => (string) Str::uuid(),
                    'user_id'   => $user_id,
                    'name'      => $MindFileName,
                    'mind_url'  => $mindUrl,
                    'image_target_id' => $imageTargetId, // link ke image target jika ada
                ]);
                

                if (!$mindResponse->successful()) {
                    Log::error('Upload mind metadata gagal: '.$mindResponse->body());
                }
                
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User, image target, dan file mind berhasil ditambahkan.');
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

        $mindFiles = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/mind_files?user_id=eq.{$id}&select=*")->json();

        if (!$user) abort(404);

        
        return view('users.edit', compact('user', 'images','mindFiles'));
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
        $imageTargetsEdit = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = now()->format('YmdHis') . '.' . $file->getClientOriginalName();
                list($width, $height) = getimagesize($file);
                $normalizedWidth = 1.0; // Normalisasi lebar ke 1.0
                $normalizedHeight = round($height / $width, 2); // Normalisasi tinggi berdasarkan lebar
                $path = "image_targets/{$id}/{$filename}";

                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => $file->getMimeType(),
                ])->withBody(
                    file_get_contents($file),
                    $file->getMimeType()
                )->put(config('services.supabase.url') . "/storage/v1/object/media/{$path}");

                // Simpan metadata image
                $imageUrl = config('services.supabase.url') . "/storage/v1/object/public/media/{$path}";
                $imageId = (string) Str::uuid();

                Http::withHeaders([
                    'apikey'        => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => 'application/json',
                ])->post(config('services.supabase.url') . '/rest/v1/image_targets', [
                    'id'        => $imageId,
                    'user_id'   => $id,
                    'name'      => $filename,
                    'image_url' => $imageUrl,
                    'plane_width' => $normalizedWidth,
                    'plane_height' => $normalizedHeight,
                ]);

                $imageTargetsEdit[$originalName] = [
                    'id'        => $imageId,
                    'user_id'   => $id,
                    'name'      => $filename,
                    'image_url' => $imageUrl,
                    'plane_width' => $normalizedWidth,
                    'plane_height' => $normalizedHeight,
                ];
            }
        }

        // ✅ Upload file .mind (bisa banyak)
        if ($request->hasFile('minds')) {
            foreach ($request->file('minds') as $mind) {
                $mindNameOnly = pathinfo($mind->getClientOriginalName(), PATHINFO_FILENAME);
                $MindFileName = now()->format('YmdHis') . '_' . pathinfo($mind->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $mind->getClientOriginalExtension();
                $path = "minds/{$id}/{$MindFileName}";

                // Upload ke Supabase Storage
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => $mind->getMimeType(),
                ])->withBody(
                    file_get_contents($mind),
                    $mind->getMimeType()
                )->put(config('services.supabase.url') . "/storage/v1/object/media/{$path}");

                // Simpan metadata file .mind
                $mindUrl = config('services.supabase.url') . "/storage/v1/object/public/media/{$path}";

                //temukan image target yang sesuai dengan nama file mind
                $linkedImageTarget = $imageTargetsEdit[$mindNameOnly] ?? null;
                $imageTargetId = $linkedImageTarget['id'] ?? null;

                // Simpan metadata file .mind ke Supabase DB
                $mindEditResponse = Http::withHeaders([
                    'apikey'        => config('services.supabase.service_key'),
                    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
                    'Content-Type'  => 'application/json',
                ])->post(config('services.supabase.url') . '/rest/v1/mind_files', [
                    'id'        => (string) Str::uuid(),
                    'user_id'   => $id,
                    'name'      => $MindFileName,
                    'mind_url'  => $mindUrl,
                    'image_target_id' => $imageTargetId, // link ke image target jika ada
                ]);

                if (!$mindEditResponse->successful()) {
                    Log::error('Edit mind metadata gagal: '.$mindEditResponse->body());
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }


    public function destroy($id)
    {
        //hapus semua image target milik user
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

        //hapus semua mind file milik user
         $minds = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/mind_files?user_id=eq.{$id}&select=*")->json();
        // Hapus file .mind
        foreach ($minds as $mind) {
            $mindPath = str_replace(config('services.supabase.url') . "/storage/v1/object/public/media/", '', $mind['mind_url']);
            Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/storage/v1/object/media/{$mindPath}");

            Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/rest/v1/mind_files?id=eq.{$mind['id']}");
        }

        // hapus video yang terkait dengan user
        $videos = Http::withHeaders([
            'apikey' => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/videos?user_id=eq.{$id}&select=*")->json();

        foreach ($videos as $video) {
            $videoPath = str_replace(config('services.supabase.url') . "/storage/v1/object/public/media/", '', $video['video_url']);
            Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/storage/v1/object/media/{$videoPath}");

            Http::withHeaders([
                'apikey' => config('services.supabase.service_key'),
                'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
            ])->delete(config('services.supabase.url') . "/rest/v1/videos?id=eq.{$video['id']}");
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

        // Log error detail jika gagal menghapus user atau auth
        if ($deleteUser->failed()) {
            Log::error('Gagal menghapus user dari tabel users: ' . $deleteUser->body());
        }
        if ($deleteAuth->failed()) {
            Log::error('Gagal menghapus user dari Supabase Auth: ' . $deleteAuth->body());
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

    public function deleteMindFile($id)
    {
        // 1. Ambil data file dari Supabase DB
        $mind = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->get(config('services.supabase.url') . "/rest/v1/mind_files?id=eq.{$id}&select=*")->json()[0] ?? null;

        if (!$mind) {
            return redirect()->back()->with('error', 'File .mind tidak ditemukan.');
        }

        // 2. Ambil path file dari URL public-nya
        $parsedUrl = parse_url($mind['mind_url']);
        $pathInStorage = str_replace('/storage/v1/object/public/media/', '', $parsedUrl['path']);

        // 3. Hapus file dari Storage
        $deleteFile = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->delete(config('services.supabase.url') . "/storage/v1/object/media/{$pathInStorage}");

        // 4. Hapus data dari table image_targets
        $deleteData = Http::withHeaders([
            'apikey'        => config('services.supabase.service_key'),
            'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
        ])->delete(config('services.supabase.url') . "/rest/v1/mind_files?id=eq.{$id}");

        // 5. Cek keberhasilan
        if (($deleteFile->successful() || $deleteFile->status() === 404) && $deleteData->successful()) {
            return redirect()->back()->with('success', 'File .mind berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus file .mind.')->withErrors([
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
