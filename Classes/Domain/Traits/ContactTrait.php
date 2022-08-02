<?php
namespace KayStrobach\Contact\Domain\Traits;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

trait ContactTrait
{
    use ContactPhoneTrait;
    use ContactMailTrait;
}
