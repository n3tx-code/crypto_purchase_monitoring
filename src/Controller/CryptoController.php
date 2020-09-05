<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Form\CryptoType;
use App\Repository\CryptoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoController extends AbstractController
{
    /**
     * @Route("/crypto/add", name="crypto_add", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
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

    /**
     * @Route("/crypto/{shortcode}", name="crypto_detail", methods={"GET"})
     * @param CryptoRepository $cryptoRepository
     * @param $shortcode
     * @return Response
     */
    public function detail(CryptoRepository $cryptoRepository, $shortcode): Response
    {
        $crypto = $cryptoRepository->findOneBy(['shortcode' => $shortcode]);
        if (is_null($crypto)) {
            return $this->render('error/notFound.html.twig', ['entity' => 'Crypto']);
        }
        return $this->render('crypto/detail.html.twig', ['crypto' => $crypto]);
    }

    /**
     * @Route("/crypto/{shortcode}/current-total-update", name="crypto_current_total_update", methods={"POST"})
     * @param CryptoRepository $cryptoRepository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $shortcode
     * @return Response
     */
    public function currentTotal(CryptoRepository $cryptoRepository, Request $request, EntityManagerInterface $em, $shortcode): Response
    {
        $crypto = $cryptoRepository->findOneBy(['shortcode' => $shortcode]);
        if (is_null($crypto)) {
            return $this->render('error/notFound.html.twig', ['entity' => 'Crypto']);
        }
        $currentTotal = (float)$request->get('currentTotal');

        if (!is_float($currentTotal) || $currentTotal <= 0) {
            $this->addFlash('error', "Wrong value");
            return $this->redirectToRoute("crypto_detail", array('shortcode' => $shortcode));
        }

        $crypto->setCurrentTotal($currentTotal);

        if ($this->isCsrfTokenValid('currentTotalUpdate' . $shortcode, $request->get('_token'))) {
            $em->persist($crypto);
            $em->flush();
            $this->addFlash('success', "Current total for " . $crypto->getTitle() . " has been updated.");
        }

        return $this->redirectToRoute("crypto_detail", array('shortcode' => $shortcode));
    }
}
