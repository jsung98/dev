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

use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Eguana\WorkBoard\Controller\Adminhtml\AbstractController;
use Eguana\WorkBoard\Model\BoardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface as ResponseInterfaceAlias;
use Magento\Framework\Controller\Result\Redirect as RedirectAlias;
use Magento\Framework\Controller\ResultInterface as ResultInterfaceAlias;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * This class is used to delete the Board record
 *
 * Class Delete
 */
class Delete extends AbstractController
{
    /**
     * @var BoardFactory
     */
    private $boardFactory;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @var UrlRewriteCollectionFactory
     */
    private $urlRewriteCollection;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlPersistInterface
     */
    private $urlPersist;

    /**
     * @param Context $context
     * @param UrlRewriteCollectionFactory $urlRewriteCollection
     * @param PageFactory $resultPageFactory
     * @param UrlPersistInterface $urlPersist
     * @param BoardFactory $boardFactory
     * @param StoreManagerInterface $storeManager
     * @param BoardRepositoryInterface $boardRepository
     */
    public function __construct(
        Context $context,
        UrlRewriteCollectionFactory $urlRewriteCollection,
        PageFactory $resultPageFactory,
        UrlPersistInterface $urlPersist,
        BoardFactory $boardFactory,
        StoreManagerInterface $storeManager,
        BoardRepositoryInterface $boardRepository
    ) {
        $this->boardFactory = $boardFactory;
        $this->urlPersist       = $urlPersist;
        $this->urlRewriteCollection = $urlRewriteCollection;
        $this->boardRepository = $boardRepository;
        $this->storeManager = $storeManager;
        parent::__construct($context, $resultPageFactory);
    }

    /**
     * Execute the delete action
     *
     * @return ResponseInterfaceAlias|RedirectAlias|ResultInterfaceAlias
     */

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int)$this->getRequest()->getParam('board_id');
        if ($id) {
            try {
                /** @var WorkBoard $model */
                $model = $this->boardRepository->getById($id);
                $model->delete();

                $this->urlPersist->deleteByData([
                    UrlRewrite::ENTITY_ID => $model->getId(),
                    UrlRewrite::ENTITY_TYPE => 'custom',
                    UrlRewrite::REDIRECT_TYPE => 0,
                    UrlRewrite::TARGET_PATH => 'board/index/detail/board_id/' . $model->getId()
                ]);

                $this->messageManager->addSuccessMessage(__('Board was successfully deleted'));
                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                return $resultRedirect->setPath('*/*/index');
            }
        }
        $this->messageManager->addErrorMessage(__('Board could not be deleted'));
        return $resultRedirect->setPath('*/*/index');
    }
}
