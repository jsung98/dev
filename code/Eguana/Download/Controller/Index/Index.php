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

namespace Eguana\Download\Controller\Index;

use Eguana\Download\Model\Config;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * This controller will display black page
 *
 * Class Page
 */
class Index implements ActionInterface
{

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var BoardConfiguration
     */
    private $boardConfiguration;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Config
     */
    private Config $config;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param LoggerInterface $logger
     * @param RedirectFactory $redirectFactory
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Config $config,
        RedirectFactory $redirectFactory,
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->redirectFactory = $redirectFactory;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * To create blank page, execute method will be called
     *
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */

    public function execute()
    {
        if (!$this->getEnabled()) {
            return $this->redirectFactory->create()->setPath('/');
        }
        return $this->pageFactory->create();
    }

    /**
     * Get Enable value of module from configuration
     *
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->config->isEnabled();
    }
}
