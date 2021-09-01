<?php

namespace App\Controller;

use App\Service\ApiService;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    #[Route('/file', name: 'file', methods: ['POST'])]
    public function index(Request $request, ApiService $apiService, FileService $fileService): Response
    {
        if ($apiService->verifyApiKey($request) === false) {
            return $this->json('Invalid credentials');
        }

        if ($apiService->verifyBody($request) === false) {
            return $this->json('Body not well formatted');
        }

        $fileService->save($request->files->get('file'), $request->get('factory'));

        return $this->json('OK');
    }
}
