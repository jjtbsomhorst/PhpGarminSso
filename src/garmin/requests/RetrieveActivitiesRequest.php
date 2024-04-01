<?php

namespace garmin\sso\requests;

use garmin\sso\http\GarminConstants;
use garmin\sso\http\Method;
use garmin\sso\http\Uri;
use GuzzleHttp\Psr7\Request;

class RetrieveActivitiesRequest extends Request
{
    public function __construct($token, int $start = 0, int $limit = 20, string $sortby = 'startLocal', string $sortOrder = 'asc')
    {
        parent::__construct(Method::GET->value, new Uri(GarminConstants::CONNECT_BASE_URL . '/activitylist-service/activities/search/activities', ['limit' => $limit, 'start' => $start]), ["NK" => "NT", "X-app-ver" => "4.76.0.17", "X-lang" => "nl-NL", "DI-Backend" => "connectapi.garmin.com", "DNT" => "1", "Sec-GPC" => "1", "Connection" => "keep-alive", "Referer" => "https://connect.garmin.com/modern/activities", "Pragma" => "no-cache", "Cache-Control" => "no-cache", "TE" => "trailers", "Authorization" => sprintf("Bearer %s", $token)]);
    }
}