<?php
/**
 * Created by kay.
 */

namespace KayStrobach\Contact\Controller;

use Neos\Flow\Annotations as Flow;
use KayStrobach\Contact\Domain\Model\User;
use KayStrobach\Contact\Domain\Repository\UserRepository;
use Neos\Error\Messages\Message;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\AccountRepository;

class ForgotPasswordController extends ActionController
{
    /**
     * @Flow\Inject()
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject()
     * @var \Neos\Flow\Security\Cryptography\HashService
     */
    protected $hashService;

    /**
     * @Flow\Inject()
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @Flow\InjectConfiguration(path="Layout")
     * @var array
     */
    protected $settings;

    protected function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);
        $view->assign('settings', $this->settings);
    }

    /**
     * @param string|null $username
     */
    public function indexAction($username = null)
    {
        $this->view->assign('username', $username);
    }

    /**
     * @param string $username
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException
     */
    public function sendForgotPasswordMailAction($username)
    {
        $users = $this->userRepository->findByEmailOrAccountName($username);
        if ($users->count() === 0) {
            $this->addFlashMessage('Falls ein Account mit dieser Adresse existiert wurde nun eine E-Mail mit einem Passwortresetlink verschickt');
            $this->redirect(
                'index'
            );
        }
        if ($users->count() > 1) {
            $this->addFlashMessage('Es gibt mehr als einen Benutzer mit dieser Mailadresse, Funktion steht deswegen nicht zur Verfügung.');
            $this->redirect(
                'index'
            );
        }

        /** @var User $user */
        $user = $users->getFirst();
        /** @var Account $account */
        $account = $user->getAccounts()->first();
        $validUntilDate = new \DateTime('now');
        $validUntilDate->modify('+2 days');
        $validUntilString = $validUntilDate->format('y-m-d-h-i-s');
        $secret = $this->hashService->generateHmac($validUntilString);

        $this->systemLogger->log('account found');

        try {
            $mail = new \KayStrobach\Contact\Utility\MailUtility();
            $mail->send(
                array(
                    $user->getContact()->getEmail()
                ),
                'Authentication/PasswordForgotten',
                array(
                    'email' => $user->getContact()->getEmail(),
                    'user' => $user,
                    'account' => $account,
                    'validUntil' => $validUntilString,
                    'secret' => $secret,
                    'action' => 'resetPassword',
                    'controller' => 'ForgotPassword',
                    'package' => 'KayStrobach.Contact'
                )
            );
            $this->addFlashMessage('Falls ein Account mit dieser Adresse existiert wurde nun eine E-Mail mit einem Passwortresetlink verschickt');
        } catch (\Exception $e) {
            $this->addFlashMessage('Es gab ein Problem beim Senden der Nachricht. ' . $e->getMessage(), '', Message::SEVERITY_ERROR);
        }

        $this->redirect(
            'login',
            'Authentication'
        );
    }

    /**
     * @param Account $account
     * @param string $validUntil
     * @param string $secret
     */
    public function resetPasswordAction(Account $account, $validUntil, $secret)
    {
        if (!$this->hashService->validateHmac($validUntil, $secret)) {
            $this->addFlashMessage('Die Signatur konnte nicht überprüft werden.');
            $this->redirect(
                'index'
            );
        }
        $this->view->assign('account', $account);
    }

    /**
     * @param Account $account
     * @param string $newPassword
     * @param string $newPasswordDuplicate
     * @throws StopActionException
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function saveNewPasswordAction($account, $newPassword, $newPasswordDuplicate)
    {
        if(strlen($newPassword) < 6) {
            $this->addFlashMessage('6 Zeichen sind das minimum für ein Passwort.', '', Message::SEVERITY_ERROR);
            $this->forwardToReferringRequest();
            $this->redirect(
                'index'
            );
        }
        if($newPassword !== $newPasswordDuplicate) {
            $this->addFlashMessage('Passwörter stimmen nicht überein.', '', Message::SEVERITY_ERROR);
            $this->forwardToReferringRequest();
            $this->redirect(
                'index'
            );
        }

        $account->setCredentialsSource(
            $this->hashService->hashPassword($newPassword, 'default')
        );
        $this->accountRepository->update($account);
        $this->addFlashMessage('Passwort erfolgreich geändert');
        $this->redirect(
            'login',
            'Authentication',
            null,
            [
                'username' => $account->getAccountIdentifier()
            ]
        );
    }
}