<?php

namespace App\Controller;

use App\Entity\Mouvement;
use App\Form\MouvementType;
use App\Repository\CryptoRepository;
use App\Repository\MouvementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class MouvementController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(MouvementRepository $mouvementRepository, CryptoRepository $cryptoRepository): Response
    {
        $mouvements = $mouvementRepository->findAll();
        $cryptos = $cryptoRepository->findAll();

        $totalInvest = 0;
        foreach ($mouvements as $mouvement) {
            if ($mouvement->isInvestisement()) {
                $totalInvest += floatval($mouvement->getAmount());
            }
        }

        $currentTotal = 0;
        foreach ($cryptos as $crypto) {
            $currentTotal += floatval($crypto->getCurrentTotal());

        }

        $evolution = [];
        $evolution['benefit'] = $currentTotal - $totalInvest;
        $pourcent = 0;
        if ($currentTotal != 0) {
            $pourcent = round((($currentTotal - $totalInvest) / $currentTotal) * 100, 2);
        }
        $evolution['pourcent'] = $pourcent;
        if ($evolution['benefit'] > 0) {
            $evolution['color'] = "rgba(40,167,69,0." . round($pourcent) . ")";
        } else {
            $evolution['color'] = "rgba(220,53,69,0." . round($pourcent) . ")";
        }


        return $this->render('mouvement/index.html.twig', [
            'evolution' => $evolution,
            'totalInvest' => $totalInvest,
            'currentTotal' => $currentTotal,
            'mouvements' => $mouvements,
            'cryptos' => $cryptos,
        ]);
    }

    /**
     * @Route("/crypto/{shortcode}/mouvement/add", name="mouvement_add", methods={"GET", "POST"})
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        $shortcode,
        CryptoRepository $cryptoRepository
    ): Response
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
