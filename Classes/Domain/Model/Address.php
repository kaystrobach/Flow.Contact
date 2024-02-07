<?php

namespace KayStrobach\Contact\Domain\Model;

/**
 * @deprecated
 */
class Address
{
    protected User $user;

    public function __construct(
        User $user
    )
    {
        $this->user = $user;
    }

    public static function fromUser(User $user): self
    {
        return new Address(
            $user
        );
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->user->getAddress()->getStreet();
    }

    /**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->user->getAddress()->getHouseNumber();
    }


    /**
     * @return string
     */
    public function getAddressAddon()
    {
        return $this->user->getAddress()->getAddressAddon();
    }

    /**
     * @return string
     */
    public function getRoomNumber()
    {
        return $this->user->getAddress()->getRoomNumber();
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->user->getAddress()->getZipCode();
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->user->getAddress()->getCity();
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->user->getAddress()->getCountry();
    }
}
