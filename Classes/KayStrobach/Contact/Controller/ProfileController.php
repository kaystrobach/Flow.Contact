<?php
namespace KayStrobach\Contact\Controller;

/*                                                                        *
 * This script belongs to the Flow package "SingleSignOn".               *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Exception\StopActionException;
use TYPO3\Flow\Security\AccountRepository;
use TYPO3\Flow\Security\Cryptography\HashService;
use TYPO3\Party\Domain\Service\PartyService;

/**
 * Standard controller for the SingleSignOn package
 *
 * @Flow\Scope("singleton")
 */
class ProfileController extends \TYPO3\Flow\Mvc\Controller\ActionController {
    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @Flow\Inject()
     * @var PartyService
     */
    protected $partyService;

    /**
     * @var \TYPO3\Flow\Security\Account
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