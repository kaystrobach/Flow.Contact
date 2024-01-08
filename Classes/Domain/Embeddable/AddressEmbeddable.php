<?php

declare(strict_types=1);

namespace KayStrobach\Contact\Domain\Embeddable;

use KayStrobach\Contact\Domain\Model\User;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Neos\Party\Domain\Model\PersonName;

/**
 * @ORM\Embeddable()
 */
class AddressEmbeddable
{
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected string $combinedAddress = '';

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255},validationGroups={"KontaktAnschrift"})
     */
    protected string $street = '';

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255},validationGroups={"KontaktAnschrift"})
     */
    protected string $houseNumber = '';

    /**
     * @var string
     * @Flow\Validate(type="StringLength", options={ "minimum"=0, "maximum"=255})
     */
    protected string $addressAddon = '';

    /**
     * @var string
     * @Flow\Validate(type="String")
     */
    protected string $roomNumber = '';

    /**
     * @var string
     * @Flow\Validate(type="Number")
     * @Flow\Validate(type="StringLength", options={ "minimum"=5, "maximum"=5},validationGroups={"KontaktAnschrift"})
     */
    protected string $zipCode = '';

    /**
     * @var string
     */
    protected string $city = '';

    /**
     * @Flow\Validate(type="String")
     * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255},validationGroups={"KontaktAnschrift"})
     * @var string
     */
    protected $country = '';

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param string $houseNumber
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @return string
     */
    public function getAddressAddon()
    {
        return $this->addressAddon;
    }

    /**
     * @param string $addressAddon
     */
    public function setAddressAddon($addressAddon)
    {
        $this->addressAddon = $addressAddon;
    }

    /**
     * @return string
     */
    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    /**
     * @param string $roomNumber
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @ORM\PrePersist()
     * @return void
     */
    public function updateCombinedAdress(PersonName $personname): void
    {
        if ($this->getStreet() === '') {
            return;
        }
        $this->combinedAddress = implode(
            PHP_EOL,
            [
                $personname->getFullName(),
                $this->street . ' ' . $this->houseNumber,
                $this->roomNumber,
                $this->addressAddon,
                $this->zipCode . ' ' . $this->city . ' - ' . $this->country
            ]
        );
    }
}
