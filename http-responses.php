<?php

$http_responses =  [
    "not_found" => function ($message = "HTTP 404 Not Found") {
        http_response_code(404);
        echo $message;
    },
    "bad_request" => function ($message = "HTTP 400 Bad Request") {
        http_response_code(400);
        echo $message;
    },
    "unauthorized" => function ($message = "HTTP 403 Unauthorized") {
        http_response_code(403);
        echo $message;
    }
];

return $http_responses;
