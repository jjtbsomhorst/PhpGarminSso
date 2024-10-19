<?php

namespace jjtbsomhorst\garmin\sso\responses;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class LoginResponse extends Response
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

    /**
     * @throws Exception
     */
    public function getServiceTicket(): string
    {
        if ($this->getStatusCode() !== 200) {
            throw new Exception($this->getReasonPhrase(), $this->getStatusCode());
        }

        if (preg_match('/ticket=([A-Z0-9-]+)/', $this->getBody(), $matches)) {
            return $matches[1];
        }

        throw new Exception('Invalid response. No ticket detected');
    }
}
