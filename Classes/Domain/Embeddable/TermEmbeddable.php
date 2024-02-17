<?php

declare(strict_types=1);

namespace KayStrobach\Contact\Domain\Embeddable;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;

/**
 * @ORM\Embeddable()
 */
class TermEmbeddable
{
    /**
     * @ORM\Column(nullable=true)
     * @var ?DateTimeImmutable
     */
    protected ?DateTimeImmutable $startAt;

    /**
     * @ORM\Column(nullable=true)
     * @var ?DateTimeImmutable
     */
    protected ?DateTimeImmutable $stopAt;

    public function getStartAt(): ?DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?DateTimeImmutable $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getStopAt(): ?DateTimeImmutable
    {
        return $this->stopAt;
    }

    public function setStopAt(?DateTimeImmutable $stopAt): void
    {
        $this->stopAt = $stopAt;
    }
}
