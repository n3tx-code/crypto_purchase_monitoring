<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Entity\Mouvement;
use App\Form\CryptoType;
use App\Form\MouvementType;
use App\Repository\CryptoRepository;
use App\Repository\MouvementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MouvementController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(MouvementRepository $mouvementRepository, CryptoRepository $cryptoRepository): Response
    {
        $mouvements = $mouvementRepository->findAll();
        $cryptos = $cryptoRepository->findAll();
        return $this->render('mouvement/index.html.twig', [
            'mouvements' => $mouvements,
            'cryptos' => $cryptos,
        ]);
    }

    /**
     * @Route("/crypto/{shortcode}/mouvement/add", name="mouvement_add", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, $shortcode, CryptoRepository $cryptoRepository): Response
    {
        $mvt = new Mouvement();
        $crypto = $cryptoRepository->findOneBy(['shortcode' => $shortcode]);
        $form = $this->createForm(MouvementType::class, $mvt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid('addMouvement', $request->get('_token'))) {
                $mvt->setDateMade(new \DateTime("now"));
                $mvt->setCrypto($crypto);
                $em->persist($mvt);
                $em->flush();
                $this->addFlash('success', "Mouvement has been added.");
                return $this->redirectToRoute("crypto_detail", array('shortcode' => $shortcode));
            }
        }

        return $this->render('mouvement/add.html.twig',
            [
                'form' => $form->createView(),
                'mouvement' => $mvt,
                'crypto' => $crypto
            ]);
    }
}
