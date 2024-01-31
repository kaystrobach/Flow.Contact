<?php

namespace KayStrobach\Contact\Domain\Model;

use KayStrobach\Contact\Domain\Embeddable\AddressEmbeddable;
use KayStrobach\Contact\Domain\Embeddable\PhoneEmbeddable;
use KayStrobach\Contact\Domain\Embeddable\SalutationEmbeddable;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Party\Domain\Model\Person;

/**
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class User extends Person
{
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
     * @ORM\Embedded(columnPrefix="phone_")
     * @var PhoneEmbeddable
     */
    protected $phone;

    /**
     * @ORM\Embedded(columnPrefix="phone_private_")
     * @var PhoneEmbeddable
     */
    protected $phonePrivate;

    /**
     * @var \KayStrobach\Contact\Domain\Model\Institution
     * @ORM\ManyToOne(cascade={"persist"}, inversedBy="users")
     */
    protected $institution;

    /**
     * @var string
     * @Flow\Validate(type="String")
     */
    protected string $institutionPosition = '';

    public function __construct()
    {
        parent::__construct();
        $this->address = new AddressEmbeddable();
        $this->salutation = new SalutationEmbeddable();
        $this->phone = new PhoneEmbeddable();
        $this->phonePrivate = new PhoneEmbeddable();
    }

    /**
     * @return \KayStrobach\Contact\Domain\Model\Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param \KayStrobach\Contact\Domain\Model\Institution $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
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

    public function getPhone(): PhoneEmbeddable
    {
        return $this->phone;
    }

    public function setPhone(PhoneEmbeddable $phone): void
    {
        $this->phone = $phone;
    }

    public function getInstitutionPosition(): string
    {
        return $this->institutionPosition;
    }

    public function setInstitutionPosition(string $institutionPosition): void
    {
        $this->institutionPosition = $institutionPosition;
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
}
