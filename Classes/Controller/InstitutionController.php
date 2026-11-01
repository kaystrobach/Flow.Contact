<?php

namespace KayStrobach\Contact\Controller;

use KayStrobach\Contact\Domain\Model\User;
use KayStrobach\Contact\Domain\Repository\UserRepository;
use Neos\Flow\Annotations as Flow;
use KayStrobach\Contact\Domain\Model\Institution;
use KayStrobach\Contact\Domain\Repository\InstitutionRepository;
use Neos\Flow\Mvc\View\ViewInterface;

class InstitutionController extends \Neos\Flow\Mvc\Controller\ActionController
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
            $this->institutionRepository->findByDefaultQuery()
        );
    }

    /**
     * @Flow\IgnoreValidation("institution")
     * @param Institution $institution
     */
    public function newAction(?Institution $institution = null) {

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

        $partials = [];
        $data = [];
        $this->emitRenderSettings($this->view, $institution, $partials, $data);
        $this->view->assign('additionalPartials', $partials);
        $this->view->assignMultiple($data);
    }

    public function updateAction(Institution $institution) {
        $this->institutionRepository->update($institution);
        $this->redirect('index');
    }

    public function deletedAction()
    {
        $this->view->assign('institutions', $this->institutionRepository->findDeleted());
    }

    /**
     * soft delete, relationships to users are kept
     */
    public function deleteAction(Institution $institution)
    {
        $institution->delete();
        $this->institutionRepository->update($institution);
        $this->addFlashMessage('Einrichtung ' . $institution->getName() . ' wurde gelöscht');
        $this->redirect('index');
    }

    public function restoreAction(Institution $institution)
    {
        $institution->restore();
        $this->institutionRepository->update($institution);
        $this->addFlashMessage('Einrichtung ' . $institution->getName() . ' wurde wiederhergestellt');
        $this->redirect('deleted');
    }

    /**
     * @param ViewInterface $view
     * @param Institution $institution
     * @param array $additionalPartials
     * @param array $additionalPartialsData
     * @return void
     * @Flow\Signal
     */
    protected function emitRenderSettings(ViewInterface $view, Institution $institution, &$additionalPartials, &$additionalPartialsData)
    {}
}
