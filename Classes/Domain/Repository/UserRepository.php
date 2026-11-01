<?php
namespace KayStrobach\Contact\Domain\Repository;

/*
 * This file is part of the KayStrobach.Contact package.
 */

use KayStrobach\Contact\Domain\Model\Institution;
use KayStrobach\VisualSearch\Domain\Repository\SearchableRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;

/**
 * @Flow\Scope("singleton")
 */
class UserRepository extends SearchableRepository
{
    /**
     * @var string
     */
    protected $defaultSearchName = 'KayStrobach_Contact_User';

    /**
     * Users with any relationship to the institution, not only the primary one
     *
     * @param Institution $institution
     * @return \Neos\Flow\Persistence\QueryResultInterface
     */
    public function findByInstitution(Institution $institution)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals(
                'instutionRelationships.institution', $institution
            )
        );
        return $query->execute();
    }

    /**
     * Users with at least one account that is not expired (see Account::isActive())
     *
     * @return \Neos\Flow\Persistence\QueryResultInterface
     */
    public function findActive()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr([
                $query->equals('accounts.expirationDate', null),
                $query->greaterThan('accounts.expirationDate', new \DateTime('now')),
            ])
        );
        $query->setDistinct();
        $query->setOrderings([
            'name.lastName' => QueryInterface::ORDER_ASCENDING,
            'name.firstName' => QueryInterface::ORDER_ASCENDING,
        ]);
        return $query->execute();
    }

    public function findByEmailOrAccountName($string)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr(
                [
                    $query->equals('contact.email', $string),
                    $query->equals('accounts.accountIdentifier', $string)
                ]
            )
        );
        return $query->execute();
    }
}
