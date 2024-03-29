<?php

namespace KayStrobach\Contact\Controller;

use KayStrobach\Contact\Domain\Model\User;
use KayStrobach\Contact\Domain\Repository\InstitutionRepository;
use KayStrobach\Contact\Domain\Repository\UserRepository;
use Neos\Flow\Annotations as Flow;
use KayStrobach\Contact\Domain\Model\Institution;
use Neos\Error\Messages\Message;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Cryptography\HashService;
use Neos\Flow\Utility\Algorithms;
use Neos\Party\Domain\Model\ElectronicAddress;
use Neos\Party\Domain\Service\PartyService;

class UserController extends \Neos\Flow\Mvc\Controller\ActionController
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
     * @var \Neos\Flow\Security\Policy\PolicyService
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
     * @var \Neos\Flow\Security\AccountFactory
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
            'now',
            new \DateTime('now')
        );
        $this->view->assign(
            'users',
            $this->userRepository->findByDefaultQuery()
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

        $partials = [];
        $data = [];
        $this->emitRenderSettings($this->view, $user, $partials, $data);
        $this->view->assign('additionalPartials', $partials);
        $this->view->assignMultiple($data);
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
        if(strlen($newPassword) < 8) {
            $this->addFlashMessage('8 Zeichen sind das minimum für ein Passwort.', '', Message::SEVERITY_ERROR);
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

    protected function getGeneralViewVariables()
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

    /**
     * @param ViewInterface $view
     * @param User $user
     * @param array $additionalPartials
     * @param array $additionalPartialsData
     * @return void
     * @Flow\Signal
     */
    protected function emitRenderSettings(ViewInterface $view, User $user, &$additionalPartials, &$additionalPartialsData)
    {

    }


    public function newElectronicAdressAction(User $object)
    {
        $this->view->assign(
            'object',
            $object
        );

        $ea = new ElectronicAddress();
        $ea->setType('Email');
        $ea->setUsage('Work');
        $ea->setApproved(1);
        $this->view->assign(
            'electronicAddress',
            $ea
        );
    }

    public function addElectronicAdressAction(User $object, ElectronicAddress $electronicAddress)
    {
        $object->addElectronicAddress($electronicAddress);
        if ($object->getPrimaryElectronicAddress() === null) {
            $object->setPrimaryElectronicAddress($electronicAddress);
        }
        $this->userRepository->update($object);
        $this->redirect(
            'edit',
            null,
            null,
            [
                'user' => $object
            ]
        );
    }
}
