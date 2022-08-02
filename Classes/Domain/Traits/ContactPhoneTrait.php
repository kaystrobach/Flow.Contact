<?php

namespace KayStrobach\Contact\Domain\Traits;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

trait ContactPhoneTrait
{
    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="StringLength", options={ "minimum"=5, "maximum"=255 })
     */
    protected $phone = '';

    /**
     * @var string
     * @Flow\Validate(type="String")
     */
    protected $mobile = '';

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }
}
