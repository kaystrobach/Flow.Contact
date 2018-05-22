<?php

namespace KayStrobach\Contact\Command;

use KayStrobach\Contact\Domain\Model\Contact;
use KayStrobach\Contact\Domain\Model\User;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Party\Domain\Model\PersonName;

/**
 * @Flow\Scope("singleton")
 */
class AccountCommandController extends \Neos\Flow\Cli\CommandController
{
    /**
     * @Flow\Inject()
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\Policy\PolicyService
     */
    protected $policyService;

    /**
     * @var \Neos\Flow\Security\Cryptography\HashService
     * @Flow\Inject
     */
    protected $hashService;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\AccountFactory
     */
    protected $accountFactory;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Lists all users
     */
    public function listCommand() {
        $users = $this->accountRepository->findAll();

        /** @var Account $account */
        foreach ($users as $currentKey => $account) {

            $this->outputLine($account->getAccountIdentifier());
            $this->outputLine('    <info>Created:</info> ' . $account->getCreationDate()->format( \DateTime::W3C));
            if ($account->getExpirationDate()) {
                $this->outputLine('    <info>Expires:</info> ' . $account->getExpirationDate()->format( \DateTime::W3C));
            }
            $this->outputLine('    <info>Active:</info>  ' . ($account->isActive() ? '<success>yes</success>' : '<error>no</error>'));
            $this->outputLine('    <info>Failed:</info>  ' . $account->getFailedAuthenticationCount());
            $this->outputLine('');
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
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
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
            $account = $this->accountFactory->createAccountWithPassword(
                $username,
                $password,
                explode(',', $roles),
                $authenticationProvider
            );
            $this->accountRepository->add($account);
            $this->outputFormatted('User: <em>' . $username . '</em> created with password "' . $password . '".');
        }
        $this->persistenceManager->persistAll();
    }
}