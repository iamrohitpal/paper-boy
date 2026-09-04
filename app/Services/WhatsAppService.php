<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'http://localhost:3001/api';
    }

    protected function getTenantId()
    {
        if (Auth::check() && Auth::user()->hasRole('Super Admin') && session()->has('active_tenant_id')) {
            return session('active_tenant_id');
        }

        return Auth::user()->tenant_id ?? 1;
    }

    public function getStatus()
    {
        try {
            $response = Http::timeout(5)->withHeaders([
                'x-tenant-id' => $this->getTenantId(),
            ])->get($this->apiUrl.'/status');

            if ($response->successful()) {
                return $response->json();
            }

            return ['status' => 'disconnected'];
        } catch (\Exception $e) {
            return ['status' => 'disconnected'];
        }
    }

    public function getPairingCode($phoneNumber)
    {
        try {
            $response = Http::timeout(30)->withHeaders([
                'x-tenant-id' => $this->getTenantId(),
            ])->post($this->apiUrl.'/pair', [
                'phoneNumber' => $phoneNumber,
            ]);

            if ($response->successful() && $response->json('success')) {
                return ['success' => true, 'code' => $response->json('code')];
            }

            return ['success' => false, 'error' => $response->json('error') ?? 'Failed to get code'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function sendMessage($number, $message, $mediaUrl = null)
    {
        try {
            $data = [
                'number' => $number,
                'message' => $message,
            ];

            if ($mediaUrl) {
                $data['mediaUrl'] = $mediaUrl;
            }

            $response = Http::timeout(10)->withHeaders([
                'x-tenant-id' => $this->getTenantId(),
            ])->post($this->apiUrl.'/send', $data);

            return $response->successful() && $response->json('success');
        } catch (\Exception $e) {
            \Log::error('WhatsApp send failed: '.$e->getMessage());

            return false;
        }
    }

    public function logout()
    {
        try {
            $response = Http::timeout(10)->withHeaders([
                'x-tenant-id' => $this->getTenantId(),
            ])->post($this->apiUrl.'/logout');

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
