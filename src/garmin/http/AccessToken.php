<?php

namespace jjtbsomhorst\garmin\sso\http;

use Carbon\Carbon;

class AccessToken
{
    private Carbon $expiresAt;

    private Carbon $refresExpiresat;

    private function __construct(
        public string $scope,
        public string $jti,
        public string $accessToken,
        public string $token_type,
        public string $refreshToken,
        public int $expiresIn,
        public int $refreshExpiresIn
    ) {
        $this->expiresAt = Carbon::now();
        $this->refresExpiresat = Carbon::now();
    }

    public static function fromJson(array $json): AccessToken
    {
        return new self($json['scope'], $json['jti'], $json['access_token'], $json['token_type'], $json['refresh_token'], $json['expires_in'], $json['refresh_token_expires_in']);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expiresAt);
    }

    public function canRefresh(): bool
    {
        return ! Carbon::now()->isAfter($this->refresExpiresat);
    }
}
