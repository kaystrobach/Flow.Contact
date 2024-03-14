<?php

declare(strict_types=1);

namespace KayStrobach\Contact\Domain\Embeddable;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;

/**
 * @ORM\Embeddable()
 */
class CreatedEmbeddable
{
    /**
     * @var ?DateTimeImmutable
     */
    protected ?DateTimeImmutable $at;

    /**
     * @var string
     */
    protected string $by = '';

    public function __construct()
    {
        $this->at = new DateTimeImmutable('now');
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getAt(): ?DateTimeImmutable
    {
        return $this->at;
    }

    /**
     * @param DateTimeImmutable|null $at
     * @return void
     */
    public function setAt(?DateTimeImmutable $at): void
    {
        $this->at = $at;
    }

    /**
     * @return string
     */
    public function getBy(): string
    {
        return $this->by;
    }

    /**
     * @param string $by
     * @return void
     */
    public function setBy(string $by): void
    {
        $this->by = $by;
    }
}
