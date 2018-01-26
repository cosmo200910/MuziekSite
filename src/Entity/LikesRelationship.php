<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikesRelationshipRepository")
 */
class LikesRelationship
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="integer")
     */
    private $userID;

    /**
     * @ORM\Column(type="integer")
     */
    private $songID;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getSongID()
    {
        return $this->songID;
    }

    /**
     * @param mixed $songID
     */
    public function setSongID($songID)
    {
        $this->songID = $songID;
    }
}
