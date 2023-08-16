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

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Eguana\WorkBoard\Controller\Adminhtml\AbstractController;
use Eguana\WorkBoard\Model\Board;
use Eguana\WorkBoard\Model\BoardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect as RedirectAlias;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface as ResultInterfaceAlias;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteFactory as ResourceUrlRewriteFactory;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Psr\Log\LoggerInterface;

/**
 * Action for save button
 *
 * Class Save
 */
class Save extends AbstractController implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var BoardFactory
     */
    private $boardFactory;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;

    /**
     * @var ResourceUrlRewriteFactory
     */
    private $resourceUrlRewriteFactory;

    /**
     * @var UrlPersistInterface
     */
    private $urlPersist;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Board
     */
    private $boardModel;

    /**
     * @var UrlRewriteCollectionFactory
     */
    private $urlRewriteCollection;

    /**
     * Save constructor.
     * @param Context $context
     * @param Board $boardModel
     * @param UrlRewriteCollectionFactory $urlRewriteCollection
     * @param PageFactory $resultPageFactory
     * @param DataPersistorInterface $dataPersistor
     * @param BoardFactory $boardFactory
     * @param BoardRepositoryInterface $boardRepository
     * @param TimezoneInterface $timezone
     * @param UrlPersistInterface $urlPersist
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param LoggerInterface $logger
     * @param ResourceUrlRewriteFactory $resourceUrlRewriteFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Board $boardModel,
        UrlRewriteCollectionFactory $urlRewriteCollection,
        PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor,
        BoardFactory $boardFactory,
        BoardRepositoryInterface $boardRepository,
        UrlPersistInterface $urlPersist,
        UrlRewriteFactory $urlRewriteFactory,
        LoggerInterface $logger,
        ResourceUrlRewriteFactory $resourceUrlRewriteFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->urlRewriteCollection = $urlRewriteCollection;
        $this->boardModel = $boardModel;
        $this->boardFactory = $boardFactory;
        $this->boardRepository = $boardRepository;
        $this->resourceUrlRewriteFactory = $resourceUrlRewriteFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->logger = $logger;
        $this->urlPersist = $urlPersist;
        $this->storeManager = $storeManager;
        parent::__construct($context, $resultPageFactory);
    }

    /**
     * Save Board action
     *
     * @return RedirectAlias|ResponseInterface|ResultInterfaceAlias|mixed
     */
    public function execute()
    {
        $model = '';
        /** @var RedirectAlias $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $generalData = $data;
            if (isset($generalData['is_active']) && $generalData['is_active'] === '1') {
                $generalData['is_active'] = 1;
            }
            if (empty($generalData['board_id'])) {
                $generalData['board_id'] = null;
            }
            $id = $generalData['board_id'];
            $model = $this->boardFactory->create();

            if ($id) {
                try {
                    $model = $this->boardRepository->getById($id);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('This board no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }

                if (isset($model) && $generalData['identifier']) {
                    $urlKeyCheck = $generalData['identifier'];
                    $storeDiff = array_diff($generalData['store_id'], $model['store_id']);
                    $urlcollection = $this->urlRewriteCollection->create();
                    $urlcollection->addFieldToFilter('request_path', ['eq' => $urlKeyCheck]);
                    if ($model->getIdentifier() != $generalData['identifier']) {
                        $collection = $urlcollection->addStoreFilter($generalData['store_id']);
                    } else {
                        $collection = $urlcollection->addStoreFilter($storeDiff);
                    }
                    if (count($collection) > 0) {
                        $this->messageManager->addErrorMessage(
                            __('The value specified in the URL Key is already exists.
                        Please use a unique identifier key')
                        );
                        return $resultRedirect->setPath('*/*/edit', [
                            'board_id' => $id
                        ]);
                    }
                }
            } else {
                $urlKeyCheck = $generalData['identifier'];
                if (empty($urlKeyCheck)) {
                    $urlKeyCheck = str_replace(" ", "-", strtolower($generalData['title']));
                }
                $generalData['identifier'] = $urlKeyCheck;
                $urlcollection = $this->urlRewriteCollection->create();
                $urlcollection->addFieldToFilter('request_path', ['eq' => $urlKeyCheck]);
                $collection = $urlcollection->addStoreFilter($generalData['store_id']);
                if (count($collection) > 0) {
                    $this->messageManager->addErrorMessage(
                        __('The value specified in the URL Key is already exists.
                        Please use a unique identifier key')
                    );
                    $this->dataPersistor->set('board_add_form', $data);
                    return $resultRedirect->setPath('*/*/new');
                }
            }

            if (!empty($generalData['category']) && isset($generalData['category'])) {
                $category= implode(',', $generalData['category']);
                } else {
                $category = 0;
                }
            $generalData['category'] = $category;

            if (strpos($generalData['thumbnail_image'][0]['url'], 'WorkBoard/') !== false) {
                if (isset($generalData['thumbnail_image'])) {
                    if (isset($generalData['thumbnail_image'][0]['file'])) {
                        $generalData['thumbnail_image'] = 'WorkBoard/' .
                            $generalData['thumbnail_image'][0]['file'];
                    } else {
                        $imageName = (explode("/media/", $generalData['thumbnail_image'][0]['url']));
                        $generalData['thumbnail_image'] = $imageName[1];
                    }
                }
            } else {
                $imageName = (explode("/media/", $generalData['thumbnail_image'][0]['url']));
                $generalData['thumbnail_image'] = $imageName[1];
            }

            if (!empty($generalData['mobile_thumbnail_image'])) {
                if (strpos($generalData['mobile_thumbnail_image'][0]['url'], 'WorkBoard/') !== false) {
                    if (isset($generalData['mobile_thumbnail_image'])) {
                        if (isset($generalData['mobile_thumbnail_image'][0]['file'])) {
                            $generalData['mobile_thumbnail_image'] = 'WorkBoard/' .
                                $generalData['mobile_thumbnail_image'][0]['file'];
                        } else {
                            $imageName = (explode("/media/", $generalData['mobile_thumbnail_image'][0]['url']));
                            $generalData['mobile_thumbnail_image'] = $imageName[1];
                        }
                    }
                } else {
                    if (isset($generalData['thumbnail_image_phone'])) {
                        $imageName = (explode("/media/", $generalData['thumbnail_image_phone'][0]['url']));
                        $generalData['thumbnail_image_phone'] = $imageName[1];
                    }
                }
            }
            $model->setData($generalData);
            try {
                $urlKey = $generalData['identifier'];
                if (empty($urlKey)) {
                    $urlKey = str_replace(" ", "-", strtolower($model->getTitle()));
                }
                if (!$this->isValidIdentifier($urlKey)) {
                    if (!isset($generalData['board_id'])) {
                        $this->messageManager
                            ->addErrorMessage(__(
                                "The Board URL key can't use capital letters or disallowed symbols. "
                                . "Remove the letters and symbols and try again."
                            ));
                        $this->dataPersistor->set('board_add_form', $generalData);
                        return $resultRedirect
                            ->setPath('*/*/new');
                    } else {
                        $this->messageManager
                            ->addErrorMessage(__(
                                "The Board URL key can't use capital letters or disallowed symbols. "
                                . "Remove the letters and symbols and try again."
                            ));
                        return $resultRedirect->setPath(
                            '*/*/edit',
                            [
                                'board_id' => $generalData['board_id'],
                                '_current' => true
                            ]
                        );
                    }
                }

                if ($this->isNumericIdentifier($urlKey)) {
                    if (!isset($generalData['board_id'])) {
                        $this->messageManager
                            ->addErrorMessage(__(
                                "The Board URL key can't use only numbers. Add letters or words and try again."
                            ));
                        $this->dataPersistor->set('board_add_form', $generalData);
                        return $resultRedirect
                            ->setPath('*/*/new');
                    } else {
                        $this->messageManager
                            ->addErrorMessage(__(
                                "The board URL key can't use only numbers. Add letters or words and try again."
                            ));
                        return $resultRedirect->setPath(
                            '*/*/edit',
                            [
                                'board_id' => $generalData['board_id'],
                                '_current' => true
                            ]
                        );
                    }
                }

                $model->setIdentifier($urlKey);
                $model->setUpdateTime('');
                $this->boardRepository->save($model);
                $generalData['identifier'] = $urlKey;
                $this->saveUrlRewrite($generalData, $model);
                $this->messageManager->addSuccessMessage(__('You saved the Board.'));
                return $this->processResultRedirect($model, $resultRedirect, $data);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the Board.')
                );
            }
            $this->dataPersistor->set('board_add_form', $data);
            return $this->processResultRedirect($model, $resultRedirect, $data);
        }
        return $this->processResultRedirect($model, $resultRedirect, $data);
    }

    /**
     * If categories count is less than store ids
     *
     * @param array|mixed $storeIds
     * @param mixed $categories
     * @param mixed $generalData
     * @param RedirectAlias|mixed $resultRedirect
     * @return void
     */
    private function checkCountStoreIds($storeIds, $categories, $generalData, $resultRedirect)
    {
        foreach ($storeIds as $storeId) {
            $index = 1;
            foreach ($categories as $category) {
                $noOfCategories = count($categories);
                $categoryId = explode('.', $category);
                if ($categoryId[0] == $storeId) {
                    break;
                }
                $storename = '';
                try {
                    $storename = $this->storeManager->getStore($storeId)->getName();
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
                if ($noOfCategories == $index) {
                    if (!isset($generalData['board_id'])) {
                        $this->messageManager
                            ->addErrorMessage(__(
                                'category is not selected against ' .
                                $storename
                            ));
                        $this->dataPersistor->set('board_add_form', $generalData);
                        return $resultRedirect
                            ->setPath('*/*/new');
                    } else {
                        $this->messageManager
                            ->addErrorMessage(__(
                                'category is not selected against ' .
                                $storename
                            ));
                        return $resultRedirect->setPath(
                            '*/*/edit',
                            [
                                'board_id' => $generalData['board_id'],
                                '_current' => true
                            ]
                        );
                    }
                }
                $index++;
            }
        }
    }

    /**
     * Process result redirect
     *
     * @param string|Board|BoardInterface $model
     * @param RedirectAlias $resultRedirect
     * @param array $data
     * @return mixed
     */
    private function processResultRedirect($model, $resultRedirect, $data)
    {
        if ($this->getRequest()->getParam('back', false) === 'duplicate') {
            $newBoard = $this->boardFactory->create(['data' => $data]);
            $newBoard->setId(null);
            $identifier = $model->getIdentifier() . '-' . uniqid();
            $newBoard->setIdentifier($identifier);
            $newBoard->setIsActive(false);
            $newBoard->setThumbnailImage($model->getThumbnailImage());
            $newBoard->setMobileThumbnailImage($model->getMobileThumbnailImage());

            $this->boardRepository->save($newBoard);
            $newData = [
                'store_id'      => $data['store_id'],
                'identifier'    => $identifier
            ];
            $this->saveUrlRewrite($newData, $newBoard);
            $this->messageManager->addSuccessMessage(__('You duplicated the Board.'));
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'board_id' => $newBoard->getId(),
                    '_current' => true
                ]
            );
        }
        $this->dataPersistor->clear('board_add_form');
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath(
                '*/*/edit',
                ['board_id' => $model->getId(), '_current' => true]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * This method is used to change the date format
     *
     * @param string|mixed $date
     * @return string
     */
  /*  private function changeDateFormat($date)
    {
        $dateTime = '';
        try {
            $dateTime = $this->timezone->date($date)->format(self::DATE_FORMAT);
            return $dateTime;
        } catch (\Exception $exception) {
            $this->logger->debug($exception->getMessage());
        }
        return $dateTime;
    }

    /**
     *  Check whether Board identifier is numeric
     *
     * @param string|mixed $urlKey
     * @return false|int
     */
    private function isNumericIdentifier($urlKey)
    {
        return preg_match('/^[0-9]+$/', $urlKey);
    }

    /**
     *  Check whether Board identifier is valid
     *
     * @param string|mixed $urlKey
     * @return false|int
     */
    private function isValidIdentifier($urlKey)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $urlKey);
    }

    /**
     * Save URL rewrites
     *
     * @param array $data
     * @param Board $model
     */
    private function saveUrlRewrite($data, $model)
    {
        try {
            $urlKey = $data['identifier'];
            if ($data['store_id'][0] == 0) {
                $storeManagerDataList = $this->storeManager->getStores();
                $data['store_id'] = [];
                foreach ($storeManagerDataList as $key => $value) {
                    $data['store_id'][] = $key;
                }
            }
            $this->urlPersist->deleteByData([
                UrlRewrite::ENTITY_ID => $model->getId(),
                UrlRewrite::ENTITY_TYPE => 'custom',
                UrlRewrite::REDIRECT_TYPE => 0,
                UrlRewrite::TARGET_PATH => 'board/index/detail/board_id/' . $model->getId()
            ]);
            //Save seo url against each store selected
            foreach ($data['store_id'] as $storeId) {

                /** @var \Magento\UrlRewrite\Model\UrlRewrite */
                $urlRewriteModel = $this->urlRewriteFactory->create();
                /** @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewrite */
                $resourceUrlRewriteModel = $this->resourceUrlRewriteFactory->create();

                /* this url is not created by system so set as 0 */
                $urlRewriteModel->setIsSystem(0);
                /* unique identifier - set random unique value to id path */
                $urlRewriteModel->setIdPath($model->getIdentifier() . '-' . uniqid());
                $urlRewriteModel->setEntityType('custom')
                    ->setRequestPath($urlKey)
                    ->setTargetPath('board/index/detail/board_id/' . $model->getId())
                    ->setRedirectType(0)
                    ->setStoreId($storeId)
                    ->setEntityId($model->getId())
                    ->setDescription($model->getMetaDescription());
                $resourceUrlRewriteModel->save($urlRewriteModel);
            }
        } catch (\Exception $e) {
            $this->logger->info('Board url rewrite saving issue:' . $e->getMessage());
        }
    }
}
