<?php
namespace KayStrobach\Contact\Domain\Repository;

/*
 * This file is part of the KayStrobach.Contact package.
 */

use KayStrobach\Contact\Domain\Model\Institution;
use KayStrobach\VisualSearch\Domain\Repository\SearchableRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\QueryResultInterface;

/**
 * @Flow\Scope("singleton")
 */
class InstitutionRepository extends SearchableRepository
{
    /**
     * @var string
     */
    protected $defaultSearchName = 'KayStrobach_Contact_Institution';

    /**
     * soft deleted institutions are not listed, see findDeleted()
     */
    protected function initializeFindByQuery($queryObject, $searchName)
    {
        return [
            $queryObject->equals('deleted', false)
        ];
    }

    /**
     * soft deleted institutions are not suggested in facets (e.g. "Institution" in the user search)
     */
    protected function initializeFindBySearchTerm($queryObject, $facetConfiguration)
    {
        return [
            $queryObject->equals('deleted', false)
        ];
    }

    /**
     * institutions to choose from, the current institution stays selectable even if it was deleted
     *
     * @return Institution[]
     */
    public function findSelectable(?Institution $current = null): array
    {
        $query = $this->createQuery();
        $institutions = $query
            ->matching($query->equals('deleted', false))
            ->setOrderings(['name' => QueryInterface::ORDER_ASCENDING])
            ->execute()
            ->toArray();
        if ($current !== null && $current->isDeleted()) {
            $institutions[] = $current;
        }
        return $institutions;
    }

    public function findDeleted(): QueryResultInterface
    {
        $query = $this->createQuery();
        return $query
            ->matching($query->equals('deleted', true))
            ->setOrderings(['name' => QueryInterface::ORDER_ASCENDING])
            ->execute();
    }
}
