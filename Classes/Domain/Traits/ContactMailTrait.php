<?php

namespace KayStrobach\Contact\Domain\Traits;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @deprecated
 */
trait ContactMailTrait
{
    /**
     * @var string
     * @Flow\Validate(type="EmailAddress")
     * @Flow\Validate(type="NotEmpty", validationGroups={"KontaktEmail"})
     */
    protected $email = '';

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
