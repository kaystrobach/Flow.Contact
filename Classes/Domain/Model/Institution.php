<?php

namespace KayStrobach\Contact\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use KayStrobach\Contact\Domain\Embeddable\AddressEmbeddable;
use KayStrobach\Contact\Domain\Traits\CollectionBugfixTrait;
use KayStrobach\Contact\Domain\Traits\ContactTrait;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\InheritanceType("JOINED")
 * @Flow\Entity
 */
class Institution
{
    use CollectionBugfixTrait;
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
     * @ORM\OneToMany(mappedBy="institution", cascade={"persist"})
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
     * @deprecated
     * @return ArrayCollection
     */
    public function getUsers()
    {
        $collectedUsers = new ArrayCollection();
        foreach ($this->personRelationships as $personRelationship) {
            $collectedUsers->add($personRelationship->getUser());
        }
        return $collectedUsers;
    }

    public function getPersonRelationships(): ArrayCollection|Collection
    {
        return $this->personRelationships;
    }

    public function setPersonRelationships(ArrayCollection|Collection $personRelationships): void
    {
        $this->personRelationships = $this->mergeCollections($this->personRelationships, $personRelationships);
    }

    public function hasPerson(User $person)
    {
        foreach ($this->personRelationships as $relationship) {
            if($relationship->getPerson() === $person) {
                return true;
            }
        }
        return false;
    }
}
