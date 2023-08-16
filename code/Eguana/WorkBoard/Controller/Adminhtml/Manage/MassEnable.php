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

use Eguana\WorkBoard\Model\ResourceModel\Board\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * To enable multiple Board
 *
 * Class MassEnable
 */
class MassEnable extends Action implements HttpPostActionInterface
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter               = $filter;
        $this->collectionFactory    = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action to enable Board
     *
     * @return Redirect|ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = '';
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());

            foreach ($collection as $item) {
                $item->setIsActive(true);
                $item->save();
            }

            $this->messageManager->addSuccessMessage(
                __('A total of %1 Board(s) have been enabled.', $collection->getSize())
            );

            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
        }
        return $resultRedirect->setPath('*/*/');
    }
}
