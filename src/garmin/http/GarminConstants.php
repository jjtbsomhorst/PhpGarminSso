<?php

namespace jjtbsomhorst\garmin\sso\http;

final class GarminConstants
{
    public final const SSO_BASE_URL = 'https://sso.garmin.com/sso';

    public final const SSO_EMBED_URL = self::SSO_BASE_URL.'/embed';

    public final const CONNECT_BASE_URL = 'https://connect.garmin.com';

    public final const CONNECT_MODERN_URL = self::CONNECT_BASE_URL.'/modern';

    public final const APP_VERSION = '4.76.0.17';

    public final const CLIENT_ID = 'etc';

    public final const ID = 'gauth-widget';

    public final const LOCALE = 'en';

    public final const GET_COOKIE_PARAMS = [
        'id' => self::ID,
        'embedWidget' => true,
        'gauthHost' => self::SSO_BASE_URL,
        'clientId' => self::CLIENT_ID,
        'locale' => self::LOCALE,
    ];

    public final const CSRF_TOKEN_PARAMS = [
        'id' => self::ID,
        'embedWidget' => 'true',
        'gauthHost' => self::SSO_EMBED_URL,
        'clientId' => self::CLIENT_ID,
        'locale' => self::LOCALE,
        'service' => self::SSO_EMBED_URL,
        'source' => self::SSO_EMBED_URL,
        'redirectAfterAccountLoginUrl' => self::SSO_EMBED_URL,
        'redirectAfterAccountCreationUrl' => self::SSO_EMBED_URL,
    ];
}
