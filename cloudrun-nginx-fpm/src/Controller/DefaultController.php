<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', methods: ['POST', 'GET'])]
final class DefaultController extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        // FrankenPHP need a valid GET endpoint to work
        if ($request->getMethod() === 'GET') {
            return new JsonResponse();
        }

        $data = json_decode($request->getContent(), true);

        return $this->json([
            'result' => (new \Parsedown())->parse($data['data'] ?? '')
        ]);
    }
}
