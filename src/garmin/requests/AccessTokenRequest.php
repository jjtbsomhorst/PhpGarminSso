<?php

namespace jjtbsomhorst\garmin\sso\requests;

use GuzzleHttp\Psr7\Request;
use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;

class AccessTokenRequest extends Request
{
    public function __construct()
    {
        parent::__construct(
            Method::POST->value,
            new Uri(
                GarminConstants::CONNECT_MODERN_URL.'/di-oauth/exchange',
            ),
            [
                'NK' => 'NT',
                'X-app-ver' => GarminConstants::APP_VERSION,
                'Origin' => GarminConstants::CONNECT_BASE_URL,
                'DNT' => 1,
                'Sec-GPC' => 1,
                'Referer' => GarminConstants::CONNECT_MODERN_URL,
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'same-origin',
                'TE' => 'trailers',
            ]
        );
    }
}
