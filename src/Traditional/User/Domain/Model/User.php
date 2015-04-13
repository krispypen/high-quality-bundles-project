<?php

namespace Traditional\User\Domain\Model;

use Assert\Assertion;
use Doctrine\ORM\Mapping as ORM;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;
use Symfony\Component\Intl\Intl;
use Traditional\User\Domain\Event\UserRegistered;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="traditional_user")
 * @Serializer\ExclusionPolicy("ALL")
 */
class User implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="NONE")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @ORM\Column(type="email", length=255)
     * @Serializer\Expose
     * @Serializer\Type("string")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose
     */
    private $country;

    private function __construct($id, Email $email, $password, $country)
    {
        $this->id = $id;
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setCountry($country);
    }

    public static function register($id, Email $email, $password, $country)
    {
        $user = new self($id, $email, $password, $country);

        $user->record(new UserRegistered($id));

        return $user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    private function setEmail(Email $email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    private function setPassword($password)
    {
        Assertion::notBlank($password);
        $this->password = $password;
    }

    public function getCountry()
    {
        return $this->country;
    }

    private function setCountry($country)
    {
        Assertion::notNull(Intl::getRegionBundle()->getCountryName($country), 'Invalid country code');
        $this->country = $country;
    }
}
