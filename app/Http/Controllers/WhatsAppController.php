<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function index()
    {
        return view('settings.whatsapp');
    }

    public function status()
    {
        $status = $this->whatsapp->getStatus();

        return response()->json($status);
    }

    public function requestPairingCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        $result = $this->whatsapp->getPairingCode($request->phone_number);

        if ($result['success']) {
            return response()->json(['success' => true, 'code' => $result['code']]);
        }

        return response()->json(['success' => false, 'error' => $result['error'] ?? 'Failed to get pairing code'], 400);
    }

    public function logout()
    {
        $this->whatsapp->logout();

        return redirect()->back()->with('success', 'WhatsApp disconnected successfully. A new QR code will be generated.');
    }
}
