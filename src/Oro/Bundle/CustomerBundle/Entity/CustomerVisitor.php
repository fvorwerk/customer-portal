<?php

namespace Oro\Bundle\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

use Oro\Bundle\CustomerBundle\Model\ExtendCustomerVisitor;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Table(
 *     name="oro_customer_visitor",
 *     indexes={@Index(name="id_session_id_idx", columns={"id", "session_id"})}
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @Config
 */
class CustomerVisitor extends ExtendCustomerVisitor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_visit", type="datetime")
     */
    private $lastVisit;

    /**
     * @var string
     *
     * @ORM\Column(name="session_id", type="string", length=255, nullable=false)
     */
    private $sessionId;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->lastVisit = new \DateTime('now', new \DateTimeZone('UTC'));
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * @param \DateTime $lastVisit
     *
     * @return CustomerVisitor
     */
    public function setLastVisit($lastVisit)
    {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return CustomerVisitor
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->sessionId = bin2hex(random_bytes(10));
    }
}
