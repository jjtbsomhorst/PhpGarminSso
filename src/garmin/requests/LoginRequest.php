<?php

namespace jjtbsomhorst\garmin\sso\requests;

use GuzzleHttp\Psr7\Request;
use jjtbsomhorst\garmin\sso\http\GarminConstants;
use jjtbsomhorst\garmin\sso\http\Method;
use jjtbsomhorst\garmin\sso\http\Uri;

class LoginRequest extends Request
{
    public function __construct(
        readonly string $username,
        readonly string $password,
        readonly string $csrfToken,
    ) {
        $params = array_merge(
            GarminConstants::CSRF_TOKEN_PARAMS,
            [
                'gauthHost' => GarminConstants::SSO_EMBED_URL,
                'username' => $this->username,
                'password' => $this->password,
                '_csrf' => $this->csrfToken,
                'embed' => 'true',
            ]
        );

        unset($params['clientId']);
        unset($params['locale']);

        $uri = new Uri(
            GarminConstants::SSO_BASE_URL.'/signin',
            $params
        );

        parent::__construct(
            Method::POST->value,
            new Uri(
                GarminConstants::SSO_BASE_URL.'/signin',
                array_merge(
                    GarminConstants::CSRF_TOKEN_PARAMS,
                    [
                        'gauthHost' => GarminConstants::SSO_EMBED_URL,
                        'username' => $this->username,
                        'password' => $this->password,
                        '_csrf' => $this->csrfToken,
                        'embed' => true,
                    ]
                )
            ),
            [
                'Referer' => CSRFTokenRequest::url()->__toString(),
            ]
        );
    }
}
