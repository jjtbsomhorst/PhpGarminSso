<?php

namespace jjtbsomhorst\garmin\sso\responses;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ServiceTicketResponse extends Response
{
    private const PATTERN = '/<title[^>]*>(.*?)<\/title>/i';

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
    public function validate(): void
    {
        $matchFound = preg_match('/<title>(.*?)<\/title>/i', $this->getBody(), $matches);
        if (!($this->getStatusCode() == 200 && $matchFound && $matches[1] == 'Garmin Connect')) {
            throw new Exception('Invalid response', $this->getStatusCode());
        }
    }
}