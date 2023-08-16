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

use Eguana\WorkBoard\Model\Board;
use Eguana\WorkBoard\Model\ResourceModel\Board\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface as ResponseInterfaceAlias;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface as ResultInterfaceAlias;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Psr\Log\LoggerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;

/**
 * This class is used for Mass delete
 * Class MassDelete
 */
class MassDelete extends Action
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UrlPersistInterface
     */
    private UrlPersistInterface $urlPersist;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var UrlRewriteCollectionFactory
     */
    private $urlRewriteCollection;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * MassDelete constructor.
     *
     * @param Context $context
     * @param UrlRewriteCollectionFactory $urlRewriteCollection
     * @param StoreManagerInterface $storeManager
     * @param UrlPersistInterface $urlPersist
     * @param Filter $filter
     * @param LoggerInterface $logger
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        UrlRewriteCollectionFactory $urlRewriteCollection,
        StoreManagerInterface $storeManager,
        UrlPersistInterface $urlPersist,
        Filter $filter,
        LoggerInterface $logger,
        CollectionFactory $collectionFactory
    ) {
        $this->logger = $logger;
        $this->urlPersist  = $urlPersist;
        $this->urlRewriteCollection = $urlRewriteCollection;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Execute action to delete Board
     *
     * @return Redirect|ResponseInterfaceAlias|ResultInterfaceAlias
     */
    public function execute()
    {
        $resultRedirect = '';
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();

            foreach ($collection as $board) {
                $board->delete();

                $this->urlPersist->deleteByData([
                    UrlRewrite::ENTITY_ID => $board->getId(),
                    UrlRewrite::ENTITY_TYPE => 'custom',
                    UrlRewrite::REDIRECT_TYPE => 0,
                    UrlRewrite::TARGET_PATH => 'board/index/detail/board_id/' . $board->getId()
                ]);
            }
            $this->messageManager->addSuccessMessage(
                __('A total of %1 board(s) have been deleted.', $collectionSize)
            );
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $resultRedirect->setPath('*/*/');
    }
}
