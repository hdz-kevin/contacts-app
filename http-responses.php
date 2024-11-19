<?php

$http_responses =  [
    "not found" => function ($message = "HTTP 404 NOT FOUND") {
        http_response_code(404);
        echo $message;
    },
    "bad request" => function ($message = "Bad Request") {
        http_response_code(400);
        echo $message;
    },
];

return $http_responses;
