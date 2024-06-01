<?php

namespace jjtbsomhorst\garmin\sso\requests;

use GuzzleHttp\Psr7\Request;
use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;

class RetrieveCoursesRequest extends Request
{
    public function __construct(string $token)
    {
        parent::__construct(
            Method::GET->value,
            new Uri(
                GarminConstants::CONNECT_BASE_URL . '/web-gateway/course/owner/',
            ),
            [
                "NK" => "NT",
                "X-app-ver" => GarminConstants::APP_VERSION,
                "X-lang" => "nl-NL",
                "DI-Backend" => "connectapi.garmin.com",
                "DNT" => "1",
                "Sec-GPC" => "1",
                "Connection" => "keep-alive",
                "Referer" => "https://connect.garmin.com/modern/courses",
                "Pragma" => "no-cache",
                "Cache-Control" => "no-cache",
                "TE" => "trailers",
                "Authorization" => sprintf("Bearer %s", $token),
                "Accept" => "application/json",
            ]
        );
    }
}