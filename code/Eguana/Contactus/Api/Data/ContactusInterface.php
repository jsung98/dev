<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 11:51 AM
 */

namespace Eguana\Contactus\Api\Data;

/**
 * Interface class having getter\setter
 * interface ContactInterface
 */
interface ContactusInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const CONTACT_ID = 'contact_id';
    const NAME = 'name';
    const TYPE = 'type';
    const STATUS = 'status';
    const EMAIL = 'email';
    const TELEPHONE = 'telephone';
    const COMMENT = 'comment';
    const COMPANYNAME='companyname';

    /**
     * Get Contact ID
     *
     * @return int|null
     */
    public function getContactId(): ?int;

    /**
     * Set Contact ID
     *
     * @param int $contact_Id
     * @return ContactusInterface
     */
    public function setContactId(int $contact_Id): ContactusInterface;

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
     * @return ContactusInterface
     */
    public function setName(string $name): ContactusInterface;

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set Status ID
     *
     * @param int $status
     * @return ContactusInterface
     */
    public function setStatus(int $status): ContactusInterface;

    /**
     * Get  Type
     *
     * @return int|null
     */
    public function getType(): ?int;

    /**
     * Set Type
     *
     * @param int $type
     * @return ContactusInterface
     */
    public function setType(int $type): ContactusInterface;

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
     * @return ContactusInterface
     */
    public function setEmail(string $email): ContactusInterface;
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
     * @return ContactusInterface
     */
    public function setTelephone(string $telephone): ContactusInterface;

    /**
     * Get  Message
     *
     * @return string|null
     */
    public function getComment(): ?string;

    /**
     * Set Message
     *
     * @param string $comment
     * @return ContactusInterface
     */
    public function setComment(string $comment): ContactusInterface;

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
     * @return ContactusInterface
     */
    public function setCompanyName(string $companyname): ContactusInterface;

}
