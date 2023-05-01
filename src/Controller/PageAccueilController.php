<?php

namespace App\Controller;

use App\Entity\LimitedOffer;
use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageAccueilController extends AbstractController
{
    #[Route('/', name: 'app_page_accueil')]
    public function index(Request $request, OfferRepository $offerRepository, PaginatorInterface $paginator): Response
    {
        $offers = $offerRepository->orderedOffer();
        foreach ($offers as $key => $offer) {
            if (is_a($offer, LimitedOffer::class)) {
                if ($offer->getOrderNumber() === 0
                    || $offer->getDisplayStartDate() >= \DateTime::createFromFormat('Y-m-d', date('Y-m-d'))
                    || $offer->getDisplayEndDate() <= \DateTime::createFromFormat('Y-m-d', date('Y-m-d'))) {
                    unset($offers[$key]);
                }
            }
        }
        $offersPaginate = $paginator->paginate(
            $offers,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('page_accueil/index.html.twig', [
            'controller_name' => 'PageAccueilController',
            'offers' => $offersPaginate,
        ]);
    }
}
