<?php

namespace KayStrobach\Contact\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use KayStrobach\Contact\Domain\Traits\ContactTrait;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Flow\Entity
 */
class Institution
{
    use ContactTrait;

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="NotEmpty")
     */
    protected $name;

    /**
     * @ORM\Column(nullable=true)
     * @ORM\OneToOne(cascade={"all"})
     * @var \KayStrobach\Contact\Domain\Model\Address
     */
    protected $address;

    /**
     * @var ArrayCollection<\KayStrobach\Contact\Domain\Model\User>
     * @ORM\OneToMany(mappedBy="institution")
     */
    protected $users;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getAddressWithName()
    {
        return implode(
            chr(10),
            [
                $this->getName(),
                '',
                $this->getAddress()->getStreet() . ' ' . $this->getAddress()->getHouseNumber(),
                $this->getAddress()->getZipCode() . ' ' . $this->getAddress()->getCity()
            ]
        );
    }
    
    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }
}
