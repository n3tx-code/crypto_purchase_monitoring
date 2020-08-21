<?php

namespace App\Controller;

use App\Repository\CryptoRepository;
use App\Repository\MouvementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
