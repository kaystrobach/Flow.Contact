<?php

namespace KayStrobach\Contact\Domain\Embeddable;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class BirthdayEmbeddable
{
    /**
     * @var \DateTimeImmutable|null
     */
    protected ?\DateTimeImmutable $date = null;

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getAge(): ?int
    {
        if ($this->date === null) {
            return null;
        }
        $diff = (new \DateTimeImmutable())->diff($this->date);
        return (int)$diff->format('%y');
    }

}
