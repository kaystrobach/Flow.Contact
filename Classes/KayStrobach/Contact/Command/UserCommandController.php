<?php

namespace KayStrobach\Contact\Command;

use KayStrobach\Contact\Domain\Model\Contact;
use KayStrobach\Contact\Domain\Model\User;
use KayStrobach\Contact\Domain\Repository\UserRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;
use TYPO3\Party\Domain\Model\PersonName;

/**
 * @Flow\Scope("singleton")
 */
class UserCommandController extends \TYPO3\Flow\Cli\CommandController
{
    /**
     * @Flow\Inject()
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\Policy\PolicyService
     */
    protected $policyService;

    /**
     * @var \TYPO3\Flow\Security\Cryptography\HashService
     * @Flow\Inject
     */
    protected $hashService;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\AccountRepository
     */
    protected $accountRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\AccountFactory
     */
    protected $accountFactory;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Lists all users
     */
    public function listCommand() {
        $users = $this->userRepository->findAll();
        /** @var User $user */
        foreach ($users as $user) {
            $this->outputLine('  ' . $user->getContact()->getName()->getFullName());
        }
    }

    /**
     * creates a user
     *
     * @param string $username
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @param string $roles
     * @param string $authenticationProvider
     */
    public function createCommand(
        $username,
        $password,
        $firstName,
        $lastName,
        $roles = 'KayStrobach.Contact:Administrator',
        $authenticationProvider = 'DefaultProvider'
    ) {
        $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
        if ($account instanceof Account) {
            $account->setCredentialsSource($this->hashService->hashPassword($password, 'default'));
            $this->accountRepository->update($account);
            $this->outputFormatted('User: <em>' . $username . '</em> already existing ... now with password "' . $password . '".');
        } else {
            $user = new User();
            $user->setContact(new Contact());
            $user->getContact()->setName(new PersonName());
            $user->getContact()->getName()->setFirstName($firstName);
            $user->getContact()->getName()->setLastName($lastName);

            $account = $this->accountFactory->createAccountWithPassword(
                $username,
                $password,
                explode(',', $roles),
                $authenticationProvider
            );

            $user->addAccount($account);

            $this->accountRepository->add($account);
            $this->userRepository->add($user);
            $this->outputFormatted('User: <em>' . $username . '</em> created with password "' . $password . '".');
        }
        $this->persistenceManager->persistAll();
    }
}