<?php
declare(strict_types=1);

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
        $response = null;
        if ($apiService->verifyApiKey($request) === false) {
            $response = 'Invalid credentials';
        }

        if ($response === null && $apiService->verifyBody($request) === false) {
            $response = 'Body not well formatted';
        }

        if ($response === null) {
            $fileService->save($request->files->get('file'), $request->get('factory'));
        }

        return $this->json($response ?? 'ok');
    }
}
