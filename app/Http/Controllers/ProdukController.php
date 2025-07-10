<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $supabaseUrl = config('services.supabase.url');
            $supabaseKey = config('services.supabase.service_key');

            $headers = [
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
            ];

            $response = Http::withHeaders($headers)
                ->get($supabaseUrl . '/rest/v1/produk?select=*');

            if ($response->successful()) {
                $produk = $response->json();
                return datatables()->of($produk)
                    ->addIndexColumn()
                    ->addColumn('nama', function ($row) {
                        return $row['nama'];
                    })
                    ->editColumn('harga', function ($row) {
                        return 'Rp ' . number_format($row['harga'], 0, ',', '.');
                    })
                    ->editColumn('link', function ($row) {
                        return '<a href="' . $row['link'] . '" target="_blank" class="btn btn-info btn-sm">Lihat Link</a>';
                    })
                    ->addColumn('action', function ($row) {
                        return '
                            <a href="'.url('produk/'.$row['id'].'/edit').'" class="btn btn-sm btn-warning">Edit</a>
                            <button onclick="deleteProduk(\''.$row['id'].'\')" class="btn btn-sm btn-primary">Delete</button>
                        ';
                    })

                    ->rawColumns(['link', 'action'])
                    ->make(true);
            } else {
                return datatables()->of([])->make(true);
            }
        }

        return view('produk.index');
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'product_name' => 'required',
        'price' => 'required|numeric',
        'link' => 'required|url',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $supabaseUrl = config('services.supabase.url');
    $supabaseKey = config('services.supabase.service_key');

    // Upload gambar ke Supabase Storage
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $uuid = Str::uuid();
        $filename = "media/produk/{$uuid}." . $file->getClientOriginalExtension();

        $upload = Http::withHeaders([
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type' => $file->getMimeType(),
        ])->withBody(
            file_get_contents($file),
            $file->getMimeType()
        )->put("{$supabaseUrl}/storage/v1/object/{$filename}");

        if (!$upload->successful()) {
            return redirect()->back()->with('error', 'Gagal mengunggah gambar ke storage.');
        }

        $imageUrl = "{$supabaseUrl}/storage/v1/object/public/{$filename}";

        $response = Http::withHeaders([
            'apikey'        => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type'  => 'application/json',
        ])->post("{$supabaseUrl}/rest/v1/produk", [
            'id'        => $uuid,
            'nama'      => $request->product_name,
            'harga'     => $request->price,
            'link'      => $request->link,
            'image_url' => $imageUrl,
        ]);

        if ($response->successful()) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan produk.');
    }

    return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
}


    public function edit($id)
    {
        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.service_key');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ];

        $response = Http::withHeaders($headers)
            ->get($supabaseUrl . "/rest/v1/produk?id=eq.{$id}&select=*");

        $produk = $response->json()[0] ?? null;

        if (!$produk) {
            return redirect()->route('produk.index')->with('error', 'Produk tidak ditemukan');
        }

        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required',
            'price' => 'required|numeric',
            'link' => 'required|url',
            'image_edit' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.service_key');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type' => 'application/json',
        ];

        $data = [
            'nama' => $request->product_name,
            'harga' => $request->price,
            'link' => $request->link,
        ];

        if ($request->hasFile('image_edit')) {
            // Ambil data lama
            $produkData = Http::withHeaders($headers)
                ->get("{$supabaseUrl}/rest/v1/produk?id=eq.{$id}&select=image_url")
                ->json();

            $oldImageUrl = $produkData[0]['image_url'] ?? null;

            // Upload gambar baru
            $file = $request->file('image_edit');
            $newFilename = "media/produk/" . Str::uuid() . '.' . $file->getClientOriginalExtension();

            $upload = Http::withHeaders([
                'Authorization' => 'Bearer ' . $supabaseKey,
                'Content-Type' => $file->getMimeType(),
            ])->withBody(
                file_get_contents($file),
                $file->getMimeType()
            )->put("{$supabaseUrl}/storage/v1/object/{$newFilename}");

            if (!$upload->successful()) {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar baru.');
            }

            // Hapus gambar lama
            if ($oldImageUrl) {
                $oldPath = ltrim(str_replace("{$supabaseUrl}/storage/v1/object/public/", '', $oldImageUrl), '/');
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . $supabaseKey,
                    'apikey' => $supabaseKey,
                ])->delete("{$supabaseUrl}/storage/v1/object/{$oldPath}");
            }

            $data['image_url'] = "{$supabaseUrl}/storage/v1/object/public/{$newFilename}";
        }

        $response = Http::withHeaders($headers)
            ->patch("{$supabaseUrl}/rest/v1/produk?id=eq.{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui produk.');
    }


    public function destroy($id)
{
    $supabaseUrl = config('services.supabase.url');
    $supabaseKey = config('services.supabase.service_key');

    $headers = [
        'apikey' => $supabaseKey,
        'Authorization' => 'Bearer ' . $supabaseKey,
        'Content-Type' => 'application/json'
    ];

    // Ambil data produk termasuk image_url
    $produkData = Http::withHeaders($headers)
        ->get("{$supabaseUrl}/rest/v1/produk?id=eq.{$id}&select=image_url")
        ->json();

    $imageUrl = $produkData[0]['image_url'] ?? null;

    // Jika ada gambar, hapus dari storage
    if ($imageUrl) {
        // Ambil path relatif dari URL
        $parsed = parse_url($imageUrl, PHP_URL_PATH);

        // Contoh path: /storage/v1/object/public/media/produk/abc.jpg
        // Maka path storage-nya adalah: media/produk/abc.jpg
        $storagePath = ltrim(str_replace('/storage/v1/object/public/', '', $parsed), '/');

        // Kirim DELETE ke Supabase Storage
        $delete = Http::withHeaders([
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
        ])->delete("{$supabaseUrl}/storage/v1/object/{$storagePath}");

        // Optional: bisa log jika delete gagal
        if (!$delete->successful()) {
            \Log::warning('Gagal hapus file dari Supabase Storage: ' . $storagePath);
        }
    }

    // Hapus data produk dari tabel
    $response = Http::withHeaders($headers)
        ->delete("{$supabaseUrl}/rest/v1/produk?id=eq.{$id}");

    if ($response->successful()) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 500);
}

}
