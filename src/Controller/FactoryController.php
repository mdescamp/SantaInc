<?php

namespace App\Controller;

use App\Repository\FactoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/factory', name: 'factory_')]
class FactoryController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(FactoryRepository $factoryRepository): Response
    {
        $factories = $factoryRepository->findAll();

        return $this->render('factory/index.html.twig', [
            'factories' => $factories
        ]);
    }
}
