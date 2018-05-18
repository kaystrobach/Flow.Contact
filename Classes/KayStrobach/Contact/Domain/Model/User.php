<?php

namespace KayStrobach\Contact\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TYPO3\Party\Domain\Model\AbstractParty;

/**
 * @Flow\Entity
 * @Gedmo\Loggable(logEntryClass="KayStrobach\Logger\Domain\Model\LogEntry")
 */
class User extends AbstractParty
{
    /**
     * @var \KayStrobach\Contact\Domain\Model\Contact
     * @ORM\OneToOne(cascade={"all"})
     * @Gedmo\Versioned
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
     * @param \Doctrine\Common\Collections\Collection $accounts All assigned TYPO3\Flow\Security\Account objects
     * @return void
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
    }

}