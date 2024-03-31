<?php

namespace garmin\sso\http;

class Uri extends \GuzzleHttp\Psr7\Uri
{
    public function __construct(string $baseUri, array $queryParams = [])
    {
        if (!empty($queryParams)) {
            if (str_ends_with($baseUri, '/')) {
                $baseUri = substr($baseUri, 0, strlen($baseUri) - 1);
            }

            $glue = "?";

            foreach ($queryParams as $key => $value) {
                $baseUri .= $glue . $key . "=" . $value;
                $glue = "&";
            }
        }
        return parent::__construct($baseUri);
    }
}
