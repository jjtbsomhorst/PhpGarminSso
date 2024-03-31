<?php

namespace garmin\sso\requests;

use garmin\sso\http\GarminConstants;
use garmin\sso\http\Method;
use garmin\sso\http\Uri;
use GuzzleHttp\Psr7\Request;

class SetCookieRequest extends Request
{
    public function __construct()
    {
        parent::__construct(
            Method::GET->name,
            new Uri(
                GarminConstants::SSO_BASE_URL . "/embed",
                GarminConstants::GET_COOKIE_PARAMS
            )
        );
    }
}
