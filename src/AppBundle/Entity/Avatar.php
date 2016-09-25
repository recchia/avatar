<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avatar
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvatarRepository")
 */
class Avatar
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=128, unique=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=20)
     */
    private $mimeType;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_code", type="string", length=65)
     */
    private $confirmationCode;

    /**
     * @var string
     *
     * @ORM\Column(name="delete_code", type="string", length=64, nullable=true)
     */
    private $deleteCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Avatar
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Avatar
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Avatar
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set confirmationCode
     *
     * @param string $confirmationCode
     *
     * @return Avatar
     */
    public function setConfirmationCode($confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    /**
     * Get confirmationCode
     *
     * @return string
     */
    public function getConfirmationCode()
    {
        return $this->confirmationCode;
    }

    /**
     * Set deleteCode
     *
     * @param string $deleteCode
     *
     * @return Avatar
     */
    public function setDeleteCode($deleteCode)
    {
        $this->deleteCode = $deleteCode;

        return $this;
    }

    /**
     * Get deleteCode
     *
     * @return string
     */
    public function getDeleteCode()
    {
        return $this->deleteCode;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Avatar
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return Avatar
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }
}
