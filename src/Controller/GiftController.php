<?php

namespace App\Controller;

use App\Repository\GiftCodeRepository;
use App\Repository\GiftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gift', name: 'gift_')]
class GiftController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Request $request, GiftRepository $giftRepository, GiftCodeRepository $giftCodeRepository): Response
    {
        $giftCodes = $giftCodeRepository->findAll();

        $gifts = $giftRepository->findAll();

        return $this->render('gift/index.html.twig', [
            'gifts' => $gifts,
            'giftCodes' => $giftCodes
        ]);
    }
}
