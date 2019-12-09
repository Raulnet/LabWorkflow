<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GraphController extends AbstractController
{
    /**
     * @Route("/graph-workflow", name="graph-workflow", methods={"GET"})
     */
    public function index()
    {
        return $this->render('graph/index.html.twig');
    }
}
