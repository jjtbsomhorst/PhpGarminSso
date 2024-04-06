<?php

namespace jjtbsomhorst\garmin\sso\support;

use Psr\Http\Message\StreamInterface;

readonly class ActivityDownload
{
    public function __construct(
        public string $fileName,
        public StreamInterface $stream
    ) {
    }
}