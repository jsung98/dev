<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:05 PM
 */
namespace Eguana\Contactus\Api;

use Eguana\Contactus\Api\Data\ContactusInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Declared inter
 * interface ContactusRepositoryInterface
 */
interface ContactusRepositoryInterface
{
    /**
     * Save Contactus.
     *
     * @param ContactusInterface $contact
     * @return ContactusInterface
     */
    public function save(ContactusInterface $contact): ContactusInterface;

    /**
     * Retrieve Contactus.
     *
     * @param int $contactId
     * @return ContactusInterface
     */
    public function getById(int $contactId): ContactusInterface;

    /**
     * Retrieve list matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete contactus.
     *
     * @param ContactusInterface $contact
     * @return bool true on success
     */
    public function delete(ContactusInterface $contact): bool;

    /**
     * Delete $contact by ID.
     *
     * @param $contactId
     * @return bool true on success
     */
    public function deleteById($contactId): bool;
}
