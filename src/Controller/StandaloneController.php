<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StandaloneController extends AbstractController
{
    #[Route('/{reactRouting}', name: 'index', requirements: ["reactRouting"=>".+"], defaults: ["reactRouting"=>null])]
    public function react()
    {
        return $this->render('index.html.twig');
    }
}
