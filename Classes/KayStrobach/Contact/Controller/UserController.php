<?php

namespace KayStrobach\Contact\Controller;

use KayStrobach\Contact\Domain\Model\User;
use KayStrobach\Contact\Domain\Repository\InstitutionRepository;
use KayStrobach\Contact\Domain\Repository\UserRepository;
use TYPO3\Flow\Annotations as Flow;
use KayStrobach\Contact\Domain\Model\Institution;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Exception\StopActionException;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\AccountRepository;
use TYPO3\Flow\Security\Cryptography\HashService;
use TYPO3\Flow\Utility\Algorithms;
use TYPO3\Party\Domain\Service\PartyService;

class UserController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
     * @Flow\Inject()
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject()
     * @var InstitutionRepository
     */
    protected $institutionRepository;

    /**
     * @var \TYPO3\Flow\Security\Policy\PolicyService
     * @Flow\Inject
     */
    protected $policyService;

    /**
     * @Flow\Inject()
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\AccountFactory
     */
    protected $accountFactory;

    /**
     * @Flow\Inject()
     * @var HashService
     */
    protected $hashService;

    /**
     * @Flow\Inject()
     * @var PartyService
     */
    protected $partyService;

    /**
     *
     */
    public function indexAction() {
        $this->view->assign(
            'users',
            $this->userRepository->findAll()
        );
    }

    /**
     * @Flow\IgnoreValidation("institution")
     * @param User $institution
     */
    public function newAction(User $institution = null) {
        $this->getGeneralViewVariables();
    }

    /**
     * @param User $user
     */
    public function createAction(User $user)
    {
        $this->fixMissingAccount($user);
        $this->userRepository->add($user);
        $this->redirect(
            'edit',
            null,
            null,
            array(
                'user' => $user
            )
        );
    }

    /**
     * @Flow\IgnoreValidation("user")
     * @param User $user
     */
    public function editAction(User $user)
    {
        $this->getGeneralViewVariables();
        $this->view->assign('user', $user);
    }

    public function updateAction(User $user)
    {
        foreach ($user->getAccounts() as $account) {
            $this->accountRepository->update($account);
        }
        $this->fixMissingAccount($user);
        $this->userRepository->update($user);
        $this->redirect(
            'edit',
            null,
            null,
            array(
                'user' => $user
            )
        );
    }

    /**
     * @param Account $account
     * @param string $newPassword
     * @param string $newPasswordDuplicate
     * @throws StopActionException
     */
    public function updatePasswordAction(Account $account, $newPassword, $newPasswordDuplicate)
    {
        if(strlen($newPassword) < 6) {
            $this->addFlashMessage('6 Zeichen sind das minimum für ein Passwort.', '', Message::SEVERITY_ERROR);
            $this->redirect(
                'edit',
                null,
                null,
                [
                    'user' => $this->partyService->getAssignedPartyOfAccount($account)
                ]
            );
            throw new StopActionException();
        }
        if($newPassword !== $newPasswordDuplicate) {
            $this->addFlashMessage('Passwörter stimmen nicht überein.', '', Message::SEVERITY_ERROR);
            $this->redirect(
                'edit',
                null,
                null,
                [
                    'user' => $this->partyService->getAssignedPartyOfAccount($account)
                ]
            );
            throw new StopActionException();
        }

        $account->setCredentialsSource(
            $this->hashService->hashPassword($newPassword, 'default')
        );
        $this->accountRepository->update($account);
        $this->addFlashMessage('Passwort erfolgreich geändert');
        $this->redirect(
            'edit',
            null,
            null,
            [
                'user' => $this->partyService->getAssignedPartyOfAccount($account)
            ]
        );
    }

    public function getGeneralViewVariables()
    {
        $this->view->assign(
            'institutions',
            $this->institutionRepository->findAll()
        );
        $this->view->assign(
            'roles',
            $this->policyService->getRoles()
        );
    }

    protected function fixMissingAccount(User $user)
    {
        if ($user->getAccounts()->count() === 0) {
            $account = $this->accountFactory->createAccountWithPassword(
                strtolower(Algorithms::generateRandomString(6)),
                Algorithms::generateRandomString(20),
                [],
                'DefaultProvider'
            );
            $user->addAccount($account);
            $this->accountRepository->add($account);
        }
    }
}