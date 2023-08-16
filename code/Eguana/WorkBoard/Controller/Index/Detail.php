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

namespace Eguana\WorkBoard\Controller\Index;

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Result\Page;
use Magento\Framework\App\RequestInterface;
use Eguana\WorkBoard\Model\BoardConfiguration\BoardConfiguration;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * This controller will display black page
 *
 * Class Deatil
 */
class Detail implements ActionInterface
{

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var BoardConfiguration
     */
    private $boardConfiguration;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * Detail constructor.
     * @param Context $context
     * @param BoardRepositoryInterface $boardRepository
     * @param BoardConfiguration $boardConfiguration
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param PageFactory $pageFactory
     * @param DateTime $date
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        BoardRepositoryInterface $boardRepository,
        BoardConfiguration $boardConfiguration,
        RedirectFactory $redirectFactory,
        RequestInterface $request,
        PageFactory $pageFactory,
        DateTime $date,
        LoggerInterface $logger
    ) {
        $this->pageFactory = $pageFactory;
        $this->boardRepository = $boardRepository;
        $this->request = $request;
        $this->date = $date;
        $this->redirectFactory = $redirectFactory;
        $this->logger = $logger;
        $this->boardConfiguration = $boardConfiguration;
    }

    /**
     * To create blank page, execute method will be called
     *
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */

    public function execute()
    {
        if ($this->getEnableValue() == 0) {
            return $this->redirectFactory->create()->setPath('/');
        }
        $boardId = (int)$this->request->getParam('board_id');
        if (!$boardId) {
            return $this->redirectFactory->create()->setPath('board/');
        } else {
            $active = $this->getBoardIsActive();
            if ($active == 0) {
                return $this->redirectFactory->create()->setPath('board/');
            }
        }
        return $this->pageFactory->create();
    }

    /**
     * Get Enable value of module from configuration
     *
     * @return mixed
     */
    public function getEnableValue()
    {
        return $this->boardConfiguration->getConfigValue('enabled');
    }
    /**
     * Get Enable value of notice using repository
     *
     * @return BoardInterface|string
     */
    public function getBoardIsActive()
    {
        $isActive = 0;
        try {
            $board = $this->boardRepository->getById($this->request->getParam('board_id'));
            $isActive = $board['is_active'];
            if ($isActive == 1) {
                if ($board['date'] < $this->date->gmtDate() || $board['date'] == $this->date->gmtDate()) {
                    $isActive = 1;
                } else {
                    $isActive = 0;
                }
            }
            return $isActive;
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
        return $isActive;
    }
}
