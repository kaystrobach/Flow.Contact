<?php
namespace KayStrobach\Contact\Domain\Model;

use KayStrobach\Contact\Domain\Traits\ContactTrait;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use TYPO3\Party\Domain\Model\PersonName;

/**
 * @Gedmo\Loggable(logEntryClass="KayStrobach\Logger\Domain\Model\LogEntry")
 * @Flow\Entity
 */
class Contact
{
    use ContactTrait;

    /**
     * @var PersonName
     * @ORM\Column(nullable=true)
     * @ORM\OneToOne(cascade={"all"})
     */
    protected $name;

    /**
     * @var string
     * @Flow\Validate(type="String")
     * @Gedmo\Versioned
     */
    protected $position = '';

    /**
     * @ORM\Column(nullable=true)
     * @ORM\OneToOne(cascade={"all"})
     * @var \KayStrobach\Contact\Domain\Model\Address
     */
    protected $address;

    /**
     * @return PersonName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param PersonName $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}
