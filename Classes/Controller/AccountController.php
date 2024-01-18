<?php

namespace KayStrobach\Contact\Controller;

use KayStrobach\Contact\Domain\Dto\AccountDto;
use KayStrobach\Contact\Domain\Model\User;
use Neos\Error\Messages\Message;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountFactory;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Cryptography\HashService;
use Neos\Flow\Security\Policy\PolicyService;
use Neos\Party\Domain\Service\PartyService;
use Neos\Flow\Annotations as Flow;

class AccountController extends ActionController
{
    /**
     * @var PolicyService
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
     * @var AccountFactory
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

    public function newAction(User $user)
    {
        $dto = new AccountDto();
        $dto->account = new Account();
        $dto->user = $user;
        $this->view->assign('dto', $dto);
    }

    public function editPasswordAction(Account $account)
    {
        $dto = new AccountDto();
        $dto->account = new Account();
        $this->view->assign('dto', $dto);
    }

    public function updatePasswordAction(AccountDto $dto)
    {
        $account = $dto->account;
        if(strlen($dto->password) < 8) {
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
        if($dto->password !== $dto->passwordRepeat) {
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
            $this->hashService->hashPassword($dto->password, 'default')
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
}
