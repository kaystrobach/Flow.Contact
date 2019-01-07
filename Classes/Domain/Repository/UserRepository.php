<?php
namespace KayStrobach\Contact\Domain\Repository;

/*
 * This file is part of the KayStrobach.Contact package.
 */

use KayStrobach\Contact\Domain\Model\Institution;
use KayStrobach\VisualSearch\Domain\Repository\SearchableRepository;
use Neos\Flow\Annotations as Flow;

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
     * @param Institution $institution
     * @return \Neos\Flow\Persistence\QueryResultInterface
     */
    public function findByInstitution(Institution $institution)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals(
                'institution', $institution
            )
        );
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
