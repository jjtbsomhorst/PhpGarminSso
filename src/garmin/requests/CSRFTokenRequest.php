<?php

namespace garmin\sso\requests;

use garmin\sso\http\GarminConstants;
use garmin\sso\http\Method;
use garmin\sso\http\Uri;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\UriInterface;

class CSRFTokenRequest extends Request
{
    public function __construct()
    {
        parent::__construct(
            Method::GET->name,
            new Uri(
                GarminConstants::SSO_BASE_URL . '/signin',
                GarminConstants::CSRF_TOKEN_PARAMS,
            ),
            [
                "Referer" => (new SetCookieRequest())->getUri()->__toString()
            ]
        );
    }

    public static function url(): UriInterface
    {
        return (new self())->getUri();
    }
}
