<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * This simple controller is used only to render html template
     *
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('main.html.twig');
    }
}