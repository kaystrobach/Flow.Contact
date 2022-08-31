<?php
namespace KayStrobach\Contact\Domain\Model;

use KayStrobach\Contact\Domain\Traits\ContactPhoneTrait;
use KayStrobach\Contact\Domain\Traits\ContactTrait;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Neos\Party\Domain\Model\ElectronicAddress;
use Neos\Party\Domain\Model\Person;
use Neos\Party\Domain\Model\PersonName;
use Neos\Utility\ObjectAccess;

/**
 * @Flow\Entity
 */
class Contact
{
    use ContactPhoneTrait;

    /**
     * @ORM\OneToOne(cascade={"all"}, mappedBy="contact", fetch="EAGER")
     * @var User
     */
    protected $user;

    /**
     * @var PersonName
     * @ORM\Column(nullable=true)
     * @ORM\OneToOne(cascade={"all"})
     */
    protected $name;

    /**
     * @var string
     * @Flow\Validate(type="String")
     */
    protected $position = '';

    /**
     * @ORM\Column(nullable=true)
     * @ORM\OneToOne(cascade={"all"})
     * @var \KayStrobach\Contact\Domain\Model\Address
     */
    protected $address;

    /**
     * @deprecated
     * @return string
     */
    public function getEmail()
    {
        return ObjectAccess::getPropertyPath($this->getUser(), 'primaryElectronicAddress.identifier');
    }

    /**
     * @deprecated
     * @param string $email
     * @return void
     */
    public function setEmail(string $email)
    {
        if (!$this->getUser()->getPrimaryElectronicAddress()) {
            $ea = new ElectronicAddress();
            $ea->setType(ElectronicAddress::TYPE_EMAIL);
            $ea->setApproved(true);
            $ea->setUsage(ElectronicAddress::USAGE_WORK);
            $this->getUser()->setPrimaryElectronicAddress($ea);
        }
        $this->getUser()->getPrimaryElectronicAddress()->setIdentifier($email);
    }

    /**
     * @deprecated
     * @return PersonName
     */
    public function getName()
    {
        if ($this->getUser()->getName() === null) {
            $this->getUser()->setName(new PersonName());
        }
        return $this->getUser()->getName();
    }

    /**
     * @deprecated
     * @param PersonName $name
     */
    public function setName(PersonName $name)
    {
        if ($name->getFullName() !== '') {
            $this->name = $name;
            $this->getUser()->setName($name);
        }
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
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

    /**
     * @return User
     */
    public function getUser(): User
    {
        if ($this->user === null) {
            $this->user = new User();
            $this->user->setName(new PersonName());
        }
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
