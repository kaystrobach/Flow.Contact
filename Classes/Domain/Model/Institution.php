<?php

namespace KayStrobach\Contact\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use KayStrobach\Contact\Domain\Embeddable\AddressEmbeddable;
use KayStrobach\Contact\Domain\Traits\ContactTrait;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Flow\Entity
 */
class Institution
{
    // @todo fix this use ContactTrait;

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="NotEmpty")
     */
    protected $name;

    /**
     * @ORM\Embedded(columnPrefix="address_")
     * @var AddressEmbeddable
     */
    protected $address;

    /**
     * @var ArrayCollection<\KayStrobach\Contact\Domain\Model\User>
     * @ORM\OneToMany(mappedBy="institution")
     */
    protected $users;

    /**
     * @ORM\OneToMany(mappedBy="institution")
     * @var Collection<UserInstitutionRelationship>
     */
    protected $personRelationships;

    public function __construct()
    {
        $this->address = new AddressEmbeddable();
        $this->personRelationships = new ArrayCollection();
    }

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

    public function getAddress(): AddressEmbeddable
    {
        return $this->address;
    }

    public function setAddress(AddressEmbeddable $address): void
    {
        $this->address = $address;
    }

    public function getAddressWithName(): string
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
