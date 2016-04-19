<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Subscriber
 * @package AppBundle\Entity
 */
class Subscriber
{

    /**
     * @Assert\Type("string")
     * @var string
     */
    private $fname;

    /**
     * @Assert\Type("string")
     * @var string
     */
    private $lname;


    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("email")
     */
    private $email;

    /**
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * @param string $fname
     */
    public function setFname($fname)
    {
        $this->fname = $fname;
    }

    /**
     * @return string
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * @param string $lname
     */
    public function setLname($lname)
    {
        $this->lname = $lname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}
