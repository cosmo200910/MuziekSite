<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Song;
use App\Entity\User;

class HomeController extends Controller
{


    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(
            Song::class);

        $songs = $repository->findBy(
            ['uploaderId' => $user->getId()]
        );

        return $this->render('home/home.html.twig', array(
            'songCount' => count($songs),
        ));
    }
}
