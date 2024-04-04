<?php

namespace jjtbsomhorst\garmin\sso\requests;

use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;
use GuzzleHttp\Psr7\Request;

class RetrieveActivitiesRequest extends Request
{
    public function __construct($token, int $start = 0, int $limit = 20, string $sortby = 'startLocal', string $sortOrder = 'asc')
    {
        parent::__construct(
            Method::GET->value,
            new Uri(
                GarminConstants::CONNECT_BASE_URL . '/activitylist-service/activities/search/activities',
                [
                    'limit' => $limit,
                    'start' => $start,
                    'sortBy' => $sortby,
                    'sortOrder' => $sortOrder
                ]
            ),
            [
                "NK" => "NT",
                "X-app-ver" => GarminConstants::APP_VERSION,
                "X-lang" => "nl-NL",
                "DI-Backend" => "connectapi.garmin.com",
                "DNT" => "1",
                "Sec-GPC" => "1",
                "Connection" => "keep-alive",
                "Referer" => "https://connect.garmin.com/modern/activities",
                "Pragma" => "no-cache",
                "Cache-Control" => "no-cache",
                "TE" => "trailers",
                "Authorization" => sprintf("Bearer %s", $token)
            ]
        );
    }
}