<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 */

namespace Eguana\Download\Api\Data;

/**
 * Interface class having getter\setter
 * interface DownloadInterface
 */
interface DownloadInterface
{

    const DOWNLOAD_ID = 'download_id';
    const NAME = 'name';
    const EMAIL = 'email';
    const TELEPHONE = 'telephone';
    const COMPANYNAME = 'companyname';

    /**
     * Get Download ID
     *
     * @return int|null
     */
    public function getDownloadId(): ?int;

    /**
     * Set Download ID
     *
     * @param int $download_Id
     * @return DownloadInterface
     */
    public function setDownloadId(int $download_Id): DownloadInterface;

    /**
     * Get  Name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set Name
     *
     * @param string $name
     * @return DownloadInterface
     */
    public function setName(string $name): DownloadInterface;

    /**
     * Get  Email
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Set Email
     *
     * @param string $email
     * @return DownloadInterface
     */
    public function setEmail(string $email): DownloadInterface;
    /**
     * Get  Telephone
     *
     * @return string|null
     */
    public function getTelephone(): ?string;

    /**
     * Set Telephone
     *
     * @param string $telephone
     * @return DownloadInterface
     */
    public function setTelephone(string $telephone): DownloadInterface;

    /**
     * Get  companyname
     *
     * @return string|null
     */
    public function getCompanyName(): ?string;

    /**
     * Set Name
     *
     * @param string $companyname
     * @return DownloadInterface
     */
    public function setCompanyName(string $companyname): DownloadInterface;

}
