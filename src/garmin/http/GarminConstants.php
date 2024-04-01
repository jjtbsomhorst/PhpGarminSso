<?php

namespace garmin\sso\http;

final class GarminConstants
{
    public final const string SSO_BASE_URL = "https://sso.garmin.com/sso";
    public final const string SSO_EMBED_URL = self::SSO_BASE_URL . "/embed";
    public final const string CONNECT_BASE_URL = "https://connect.garmin.com";
    public final const string CONNECT_MODERN_URL = self::CONNECT_BASE_URL . "/modern";
    public final const string APP_VERSION = "4.76.0.17";
    public final const string CLIENT_ID = "etc";
    public final const string ID = "gauth-widget";
    public final const string LOCALE = "en";

    public final const array GET_COOKIE_PARAMS = [
        "id" => self::ID,
        "embedWidget" => true,
        "gauthHost" => self::SSO_BASE_URL,
        "clientId" => self::CLIENT_ID,
        "locale" => self::LOCALE
    ];

    public final const array CSRF_TOKEN_PARAMS = [
        "id" => self::ID,
        "embedWidget" => 'true',
        "gauthHost" => self::SSO_EMBED_URL,
        "clientId" => self::CLIENT_ID,
        "locale" => self::LOCALE,
        "service" => self::SSO_EMBED_URL,
        "source" => self::SSO_EMBED_URL,
        "redirectAfterAccountLoginUrl" => self::SSO_EMBED_URL,
        "redirectAfterAccountCreationUrl" => self::SSO_EMBED_URL,
    ];
}
