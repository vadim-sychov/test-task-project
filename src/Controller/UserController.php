<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This simple controller is used only to render html template
 *
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction()
    {
        return $this->render('user/register.html.twig');
    }

    /**
     * @Route("/auth", name="auth")
     */
    public function authAction()
    {
        return $this->render('user/auth.html.twig');
    }
}