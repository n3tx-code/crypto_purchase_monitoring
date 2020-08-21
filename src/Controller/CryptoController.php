<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Form\CryptoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoController extends AbstractController
{
    /**
     * @Route("/crypto/add", name="crypto_add", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $crypto = new Crypto();
        $form = $this->createForm(CryptoType::class, $crypto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid('addCrypto', $request->get('_token'))) {
                $em->persist($crypto);
                $em->flush();
                $this->addFlash('success', $crypto->getTitle() . " has been added.");
                return $this->redirectToRoute("home");
            }
        }

        return $this->render('crypto/add.html.twig',
            ['form' => $form->createView(), 'crypto' => $crypto]);
    }
}
