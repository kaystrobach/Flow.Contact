<?php

declare(strict_types=1);

namespace KayStrobach\Contact\Domain\Embeddable;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class SalutationEmbeddable
{
    /**
     * Herr
     * Frau
     * ?
     * @var string
     */
    protected string $salutation = '';

    /**
     * Sehr geehrter Herr
     * Sehr geehrte Frau
     * Hallo
     * @var string
     */
    protected string $formalSalutation = '';

    /**
     * @var string
     */
    protected string $pronouns = '';

    public function getSalutation(): string
    {
        return $this->salutation;
    }

    public function setSalutation(string $salutation): void
    {
        $this->salutation = $salutation;
    }

    public function getFormalSalutation(): string
    {
        return $this->formalSalutation;
    }

    public function setFormalSalutation(string $formalSalutation): void
    {
        $this->formalSalutation = $formalSalutation;
    }

    public function getPronouns(): string
    {
        return $this->pronouns;
    }

    public function setPronouns(string $pronouns): void
    {
        $this->pronouns = $pronouns;
    }
}
