<?php

namespace App\Controller;

use App\Entity\Receiver;
use App\Form\ReceiverType;
use App\Repository\ReceiverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{id}/show', name: 'show')]
    public function show(Receiver $receiver): Response
    {
        return $this->render('receiver/show.html.twig', [
            'receiver' => $receiver
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Receiver $receiver, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReceiverType::class, $receiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('receiver_show', ['id' => $receiver->getId()]);
        }

        return $this->render('receiver/edit.html.twig', [
            'receiver' => $receiver,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $receiver = new Receiver();
        $form = $this->createForm(ReceiverType::class, $receiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receiver->setUuid(uniqid('', true));
            $em->persist($receiver);
            $em->flush();

            return $this->redirectToRoute('receiver_show', ['id' => $receiver->getId()]);
        }

        return $this->render('receiver/new.html.twig', [
            'receiver' => $receiver,
            'form' => $form->createView(),
        ]);
    }
}
