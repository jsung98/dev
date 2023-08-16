<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 30/11/21
 * Time: 3:24 PM
 */
namespace Eguana\Contactus\Model;

use Magento\Framework\Model\AbstractModel;
use Eguana\Contactus\Model\ResourceModel\Contactus as ResourceModel;
use Eguana\Contactus\Api\Data\ContactusInterface;

class Contactus extends AbstractModel implements ContactusInterface
{

    /**
     * @var string
     */
    protected $_cacheTag = 'eguana_contact';

    /**
     * @var string
     */
    protected $_eventPrefix = 'eguana_contact';

    /**
     * Initialize resource model
     *
     * @return void
     */

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Get ContactId
     * @return int|null
     */
    public function getContactId(): ?int
    {
        return $this->getData(ContactusInterface::CONTACT_ID);
    }

    /**
     * Set Contact ID
     *
     * @param int $contact_id
     * @return ContactusInterface
     */
    public function setContactId(int $contact_id): ContactusInterface
    {
        return $this->setData(ContactusInterface::CONTACT_ID, $contact_id);
    }

    /**
     * Retrieve Name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(ContactusInterface::NAME);
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return ContactusInterface
     */
    public function setName(string $name): ContactusInterface
    {
        return $this->setData(ContactusInterface::NAME, $name);
    }

    /**
     * Retrieve Email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getData(ContactusInterface::EMAIL);
    }

    /**
     * Set Email
     *
     * @param string $email
     * @return ContactusInterface
     */
    public function setEmail(string $email): ContactusInterface
    {
        return $this->setData(ContactusInterface::EMAIL, $email);
    }

    /**
     * Retrieve Telephone
     *
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->getData(ContactusInterface::TELEPHONE);
    }

    /**
     * Set Email
     *
     * @param string $telephone
     * @return ContactusInterface
     */
    public function setTelephone(string $telephone): ContactusInterface
    {
        return $this->setData(ContactusInterface::EMAIL, $telephone);
    }

    /**
     * Retrieve companyname
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->getData(ContactusInterface::COMPANYNAME);
    }

    /**
     * Set companyname
     *
     * @param string $companyname
     * @return ContactusInterface
     */
    public function setCompanyName(string $companyname): ContactusInterface
    {
        return $this->setData(ContactusInterface::COMPANYNAME, $companyname);
    }
}
