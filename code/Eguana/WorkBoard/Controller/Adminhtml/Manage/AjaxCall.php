<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2023 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Zaid
 * Date: 12/1/23
 * Time: 4:27 PM
 */

declare(strict_types=1);

namespace Eguana\WorkBoard\Controller\Adminhtml\Manage;

use Eguana\WorkBoard\Model\BoardConfiguration\BoardConfiguration;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;

/**
 * This class is used to show counter list according to the store view
 *
 * Class AjaxCall
 */
class AjaxCall implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var BoardConfiguration
     */
    private $boardConfiguration;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AjaxCall constructor.
     *
     * @param ResultFactory $resultFactory
     * @param Context $context
     * @param LoggerInterface $logger
     * @param BoardConfiguration $boardConfiguration
     * @param RequestInterface $request
     */
    public function __construct(
        ResultFactory $resultFactory,
        Context $context,
        LoggerInterface $logger,
        BoardConfiguration $boardConfiguration,
        RequestInterface $request
    ) {
        $this->resultFactory = $resultFactory;
        $this->context = $context;
        $this->logger = $logger;
        $this->BoardConfiguration = $boardConfiguration;
        $this->request = $request;
    }

    /**
     * This method is used to get the counter store list from store locator according to the store view selection
     *
     * @return ResponseInterface|Json|(Json&ResultInterface)|Redirect|(Redirect&ResultInterface)|ResultInterface|string
     */
    public function execute()
    {
        $resultJson = '';
        try {
            if ($this->request->isAjax()) {
                $storeId = $this->context->getRequest()->getParam('store_id');
                $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                $resultJson->setData(
                    [
                        "message" => "Category according to store view",
                        "suceess" => true,
                        "category" => $this->boardConfiguration->getCategoryValue('category', $storeId)
                    ]
                );
                return $resultJson;
            } elseif (!$this->request->isAjax()) {
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl('/');
                return $resultRedirect;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $resultJson;
    }
}
