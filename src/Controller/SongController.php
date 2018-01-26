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

class SongController extends Controller
{
    /**
     * @Route("/songs/add", name="app_song_new")
     */
    public function add(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $song->getUploadPath();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.mp3';

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('song_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $song->setUploadPath($fileName);
            $song->setUploaderId($user->getId());
            $song->setDate(date('Y-m-d H:i:s'));

            // ... persist the $product variable or any other work
            $em->persist($song);
            $em->flush();

            return $this->redirectToRoute('show_own_songs');
        }

        return $this->render('song/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/songs/all", name="show_own_songs")
     */
    public function listsongs()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Song::class);
        $songs = $repository->findBy(
            ['uploaderId' => $user->getId()]
        );

        return $this->render('song/listsongs.html.twig', array(
            'songs' => $songs,
        ));

    }

    /**
     * @Route("songs/{username}", name="show_other_songs")
     */
    public function listsongsByUsername(Request $request, AuthorizationCheckerInterface $authChecker)
    {

        $ownPage = false;

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findBy(
            ['username' => $request->get('username')]
        );


        if(true === $authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            {
                $sessionUser = $this->getUser();
                if ($user[0]->getId() == $sessionUser->getId()) {
                    $ownPage = true;
                }

            }
        }

        $songRepository = $this->getDoctrine()->getRepository(Song::class);
        $songs = $songRepository->findBy(
            ['uploaderId' => $user[0]->getId()]
        );


        $likesRepository = $this->getDoctrine()->getRepository(LikesRelationship::class);
        $likes = $likesRepository->findBy(
            ['userID' => $this->getUser()->getId()]
        );

        $songIdArray = Array();

        foreach ($likes as $like) {
            array_push($songIdArray, $like->getSongID());
        }

        return $this->render('song/listsongsusername.html.twig', array(
            'songs' => $songs,
            'username' => $user[0]->getUsername(),
            'ownPage' => $ownPage,
            'loggedInUserLikes' => $songIdArray
        ));

    }


    /**
     * @Route("/song/delete", name="delete_song")
     */
    public function deleteSong(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        if($request->request->get('id')) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(
                Song::class);


            $product = $repository->findBy(
                ['id' => $request->request->get('id')]
            );

            $em->remove($product[0]);
            $em->flush();

            $arrData = ['status' => 'success'];
            return new JsonResponse($arrData);
        }

    }



    /**
     * @Route("/song/edit", name="edit_song")
     */
    public function editSong(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($request->request->get('id') && !empty($request->request->get('name')) && !empty($request->request->get('description')) ) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(
                Song::class);
            $product = $repository->findBy(
                ['id' => $request->request->get('id')]
            );

            $sessionUser = $this->getUser();

            //if ($product[0]->uploaderId() != $sessionUser->getId()) {
            //    $arrData = ['status' => 'error'];
            //    return new JsonResponse($arrData);
            //}

            $product[0]->setName($request->request->get('name'));
            $product[0]->setDescription($request->request->get('description'));
            $em->flush();

            $arrData = ['id' => $product[0]->getId(), 'name' => $product[0]->getName(), 'description' => $product[0]->getDescription(), 'status' => 'success'];
            return new JsonResponse($arrData);
        }

        else
        {
            $arrData = ['status' => 'error'];
            return new JsonResponse($arrData);
        }


    }


}

?>