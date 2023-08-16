<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 3:08 PM
 */
namespace Eguana\Contactus\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Eguana\Contactus\Model\ContactusFactory;
use Magento\Framework\Controller\Result\Redirect;
use Psr\Log\LoggerInterface;

/**
 * Class Delete Action
 */
class Delete extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Eguana_Contactus::contactus';

    protected ContactusFactory $ContactFactory;

    private LoggerInterface $logger;

    /**
     * @param Context $context
     * @param ContactusFactory $ContactFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ContactusFactory $ContactFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->ContactFactory = $ContactFactory;
        $this->logger = $logger;
    }

    /**
     * Delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        $contactId = $this->getRequest()->getParam('contact_id');

        try {
            $model = $this->ContactFactory->create();
            $model->getById($contactId)->delete();
            $this->messageManager->addSuccessMessage(__('Delete contact success.'));
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred')
            );
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
