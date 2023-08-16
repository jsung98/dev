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

namespace Eguana\WorkBoard\Block\Index;

use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Eguana\WorkBoard\Model\Board;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Psr\Log\LoggerInterface;

/**
 * class WorkBoardDetails
 *
 * block for details.phtml
 */
class Detail extends Template implements IdentityInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @var RequestInterface
     */
    private $requestInterface;

    /**
     * Work constructor.
     *
     * @param Context $context
     * @param BoardRepositoryInterface $boardRepository
     * @param RequestInterface $requestInterface
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Context $context,
        BoardRepositoryInterface $boardRepository,
        RequestInterface $requestInterface,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->boardRepository = $boardRepository;
        $this->requestInterface = $requestInterface;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * Get Identities
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [Board::CACHE_TAG];
    }

    /**
     * Get Board Method
     *
     * @return Board
     */
    public function getBoard()
    {
        /** @var Board $board*/
        $id = $this->requestInterface->getParam('board_id');
        $board = "";
        try {
            $board = $this->boardRepository->getById($id);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $board;
    }

    /**
     * To set metatitle metakeyword and description
     *
     * @return $this|Detail
     */

    protected function _prepareLayout()
    {

        parent::_prepareLayout();
        if ($this->getRequest()->getParam('board_id')) {
            $boardData = $this->getBoard();
            $metaTitle = $boardData->getMetaTitle();
            $title = $metaTitle ? $metaTitle : $boardData->getTitle();
            $this->pageConfig->getTitle()->set(__($title));
            if (!empty($boardData->getData())) {
                $this->pageConfig->setMetaTitle(__($metaTitle));
                $this->pageConfig->setKeywords(__($boardData->getMetaKeywords()));
                $this->pageConfig->setDescription(__($boardData->getMetaDescription()));
            }
        }
        return $this;
    }
}
