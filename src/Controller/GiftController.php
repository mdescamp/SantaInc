<?php

namespace App\Controller;

use App\Repository\FactoryRepository;
use App\Repository\GiftCodeRepository;
use App\Repository\GiftRepository;
use App\Repository\ReceiverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gift', name: 'gift_')]
class GiftController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(
        Request            $request,
        GiftRepository     $giftRepository,
        GiftCodeRepository $giftCodeRepository,
        FactoryRepository  $factoryRepository,
        ReceiverRepository $receiverRepository
    ): Response
    {

        $factories = $factoryRepository->findAll();
        $receivers = $receiverRepository->findAll();
        $codes = $giftCodeRepository->findAll();
        $gifts = $giftRepository->filter($request);

        $selected = [
            'code' => $request->get('code'),
            'factory' => $request->get('factory'),
            'receiver' => $request->get('receiver'),
            'price-min' => $request->get('price-min'),
            'price-max' => $request->get('price-max'),
        ];

        return $this->render('gift/index.html.twig', compact(
            'gifts',
            'codes',
            'receivers',
            'factories',
            'selected'
        ));
    }
}
