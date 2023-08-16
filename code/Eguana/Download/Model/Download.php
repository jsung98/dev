<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 30/11/21
 * Time: 3:24 PM
 */
namespace Eguana\Download\Model;

use Magento\Framework\Model\AbstractModel;
use Eguana\Download\Model\ResourceModel\Download as ResourceModel;
use Eguana\Download\Api\Data\DownloadInterface;

class Download extends AbstractModel implements DownloadInterface
{


    /**
     * @var string
     */
    protected $_cacheTag = 'eguana_download';

    /**
     * @var string
     */
    protected $_eventPrefix = 'eguana_download';

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
     * Get DownloadId
     * @return int|null
     */
    public function getDownloadId(): ?int
    {
        return $this->getData(DownloadInterface::DOWNLOAD_ID);
    }

    /**
     * Set Download ID
     *
     * @param int $download_id
     * @return DownloadInterface
     */
    public function setDownloadId(int $download_id): DownloadInterface
    {
        return $this->setData(DownloadInterface::DOWNLOAD_ID, $download_id);
    }

    /**
     * Retrieve Name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(DownloadInterface::NAME);
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return DownloadInterface
     */
    public function setName(string $name): DownloadInterface
    {
        return $this->setData(DownloadInterface::NAME, $name);
    }

    /**
     * Retrieve Email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getData(DownloadInterface::EMAIL);
    }

    /**
     * Set Email
     *
     * @param string $email
     * @return DownloadInterface
     */
    public function setEmail(string $email): DownloadInterface
    {
        return $this->setData(DownloadInterface::EMAIL, $email);
    }

    /**
     * Retrieve Telephone
     *
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->getData(DownloadInterface::TELEPHONE);
    }

    /**
     * Set Email
     *
     * @param string $telephone
     * @return DownloadInterface
     */
    public function setTelephone(string $telephone): DownloadInterface
    {
        return $this->setData(DownloadInterface::EMAIL, $telephone);
    }

    /**
     * Retrieve companyname
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->getData(DownloadInterface::COMPANYNAME);
    }

    /**
     * Set companyname
     *
     * @param string $companyname
     * @return DownloadInterface
     */
    public function setCompanyName(string $companyname): DownloadInterface
    {
        return $this->setData(DownloadInterface::COMPANYNAME, $companyname);
    }
}
