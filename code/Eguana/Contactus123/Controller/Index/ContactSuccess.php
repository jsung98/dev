<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eguana\Contactus\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\FileSystemException;
use Eguana\Contactus\Model\Config;

/**
 * Contact Success controller
 */
class ContactSuccess extends \Magento\Framework\App\Action\Action
{
    private $downloader;
    private $directory;
    private $config;

    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        DirectoryList $directory,
        Config $config
    ){
        $this->downloader = $fileFactory;
        $this->directory = $directory;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Show Success Page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $fileName = $this->config->isUpload();;
        $filePath = '';

        try {
            $filePath = $this->directory->getPath("media") . '/UploadDir/' . $fileName;
        } catch (FileSystemException $e) {
            $this->logger->info($e->getMessage());
        }
        try {
            return $this->downloader->create($fileName, [
                'type' => 'filename',
                'value' => $filePath,
            ],
                \Magento\Framework\App\Filesystem\DirectoryList::MEDIA,
                'application/octet-stream');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
       // return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
