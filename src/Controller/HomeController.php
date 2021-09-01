<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/saveFile', name: 'save_file')]
    public function saveFile(Request $request, FileService $fileService): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $fileService->save(
            $request->files->get('factory-file'),
            $request->get('factory_id', $user->getFactories()[0]->getId())
        );
        $this->addFlash('success', 'success');

        return $this->redirectToRoute('home_');
    }
}
