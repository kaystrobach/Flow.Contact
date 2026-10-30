<?php

namespace KayStrobach\Contact\Search\Demand;

use KayStrobach\VisualSearch\Demands\AbstractDemand;

/**
 * Filters by the expiration date of accounts, same rule as Account::isActive():
 * "active" = no expiration date or expiration date in the future, "disabled" = expired.
 */
class AccountStatusDemand extends AbstractDemand
{
    public const ACTIVE = 'active';
    public const DISABLED = 'disabled';

    public function getDemands($value)
    {
        $now = new \DateTime('now');
        $subDemands = [];
        foreach ($this->fields as $matchField) {
            switch ($value) {
                case self::ACTIVE:
                    $subDemands[] = $this->query->logicalOr([
                        $this->query->equals($matchField, null),
                        $this->query->greaterThan($matchField, $now),
                    ]);
                    break;
                case self::DISABLED:
                    $subDemands[] = $this->query->lessThanOrEqual($matchField, $now);
                    break;
                default:
                    return null;
            }
        }
        return $this->query->logicalOr($subDemands);
    }
}
