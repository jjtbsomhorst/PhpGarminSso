<?php

namespace jjtbsomhorst\garmin\sso\responses;

use jjtbsomhorst\garmin\sso\http\AccessToken;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class AccessTokenResponse extends Response
{
    public function __construct(ResponseInterface $response)
    {
        parent::__construct(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }

    public function token(): AccessToken
    {
        return AccessToken::fromJson(json_decode($this->getBody(), true));
    }
}