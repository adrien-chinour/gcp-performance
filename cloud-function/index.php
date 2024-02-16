<?php

declare(strict_types=1);

use Google\CloudFunctions\FunctionsFramework;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

FunctionsFramework::http('index', 'index');

function index(ServerRequestInterface $request): ResponseInterface
{
    if ('POST' !== $request->getMethod() || empty($body = $request->getBody()->getContents())) {
        return new Response(status: 400);
    }

    $json = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException(sprintf('Could not parse body: %s', json_last_error_msg()));
    }

    $html = (new Parsedown())->parse($json['data'] ?? '');

    return new Response(status: 200, body: json_encode(['result' => $html]));
}
