<?php

namespace App\Controller;

use App\Repository\ReceiverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/receiver', name: 'receiver_')]
class ReceiverController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(ReceiverRepository $receiverRepository): Response
    {
        $receivers = $receiverRepository->findAll();

        return $this->render('receiver/index.html.twig', [
            'receivers' => $receivers,
        ]);
    }
}
