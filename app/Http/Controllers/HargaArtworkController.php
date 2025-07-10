<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HargaArtworkController extends Controller
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
                ->get($supabaseUrl . '/rest/v1/harga_artwork?select=id,price');

            if ($response->successful()) {
                $data = $response->json();
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->editColumn('price', function ($row) {
                        return 'Rp ' . number_format($row['price'], 0, ',', '.');
                    })      
                    ->addColumn('action', function ($row) {
                        return '<a href="'.route('harga_artwork.edit', $row['id']).'" class="btn btn-sm btn-primary">Edit</a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } else {
                return datatables()->of([])->make(true);
            }
        }

        return view('harga_artwork.index');
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
            ->get("$supabaseUrl/rest/v1/harga_artwork?id=eq.$id&select=id,price");

        if ($response->successful() && isset($response->json()[0])) {
            $data = $response->json()[0];
            return view('harga_artwork.edit', ['hargaArtwork' => $data]);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $supabaseUrl = config('services.supabase.url');
        $supabaseKey = config('services.supabase.service_key');

        $headers = [
            'apikey' => $supabaseKey,
            'Authorization' => 'Bearer ' . $supabaseKey,
            'Content-Type' => 'application/json',
            'Prefer' => 'return=minimal',
        ];

        $response = Http::withHeaders($headers)->patch(
            "$supabaseUrl/rest/v1/harga_artwork?id=eq.$id",
            [
                'price' => (int) $request->price,
            ]
        );

        if ($response->successful()) {
            return redirect()->route('harga_artwork.index')->with('success', 'Harga berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui harga.');
    }
}
