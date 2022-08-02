<?php

namespace KayStrobach\Contact\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Party\Domain\Model\AbstractParty;
use Neos\Party\Domain\Model\Person;

/**
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class User extends Person
{
    /**
     * @var \KayStrobach\Contact\Domain\Model\Contact
     * @ORM\OneToOne(cascade={"all"}, inversedBy="user")
     */
    protected $contact;

    /**
     * @var \KayStrobach\Contact\Domain\Model\Institution
     * @ORM\ManyToOne(cascade={"persist"}, inversedBy="users")
     */
    protected $institution;

    /**
     * @return \KayStrobach\Contact\Domain\Model\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param \KayStrobach\Contact\Domain\Model\Contact $contact
     * @return void
     */
    public function setContact(\KayStrobach\Contact\Domain\Model\Contact $contact)
    {
        $this->contact = $contact;
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
     * @ORM\PostLoad()
     * @return void
     */
    public function postInitialize()
    {
        $this->contact->setUser($this);
    }
}
