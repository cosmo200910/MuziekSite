<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Song;
use App\Entity\User;
use App\Entity\LikesRelationship;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Form\SongType;
use Symfony\Component\HttpFoundation\JsonResponse;

class LikesController extends Controller
{
    /**
     * @Route("/likes/add", name="song_like_add")
     */
    public function addLike(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $like = new LikesRelationship();
            $like->setUserID($this->getUser()->getId());
            $like->setSongID($request->request->get('songID'));
            $em->persist($like);
            $em->flush();

            $arrData = ['status' => 'success'];
            return new JsonResponse($arrData);
        }
        $arrData = ['status' => 'error'];
        return new JsonResponse($arrData);
    }

    /**
     * @Route("/likes", name="show_all_likes")
     */
    public function listLikes(Request $request)
    {

            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(
                LikesRelationship::class);

            $likes = $repository->findBy(
                array('userID' => $this->getUser()->getId())
            );

            $songIdArray = Array();

            foreach ($likes as $like) {
                array_push($songIdArray, $like->getSongID());
            }

            $query = $em->createQuery('SELECT u FROM App\Entity\Song u WHERE u.id IN (:id)');
            $query->setParameter('id', $songIdArray);
            $songs = $query->getResult();

            return $this->render('likes/likedsongs.html.twig', array(
                'songs' => $songs,
            ));

    }


    /**
     * @Route("/likes/delete", name="delete_like")
     */
    public function deleteLike(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(
                LikesRelationship::class);


            $product = $repository->findOneBy(
                array('userID' => $this->getUser()->getId(), 'songID' => $request->request->get('songID'))
            );

            $em->remove($product);
            $em->flush();

            $arrData = ['status' => 'success'];
            return new JsonResponse($arrData);
        }
        $arrData = ['status' => 'error'];
        return new JsonResponse($arrData);
    }



}

?>