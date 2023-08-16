<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:05 PM
 */
namespace Eguana\Download\Api;

use Eguana\Download\Api\Data\DownloadInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Declared inter
 * interface DownloadBrochureRepositoryInterface
 */
interface DownloadRepositoryInterface
{
    /**
     * Save DownloadBrochure.
     *
     * @param DownloadInterface $download
     * @return DownloadInterface
     */
    public function save(DownloadInterface $download): DownloadInterface;

    /**
     * Retrieve DownloadBrochure.
     *
     * @param int $downloadId
     * @return DownloadInterface
     */
    public function getById(int $downloadId): DownloadInterface;

    /**
     * Retrieve list matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete DownloadBrochure.
     *
     * @param DownloadInterface $download
     * @return bool true on success
     */
    public function delete(DownloadInterface $download): bool;

    /**
     * Delete $downloadId by ID.
     *
     * @param $downloadId
     * @return bool true on success
     */
    public function deleteById($downloadId): bool;
}
