<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeController extends AbstractController
{
    #[Route('/api/me', name: 'app_me')]
    public function index(): Response
    {
        // Retrieve the currently authenticated user from Symfony Security.
        $user = $this->getUser();
        // Expose only the minimal authentication information required by the client.
        return $this->json([
            'authenticated' => $user !== null,
            'email' => $user?->getUserIdentifier(),
            'roles' => $user?->getRoles(),
        ], Response::HTTP_OK);
    }
}
