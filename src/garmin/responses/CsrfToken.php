<?php

namespace jjtbsomhorst\garmin\sso\responses;

use Exception;
use Psr\Http\Message\ResponseInterface;

readonly class CsrfToken
{
    public static function fromResponse(ResponseInterface $response): string
    {
        if ($response->getStatusCode() !== 200) {
            throw new Exception($response->getReasonPhrase(), $response->getStatusCode());
        }

        $body = $response->getBody();
        $csrf_token_regex = '/name="_csrf" value="([^"]+)"/';
        preg_match($csrf_token_regex, $body, $matches);

        if (! isset($matches[1])) {
            throw new Exception('Invalid response');
        }

        return $matches[1];
    }
}
