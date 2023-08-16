<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eguana\Download\Controller\Index;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Eguana\Download\Model\Config;
use Eguana\Download\Model\DownloadRepository;
use Eguana\Download\Api\Data\DownloadInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Newsletter\Model\SubscriberFactory;
use Psr\Log\LoggerInterface;

/**
 * Download Success controller
 */
class DownloadSuccess extends \Magento\Framework\App\Action\Action
{
    private DirectoryList $directory;
    private Config $config;
    private DownloadRepository $downloadRepository;
    private DownloadInterface $downloadFactory;
    private LoggerInterface $logger;
    private SubscriberFactory $subscriberFactory;
    private FileFactory $fileFactory;
    private JsonFactory $resultJsonFactory;
    private StoreManagerInterface $storeManager;

    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param DirectoryList $directory
     * @param Config $config
     * @param DownloadRepository $downloadRepository
     * @param DownloadInterface $downloadFactory
     * @param SubscriberFactory $subscriberFactory
     * @param LoggerInterface $logger
     * @param JsonFactory $resultJsonFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context            $context,
        FileFactory        $fileFactory,
        JsonFactory        $resultJsonFactory,
        DirectoryList      $directory,
        Config             $config,
        DownloadRepository $downloadRepository,
        DownloadInterface  $downloadFactory,
        SubscriberFactory  $subscriberFactory,
        LoggerInterface    $logger,
        StoreManagerInterface $storeManager
    )
    {
        $this->fileFactory = $fileFactory;
        $this->directory = $directory;
        $this->config = $config;
        $this->downloadRepository = $downloadRepository;
        $this->downloadFactory = $downloadFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->logger = $logger;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     *Data save and newsletter and download function
     */
    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();
        if (!empty($postData)) {
            try {
                $downloadData = $this->downloadRepository->create();
                $downloadData->setData($postData);
                $downloadData->save();
                if (isset($postData['news_letter'])) {
                    $this->subscriberFactory->create()->subscribe($postData['email']);
                }
                $fileName = $this->config->isUpload();
                if(!empty($fileName)){
                    $filepath = $this->directory->getPath("media") . '/uploadDir/' . $fileName;
                    $url = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                    $relativePath = str_replace($this->directory->getPath("media"), "", $filepath);
                    $urlPath = $url . $relativePath;
                    $resultJson = $this->resultJsonFactory->create();
                    return $resultJson->setData([
                        'filepath' => $urlPath
                    ]);
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }
}