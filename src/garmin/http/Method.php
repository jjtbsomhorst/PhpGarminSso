<?php

namespace garmin\sso\http;

enum Method
{
    case PUT;
    case POST;
    case PATCH;
    case OPTIONS;
    case DELETE;
    case HEAD;
    case CONNECT;
    case TRACE;
    case GET;
}
