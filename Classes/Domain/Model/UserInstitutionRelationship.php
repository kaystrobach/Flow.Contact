<?php

namespace KayStrobach\Contact\Domain\Model;

use KayStrobach\Contact\Domain\Embeddable\AddressEmbeddable;
use KayStrobach\Contact\Domain\Embeddable\CreatedEmbeddable;
use KayStrobach\Contact\Domain\Embeddable\PhoneEmbeddable;
use KayStrobach\Contact\Domain\Embeddable\TermEmbeddable;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Party\Domain\Model\ElectronicAddress;

/**
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class UserInstitutionRelationship
{
    /**
     * @ORM\ManyToOne(inversedBy="personRelationships")
     * @var ?Institution
     */
    protected ?Institution $institution = null;

    /**
     * @ORM\ManyToOne(inversedBy="instutionRelationships")
     * @var ?User
     */
    protected ?User $user = null;

    /**
     * @var string
     */
    protected string $position = '';

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected string $notes = '';

    /**
     * @ORM\Embedded(columnPrefix="created_")
     * @var CreatedEmbeddable
     */
    protected $created;

    /**
     * @ORM\Embedded(columnPrefix="term_")
     * @var TermEmbeddable
     */
    protected $term;

    /**
     * @ORM\Embedded(columnPrefix="address_")
     * @var AddressEmbeddable
     */
    protected $address;

    /**
     * @ORM\Embedded(columnPrefix="postal_address_")
     * @var AddressEmbeddable
     */
    protected $postalAddress;

    /**
     * @todo remove
     * @ORM\Embedded(columnPrefix="phone_work_")
     * @var PhoneEmbeddable
     */
    protected $phoneWork;

    /**
     * @ORM\Embedded(columnPrefix="phone_private_")
     * @var PhoneEmbeddable
     */
    protected $phonePrivate;

    /**
     * @var ?ElectronicAddress
     * @ORM\ManyToOne
     */
    protected ?ElectronicAddress $primaryElectronicAddress;

    public function __construct()
    {
        $this->created = new \DateTimeImmutable('now');
        $this->term = new TermEmbeddable();
        $this->address = new AddressEmbeddable();
        $this->phoneWork = new PhoneEmbeddable();
        $this->phonePrivate = new PhoneEmbeddable();
        $this->postalAddress = new AddressEmbeddable();
    }

    public function getInstitution(): ?Institution
    {
        return $this->institution;
    }

    public function setInstitution(?Institution $institution): void
    {
        $this->institution = $institution;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getCreated(): CreatedEmbeddable|\DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(CreatedEmbeddable|\DateTimeImmutable $created): void
    {
        $this->created = $created;
    }

    public function getTerm(): TermEmbeddable
    {
        return $this->term;
    }

    public function setTerm(TermEmbeddable $term): void
    {
        $this->term = $term;
    }

    public function getAddress(): AddressEmbeddable
    {
        return $this->address;
    }

    public function setAddress(AddressEmbeddable $address): void
    {
        $this->address = $address;
    }

    public function getPhoneWork(): PhoneEmbeddable
    {
        return $this->phoneWork;
    }

    public function setPhoneWork(PhoneEmbeddable $phoneWork): void
    {
        $this->phoneWork = $phoneWork;
    }

    public function getPhonePrivate(): PhoneEmbeddable
    {
        return $this->phonePrivate;
    }

    public function setPhonePrivate(PhoneEmbeddable $phonePrivate): void
    {
        $this->phonePrivate = $phonePrivate;
    }

    public function getPrimaryElectronicAddress(): ?ElectronicAddress
    {
        return $this->primaryElectronicAddress;
    }

    public function setPrimaryElectronicAddress(?ElectronicAddress $primaryElectronicAddress): void
    {
        $this->primaryElectronicAddress = $primaryElectronicAddress;
    }

    public function getPostalAddress(): AddressEmbeddable
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(AddressEmbeddable $postalAddress): void
    {
        $this->postalAddress = $postalAddress;
    }

    public function prePersist()
    {
        if ($this->getUser() === null) {
            return;
        }
        if ($this->getUser()->getName()->getFullName() !== '') {
            $this->address->updateCombinedAdress($this->getUser()->getName());
        }
    }
}
