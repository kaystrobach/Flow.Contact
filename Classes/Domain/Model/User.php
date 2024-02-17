<?php

namespace KayStrobach\Contact\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use KayStrobach\Contact\Domain\Embeddable\AddressEmbeddable;
use KayStrobach\Contact\Domain\Embeddable\PhoneEmbeddable;
use KayStrobach\Contact\Domain\Embeddable\SalutationEmbeddable;
use KayStrobach\Contact\Domain\Traits\CollectionBugfixTrait;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Party\Domain\Model\Person;

/**
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class User extends Person
{
    use CollectionBugfixTrait;
    /**
     * @ORM\Embedded(columnPrefix="address_")
     * @var AddressEmbeddable
     */
    protected $address;

    /**
     * @ORM\Embedded(columnPrefix="salutation_")
     * @var SalutationEmbeddable
     */
    protected $salutation;

    /**
     * @ORM\Embedded(columnPrefix="phone_private_")
     * @var PhoneEmbeddable
     */
    protected $phonePrivate;

    /**
     * @ORM\OneToMany(mappedBy="user", cascade={"persist"})
     * @var Collection<UserInstitutionRelationship>
     */
    protected $instutionRelationships;

    /**
     * @ORM\ManyToOne()
     * @var ?UserInstitutionRelationship
     */
    protected ?UserInstitutionRelationship $primaryInstitutionRelationship;

    public function __construct()
    {
        parent::__construct();
        $this->address = new AddressEmbeddable();
        $this->salutation = new SalutationEmbeddable();
        $this->phonePrivate = new PhoneEmbeddable();
        $this->instutionRelationships = new ArrayCollection();
    }

    /**
     * @return ?\KayStrobach\Contact\Domain\Model\Institution
     */
    public function getInstitution()
    {
        return $this->getPrimaryInstitutionRelationship()->getInstitution();
    }

    /**
     * Sets the accounts of this party
     *
     * @param \Doctrine\Common\Collections\Collection $accounts All assigned Neos\Flow\Security\Account objects
     * @return void
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
    }

    /**
     * @deprecated
     * @return Contact
     */
    public function getContact(): Contact
    {
        return Contact::fromUser(
            $this
        );
    }

    public function getAddress(): AddressEmbeddable
    {
        return $this->address;
    }

    public function setAddress(AddressEmbeddable $address): void
    {
        $this->address = $address;
    }

    public function getSalutation(): SalutationEmbeddable
    {
        return $this->salutation;
    }

    public function setSalutation(SalutationEmbeddable $salutation): void
    {
        $this->salutation = $salutation;
    }

    public function getInstitutionPosition(): string
    {
        return $this->getPrimaryInstitutionRelationship()->getPosition();
    }

    public function getPhonePrivate(): PhoneEmbeddable
    {
        return $this->phonePrivate;
    }

    public function setPhonePrivate(PhoneEmbeddable $phonePrivate): void
    {
        $this->phonePrivate = $phonePrivate;
    }

    public function prePersist()
    {
        if ($this->getName()->getFullName() !== '') {
            $this->address->updateCombinedAdress($this->getName());
        }
    }

    public function getInstutionRelationships(): ArrayCollection|Collection
    {
        return $this->instutionRelationships;
    }

    public function setInstutionRelationships(ArrayCollection|Collection $instutionRelationships): void
    {
        $this->instutionRelationships = $this->mergeCollections($this->instutionRelationships, $instutionRelationships);
    }

    public function getPrimaryInstitutionRelationship(): ?UserInstitutionRelationship
    {
        return $this->primaryInstitutionRelationship;
    }

    public function setPrimaryInstitutionRelationship(?UserInstitutionRelationship $primaryInstitutionRelationship
    ): void {
        $this->primaryInstitutionRelationship = $primaryInstitutionRelationship;
    }
}
