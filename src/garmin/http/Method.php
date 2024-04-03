<?php

namespace jjtbsomhorst\garmin\sso\http;

enum Method: string
{
    case PUT = 'PUT';
    case POST = 'POST';
    case PATCH = 'PATCH';
    case OPTIONS = 'OPTIONS';
    case DELETE = 'DELETE';
    case HEAD = 'HEAD';
    case CONNECT = 'CONNECT';
    case TRACE = 'TRACE';
    case GET = 'GET';
}
