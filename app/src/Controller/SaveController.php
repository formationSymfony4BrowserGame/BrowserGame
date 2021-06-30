<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


class SaveController extends AbstractController
{
    /**
     * @Route("/save", name="save", methods={"POST"})
     */
    public function save(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
    
        $game = $this->json($data);

        /*return $this->render('save/index.html.twig', [
            'controller_name' => 'SaveController',
        ]);*/
    }
}
