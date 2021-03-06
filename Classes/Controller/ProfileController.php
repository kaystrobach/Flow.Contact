<?php
namespace KayStrobach\Contact\Controller;

/*                                                                        *
 * This script belongs to the Flow package "SingleSignOn".               *
 *                                                                        *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Message;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Security\AccountRepository;
use Neos\Flow\Security\Cryptography\HashService;
use Neos\Party\Domain\Service\PartyService;

/**
 * Standard controller for the SingleSignOn package
 *
 * @Flow\Scope("singleton")
 */
class ProfileController extends \Neos\Flow\Mvc\Controller\ActionController {
    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\Authentication\AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @Flow\Inject()
     * @var PartyService
     */
    protected $partyService;

    /**
     * @var \Neos\Flow\Security\Account
     */
    protected $account;

    /**
     * @Flow\Inject()
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @Flow\Inject()
     * @var HashService
     */
    protected $hashService;

    /**
     *
     */
    public function initializeAction() {
        $this->account = $this->authenticationManager->getSecurityContext()->getAccount();
    }

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction() {
        $this->view->assign('person', $this->partyService->getAssignedPartyOfAccount($this->account));
        $this->view->assign('account', $this->account);
    }

    /**
     * @param string $newPassword
     * @param string $newPasswordDuplicate
     * @throws StopActionException
     */
    public function changePasswordAction($newPassword, $newPasswordDuplicate) {
        if(strlen($newPassword) < 6) {
            $this->addFlashMessage('6 Zeichen sind das minimum für ein Passwort.', '', Message::SEVERITY_ERROR);
            $this->redirect('index');
            throw new StopActionException();
        }
        if($newPassword !== $newPasswordDuplicate) {
            $this->addFlashMessage('Passwörter stimmen nicht überein.', '', Message::SEVERITY_ERROR);
            $this->redirect('index');
            throw new StopActionException();
        }

        $this->account->setCredentialsSource(
            $this->hashService->hashPassword($newPassword, 'default')
        );
        $this->accountRepository->update($this->account);
        $this->addFlashMessage('Passwort erfolgreich geändert');
        $this->redirect('index');
    }

}