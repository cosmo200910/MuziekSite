<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 */
class Song
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
    private $uploaderId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Please upload a sound file.")
     * @Assert\File(mimeTypes={"audio/mpeg"})
     */
    private $uploadPath;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $date;

    public function getId()
    {
        return $this->id;
    }


    public function getUploaderId()
    {
        return $this->uploaderId;
    }


    public function setUploaderId($uploaderId)
    {
        $this->uploaderId = $uploaderId;
    }

    public function getDate()
    {
        return $this->date;
    }


    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getName()
    {
        return $this->name;
    }


    public function setName($name)
    {
        $this->name = $name;
    }


    public function getUploadPath()
    {
        return $this->uploadPath;
    }


    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function setDescription($description)
    {
        $this->description = $description;
    }


}
