<?php

namespace garmin\sso\http;

class AccessToken
{
    private function __construct(
        public readonly string $scope,
        public readonly string $jti,
        public readonly string $accessToken,
        public readonly string $token_type,
        public readonly string $refreshToken,
        public readonly int $expiresIn,
        public readonly int $refreshExpiresIn
    ) {
    }
    public static function fromJson(array $json): AccessToken
    {
        return new self(
            $json["scope"],
            $json["jti"],
            $json["access_token"],
            $json["token_type"],
            $json["refresh_token"],
            $json["expires_in"],
            $json["refresh_token_expires_in"]
        );
    }
}