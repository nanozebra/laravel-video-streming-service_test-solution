<?php

namespace App\Services;

use App\Models\VideoSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SecurityService
{
    public function validateSession($token, Request $request)
    {
        $session = VideoSession::where('session_token', $token)->first();

        if (!$session || !$session->isValid()) {
            return false;
        }

        // Перевірка IP-адреси
        if (config('video.security.enable_ip_validation') && $session->ip_address !== $request->ip()) {
            $this->logSecurityViolation($session, 'ip_mismatch');
            return false;
        }

        // Перевірка User-Agent
        if (config('video.security.enable_user_agent_validation') && $session->user_agent !== $request->userAgent()) {
            $this->logSecurityViolation($session, 'user_agent_mismatch');
            return false;
        }

        // Перевірка Referer для запобігання хотлінку
        if (config('video.security.enable_referer_validation') && !$this->validateReferer($request)) {
            $this->logSecurityViolation($session, 'hotlinking_attempt');
            return false;
        }

        // Оновлення лічильника переглядів
        $session->increment('plays_count');

        return true;
    }

    private function validateReferer(Request $request)
    {
        $referer = $request->header('Referer');
        $allowedDomains = config('app.allowed_domains', [config('app.url')]);

        if (!$referer) {
            return true; // Дозволити прямий доступ
        }

        $refererDomain = parse_url($referer, PHP_URL_HOST);

        foreach ($allowedDomains as $domain) {
            if (stripos($refererDomain, $domain) !== false) {
                return true;
            }
        }

        return false;
    }

    private function logSecurityViolation($session, $type)
    {
        // Логування порушень безпеки
        \Log::warning('Security violation', [
            'session_id' => $session->id,
            'type' => $type,
            'ip' => request()->ip()
        ]);
    }
}