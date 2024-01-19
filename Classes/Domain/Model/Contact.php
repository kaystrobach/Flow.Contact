<?php
namespace KayStrobach\Contact\Domain\Model;

use Neos\Party\Domain\Model\PersonName;

/**
 * @deprecated
 */
class Contact
{
    protected User $user;
    protected Address $address;

    public function __construct(
        User $user
    )
    {
        $this->user = $user;
        $this->address = Address::fromUser($user);
    }

    /**
     * @deprecated
     * @return string
     */
    public function getEmail()
    {
        return $this->user->getPrimaryElectronicAddress()->getIdentifier();
    }


    /**
     * @deprecated
     * @return PersonName
     */
    public function getName()
    {
        return $this->user->getName();
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->user->getInstitutionPosition();
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public static function fromUser(User $user): self
    {
        return new Contact(
            $user
        );
    }

    public function getPhone()
    {
        return $this->user->getPhone()->getLandline();
    }

    public function getMobile()
    {
        return $this->user->getPhone()->getMobile();
    }
}
