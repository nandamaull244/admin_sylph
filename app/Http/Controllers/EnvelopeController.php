<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Envelope;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class EnvelopeController extends Controller
{
    public function envelopeSender()
    {
        return view('engvelop.sender');
    }

    public function envelopeReciper()
    {
        return view('engvelop.reciever');
    }

    public function envelopeStore(Request $request)
    {
        $request->validate([
            'pengirim' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $hashId = md5($request->pengirim . now());

        $data = Envelope::create([
            'hash_id' => $hashId,
            'pengirim' => $request->input('pengirim'),
            'penerima' => $request->input('penerima'),
            'body' => $request->input('body'),
        ]);

        if (!$data) {
            return response()->json([
                'message' => 'Failed to create envelope.',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'hash_id' => $hashId
            ]
        ], 200);
    }

    public function getEnvelopeByHashId($hashId)
    {
        $envelope = Envelope::where('hash_id', $hashId)->first();

        if (!$envelope) {
            return response()->json([
                'message' => 'Envelope not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Envelope retrieved successfully',
            'data' => $envelope,
            'success' => true,
        ], 200);
    }
}
