<?php
namespace KayStrobach\Contact\Domain\Repository;

/*
 * This file is part of the KayStrobach.Contact package.
 */

use KayStrobach\Contact\Domain\Model\Institution;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class UserRepository extends Repository
{

    /**
     * @param Institution $institution
     * @return \TYPO3\Flow\Persistence\QueryResultInterface
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
