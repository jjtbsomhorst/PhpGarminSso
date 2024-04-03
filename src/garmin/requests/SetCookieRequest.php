<?php

namespace jjtbsomhorst\garmin\sso\requests;

use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;
use GuzzleHttp\Psr7\Request;

class SetCookieRequest extends Request
{
    public function __construct()
    {
        parent::__construct(
            Method::GET->value,
            new Uri(
                GarminConstants::SSO_BASE_URL . "/embed",
                GarminConstants::GET_COOKIE_PARAMS
            )
        );
    }
}
