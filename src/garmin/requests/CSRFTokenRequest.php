<?php

namespace jjtbsomhorst\garmin\sso\requests;

use GuzzleHttp\Psr7\Request;
use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;
use Psr\Http\Message\UriInterface;

class CSRFTokenRequest extends Request
{
    public function __construct()
    {
        parent::__construct(
            Method::GET->value,
            new Uri(
                GarminConstants::SSO_BASE_URL.'/signin',
                GarminConstants::CSRF_TOKEN_PARAMS,
            ),
            [
                'Referer' => (new SetCookieRequest)->getUri()->__toString(),
            ]
        );
    }

    public static function url(): UriInterface
    {
        return (new self)->getUri();
    }
}
