<?php

namespace KayStrobach\Contact\Domain\Dto;

use KayStrobach\Contact\Domain\Model\User;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Utility\Algorithms;
use Neos\Party\Domain\Model\ElectronicAddress;
use Neos\Party\Domain\Model\PersonName;

class UserDto {

    /**
     * @Flow\Validate (type="KayStrobach\Contact\Validation\Validator\UniqueValidator", options={"valueProperty":"accountIdentifier", "entityClass":"Neos\Flow\Security\Account"}, validationGroups={"create"})
     * @Flow\Validate(type="NotEmpty", validationGroups={"create"})
     * @var string
     */
    protected $username;

    /**
     * @Flow\Validate(type="NotEmpty", validationGroups={"create"})
     * @Flow\Validate(type="StringLength", options={"minimum":8}, validationGroups={"create"})
     * @var string
     */
    protected string $password = '';

    /**
     * @Flow\Validate(type="NotEmpty", validationGroups={"create"})
     * @var string
     */
    protected string $passwordConfirmation = '';

    /**
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * @var Account|null
     */
    protected ?Account $account = null;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\AccountFactory
     */
    protected $accountFactory;

    /**
     * @Flow\Inject()
     * @var AccountRepository
     */
    protected $accountRepository;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username ?? '';
    }

    /**
     * @param ?string $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->passwordConfirmation;
    }

    public function setPasswordConfirmation(string $passwordConfirmation): void
    {
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function createUser(): User
    {
        $user = new User();
        $user->setName(new PersonName());

        // name
        $user->getName()->setFirstName($this->user->getName()->getFirstName());
        $user->getName()->setLastName($this->user->getName()->getLastName());
        $user->getName()->setMiddleName($this->user->getName()->getMiddleName());
        $user->getName()->setAlias($this->user->getName()->getAlias());
        $user->getName()->setOtherName($this->user->getName()->getOtherName());
        $user->getName()->setTitle($this->user->getName()->getTitle());

        // address->getAddress()->setAddressAddon($this->getAddress()->getAddressAddon());
        $user->getAddress()->setCombinedAddress($this->user->getAddress()->getCombinedAddress());
        $user->getAddress()->setCity($this->user->getAddress()->getCity());
        $user->getAddress()->setCity($this->user->getAddress()->getCountry());
        $user->getAddress()->setStreet($this->user->getAddress()->getStreet());
        $user->getAddress()->setHouseNumber($this->user->getAddress()->getHouseNumber());
        $user->getAddress()->setRoomNumber($this->user->getAddress()->getRoomNumber());
        $user->getAddress()->setZipCode($this->user->getAddress()->getZipCode());

        // address
        $ea = new ElectronicAddress();
        $ea->setIdentifier($this->getUsername());
        $ea->setUsage('Work');
        $ea->setType('Email');
        $ea->setApproved(true);
        $this->user->setPrimaryElectronicAddress(
            $ea
        );

        // username
        $account = $this->accountFactory->createAccountWithPassword(
            $this->getUsername(),
            Algorithms::generateRandomString(20),
            [],
            'DefaultProvider'
        );
        $user->addAccount($account);
        $this->accountRepository->add($account);

        return $user;
    }
}
