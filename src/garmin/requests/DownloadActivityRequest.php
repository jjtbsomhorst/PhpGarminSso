<?php

namespace jjtbsomhorst\garmin\sso\requests;

use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;
use GuzzleHttp\Psr7\Request;

class DownloadActivityRequest extends Request
{
    public function __construct(string $token, string $activityId)
    {
        parent::__construct(
            Method::GET->value,
            new Uri(GarminConstants::CONNECT_BASE_URL . '/download-service/files/activity/' . $activityId),
            [
                "NK" => "NT",
                "X-app-ver" => "4.76.0.17",
                "X-lang" => "nl-NL",
                "DI-Backend" => "connectapi.garmin.com",
                "DNT" => "1",
                "Sec-GPC" => "1",
                "Connection" => "keep-alive",
                "Referer" => GarminConstants::CONNECT_MODERN_URL . "/activity/" . $activityId,
                "Pragma" => "no-cache",
                "Cache-Control" => "no-cache",
                "TE" => "trailers",
                "Authorization" => sprintf("Bearer %s", $token)
            ]
        );
    }
}