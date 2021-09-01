<?php

namespace App\Controller;

use App\Repository\FactoryRepository;
use App\Repository\GiftRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/factory', name: 'factory_')]
class FactoryController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/home', name: 'home')]
    public function index(FactoryRepository $factoryRepository, GiftRepository $giftRepository): Response
    {
        $factories = $factoryRepository->findAll();
        foreach ($factories as $factory) {
            $details[$factory->getId()] = [
                'priceMax' => $giftRepository->getPriceMax($factory),
                'priceMin' => $giftRepository->getPriceMin($factory),
                'priceAvg' => $giftRepository->getPriceAVG($factory),
                'country' => $giftRepository->getCountryNumber($factory),
            ];
        }

        return $this->render('factory/index.html.twig', [
            'factories' => $factories,
            'details' => $details ?? []
        ]);
    }
}
