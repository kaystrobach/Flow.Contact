<?php
namespace KayStrobach\Contact\Domain\Repository;

/*
 * This file is part of the KayStrobach.Contact package.
 */

use KayStrobach\VisualSearch\Domain\Repository\SearchableRepository;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class InstitutionRepository extends SearchableRepository
{
    /**
     * @var string
     */
    protected $defaultSearchName = 'KayStrobach_Contact_Institution';
    // add customized methods here

}
