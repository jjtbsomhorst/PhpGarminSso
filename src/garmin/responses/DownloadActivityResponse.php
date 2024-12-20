<?php

namespace jjtbsomhorst\garmin\sso\responses;

use Exception;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class DownloadActivityResponse extends Response
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
    public function getFileName(): string
    {
        return $this->getFileNameFromHeader();
    }

    /**
     * @throws Exception
     */
    public function download(string $path): void
    {
        if (! is_dir($path)) {
            throw new InvalidArgumentException('Given path must be a directory');
        }

        $fileName = $this->getFileNameFromHeader();

        $resource = fopen($path.'/'.$fileName, 'w');
        while (! $this->getBody()->eof()) {
            fwrite($resource, $this->getBody()->read(1024));
        }

        fclose($resource);
    }

    /**
     * @throws Exception
     */
    private function getFileNameFromHeader(): string
    {
        $header = $this->getHeader('content-disposition');
        if (
            empty($header)
        ) {
            throw new Exception('Unable to determine filename from response');
        }

        $headerValue = str_replace(['"', 'filename=', 'attachment;'], ['', '', ''], $header[0]);

        return trim($headerValue);
    }
}
