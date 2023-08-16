<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 2:57 PM
 */
namespace Eguana\Download\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\AuthorizationInterface;

/**
 * Class Index Action
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Eguana_Download::download';

    protected PageFactory $resultPageFactory;

    protected AuthorizationInterface $authorization;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        AuthorizationInterface $authorization
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->authorization = $authorization;
    }

    /**
     * View execute
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Eguana_Download::download');
        $resultPage->getConfig()->getTitle()->prepend(__('Downloads'));
        return $resultPage;
    }
}
