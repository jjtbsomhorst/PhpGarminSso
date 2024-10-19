<?php

namespace jjtbsomhorst\garmin\sso\requests;

use GuzzleHttp\Psr7\Request;
use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;

class RetrieveGpxRequest extends Request
{
    public function __construct(string $token, string $courseId)
    {
        parent::__construct(
            Method::GET->value,
            new Uri(GarminConstants::CONNECT_BASE_URL.'/modern/proxy/course-service/course/gpx/'.$courseId),
            [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Referer' => GarminConstants::CONNECT_BASE_URL.'/modern/proxy/course-service/course/gpx/'.$courseId,
                'Upgrade-Insecure-Requests' => 1,
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'same-origin',
                'Sec-Fetch-User' => '?1',
                'Priority' => 'u=1',
                'TE' => 'trailers',
            ]
        );
    }
}
