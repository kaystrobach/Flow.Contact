<?php
namespace KayStrobach\Contact\Controller;


use Neos\Error\Messages\Error;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Security\Authentication\Controller\AbstractAuthenticationController;
use Neos\Flow\Security\Cryptography\HashService;
use Neos\Flow\Security\Exception\AuthenticationRequiredException;

class AuthenticationController extends AbstractAuthenticationController
{
    /**
     * @Flow\Inject()
     * @var ConfigurationManager
     */
    protected $configurationManager;

    /**
     * @Flow\InjectConfiguration(path="Layout")
     * @var array
     */
    protected $settings;

    /**
     * @Flow\Inject
     * @var HashService
     */
    protected $hashService;

    protected function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);
        $view->assign('settings', $this->settings);
    }

    /**
     * @param string|null $username
     * @return void
     */
    public function loginAction($username = null)
    {
        $this->view->assign('username', $username);
    }

    public function loginTwoFactorAction($code)
    {

    }

    /**
     * Logs all active tokens out. Override this, if you want to
     * have some custom action here. You can always call the parent
     * method to do the actual logout.
     *
     * @return void
     * @Flow\SkipCsrfProtection
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function logoutAction()
    {
        parent::logoutAction();
        $this->redirect('login');
    }

    /**
     * Is called if authentication was successful. If there has been an
     * intercepted request due to security restrictions, you might want to use
     * something like the following code to restart the originally intercepted
     * request:
     *
     * if ($originalRequest !== NULL) {
     *     $this->redirectToRequest($originalRequest);
     * }
     * $this->redirect('someDefaultActionAfterLogin');
     *
     * @param ActionRequest $originalRequest The request that was intercepted by the security framework, NULL if there was none
     * @return string
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Flow\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function onAuthenticationSuccess(ActionRequest $originalRequest = null)
    {
        $configuration = $this->settings['AuthenticationController'];

        if (($configuration['RedirectMode'] === 'redirectToOriginalRequest') && ($originalRequest !== null)) {
            $this->redirectToRequest($originalRequest);
        }

        $arguments = [];
        if (($configuration['RedirectMode'] === 'appendOriginalRequest') && ($originalRequest !== null)) {
            $this->securityContext->setInterceptedRequest($originalRequest);
            $arguments = [
                '__referer' => [
                    '@package' => $originalRequest->getControllerPackageKey(),
                    '@subpackage' => $originalRequest->getControllerSubpackageKey(),
                    '@controller' => $originalRequest->getControllerName(),
                    '@action' => $originalRequest->getControllerActionName(),
                    'arguments' => $this->hashService->appendHmac(base64_encode(serialize($originalRequest->getArguments())))
                ]
            ];
        }

        $this->redirect(
            $configuration['RedirectAfterSuccessfullLogin']['action'],
            $configuration['RedirectAfterSuccessfullLogin']['controller'],
            $configuration['RedirectAfterSuccessfullLogin']['package'],
            $arguments
        );
    }

    /**
     * Is called if authentication failed.
     *
     * Override this method in your login controller to take any
     * custom action for this event. Most likely you would want
     * to redirect to some action showing the login form again.
     *
     * @param AuthenticationRequiredException $exception The exception thrown while the authentication process
     * @return void
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    protected function onAuthenticationFailure(AuthenticationRequiredException $exception = null)
    {
        $this->controllerContext->getFlashMessageContainer()->addMessage(
            new Error('Authentication failed!', ($exception === null ? 1347016771 : $exception->getCode()))
        );
        $arguments = $this->request->getInternalArguments();
        $username = \Neos\Utility\ObjectAccess::getPropertyPath(
            $arguments,
            '__authentication.Neos.Flow.Security.Authentication.Token.UsernamePassword.username'
        );

        $this->redirect(
            'login',
            null,
            null,
            array(
                'username' => $username
            )
        );
    }
}
