<?php

declare(strict_types=1);

namespace KayStrobach\Contact\Domain\Embeddable;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class PhoneEmbeddable
{
    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="StringLength", options={ "minimum"=5, "maximum"=255 })
     */
    protected string $landline = '';

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="StringLength", options={ "minimum"=5, "maximum"=255 })
     */
    protected string $mobile = '';

    /**
     * @return string
     */
    public function getLandline(): string
    {
        return $this->landline;
    }

    /**
     * @param string $phone
     */
    public function setLandline(string $landline)
    {
        $this->landline = $landline;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
    }
}
