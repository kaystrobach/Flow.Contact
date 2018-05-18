<?php

namespace KayStrobach\Contact\Controller;

use KayStrobach\Contact\Domain\Repository\UserRepository;
use TYPO3\Flow\Annotations as Flow;
use KayStrobach\Contact\Domain\Model\Institution;
use KayStrobach\Contact\Domain\Repository\InstitutionRepository;

class InstitutionController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
     * @Flow\Inject()
     * @var InstitutionRepository
     */
    protected $institutionRepository;

    /**
     * @Flow\Inject()
     * @var UserRepository
     */
    protected $userRepository;

    /**
     *
     */
    public function indexAction() {
        $this->view->assign(
            'institutions',
            $this->institutionRepository->findAll()
        );
    }

    /**
     * @Flow\IgnoreValidation("institution")
     * @param Institution $institution
     */
    public function newAction(Institution $institution = null) {

    }

    /**
     * @param Institution $institution
     */
    public function createAction(Institution $institution) {
        $this->institutionRepository->add($institution);
        $this->redirect(
            'edit',
            null,
            null,
            array(
                'institution' => $institution
            )
        );
    }

    /**
     * @Flow\IgnoreValidation("institution")
     * @param Institution $institution
     */
    public function editAction(Institution $institution) {
        $this->view->assign('institution', $institution);
        $this->view->assign('users', $this->userRepository->findByInstitution($institution));
    }

    public function updateAction(Institution $institution) {
        $this->institutionRepository->update($institution);
        $this->redirect('index');
    }
}