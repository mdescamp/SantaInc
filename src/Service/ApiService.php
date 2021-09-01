<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiService
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function verifyApiKey(Request $request): bool
    {
        $apiKey = $request->headers->get('api_key');

        return $apiKey !== null && $this->em->getRepository(User::class)->findOneBy(['apiKey' => $apiKey]) !== null;
    }

    public function verifyBody(Request $request): bool
    {
        return $request->files->get('file') !== null && $request->get('factory');
    }
}
