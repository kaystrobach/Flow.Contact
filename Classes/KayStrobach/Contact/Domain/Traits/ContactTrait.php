<?php
namespace KayStrobach\Contact\Domain\Traits;

/*
 * This file is part of the KayStrobach.Contact package.
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait ContactTrait
{
    /**
     * @var string
     * @Flow\Validate(type="EmailAddress")
     * @Flow\Validate(type="NotEmpty", validationGroups={"KontaktEmail"})
     */
    protected $email = '';

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="StringLength", options={ "minimum"=5, "maximum"=255 })
     * @Gedmo\Versioned
     */
    protected $phone = '';

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Gedmo\Versioned
     */
    protected $mobile = '';

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

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }
}