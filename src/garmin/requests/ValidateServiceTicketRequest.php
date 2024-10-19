<?php

namespace jjtbsomhorst\garmin\sso\requests;

use GuzzleHttp\Psr7\Request;
use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;

class ValidateServiceTicketRequest extends Request
{
    public function __construct(string $serviceTicket)
    {
        parent::__construct(
            Method::GET->value,
            new Uri(
                GarminConstants::CONNECT_MODERN_URL,
                [
                    'ticket' => $serviceTicket,
                ],
            ),
            [
                'DNT' => 1,
                'Referer' => GarminConstants::SSO_EMBED_URL,
                'TE' => 'Trailers',
            ]
        );
    }
}
