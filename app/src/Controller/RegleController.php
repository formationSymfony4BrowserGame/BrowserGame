<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegleController extends AbstractController
{
    /**
     * @Route("/regle", name="regle")
     */
    public function index(): Response
    {
        return $this->render('regle/regle.html.twig', [
            'controller_name' => 'RegleController',
        ]);
    }
}
