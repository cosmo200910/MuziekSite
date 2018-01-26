<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class GeneralController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');

    }


}

?>



