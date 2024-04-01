<?php

namespace garmin\sso\requests;

use garmin\sso\http\GarminConstants;
use garmin\sso\http\Method;
use garmin\sso\http\Uri;
use GuzzleHttp\Psr7\Request;

class ServiceTicketRequest extends Request
{
    public function __construct(
        string $serviceTicket
    ) {
        parent::__construct(
            Method::GET->value,
            new Uri(
                GarminConstants::CONNECT_MODERN_URL,
                [
                    "Ticket" => $serviceTicket
                ]
            ),
            [
                'DNT' => 1,
                'Referer' => GarminConstants::SSO_EMBED_URL,
                'TE' => 'Trailers'
            ]
        );
    }
}
