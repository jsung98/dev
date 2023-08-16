<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 3/12/21
 * Time: 2:40 PM
 */
namespace Eguana\Contactus\Controller\Adminhtml\Index;

use Eguana\Contactus\Api\ContactusRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Eguana\Contactus\Model\ContactusFactory;
use Magento\Framework\App\RequestInterface;

/**
 * Action for edit button
 *
 * Class Edit
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Eguana_Contactus::contactus';

    protected PageFactory $resultPageFactory;

    protected ForwardFactory $resultForwardFactory;

    protected $resultRedirectFactory;

    protected DataPersistorInterface $dataPersistor;

    protected ContactusRepositoryInterface $contactusRepository;

    protected ContactusFactory $contactusFactory;

    private RequestInterface $request;

    /**
     * Constructor
     *
     * @param PageFactory $pageFactory
     * @param ForwardFactory $forwardFactory
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ContactusRepositoryInterface $contactusRepository
     * @param ContactusFactory $contactusFactory
     * @param RequestInterface $request
     */
    public function __construct(
        PageFactory $pageFactory,
        ForwardFactory $forwardFactory,
        Context $context,
        RedirectFactory $redirectFactory,
        DataPersistorInterface $dataPersistor,
        ContactusRepositoryInterface $contactusRepository,
        ContactusFactory $contactusFactory,
        RequestInterface $request
    ) {
        parent::__construct($context);
        $this->resultPageFactory    = $pageFactory;
        $this->resultForwardFactory = $forwardFactory;
        $this->resultRedirectFactory = $redirectFactory;
        $this->dataPersistor = $dataPersistor;
        $this->contactusRepository = $contactusRepository;
        $this->contactusFactory = $contactusFactory;
        $this->request = $request;
    }

    /**
     * Edit Contact
     * @return Page|Redirect
     */
    public function execute()
    {
        $ContactId=$this->request->getParam("contact_id");
        $contact=$this->contactusRepository->getById($ContactId);
        if (!$contact->getContactId()) {
            $resultRedirect=$this->resultRedirectFactory->create();
            $resultRedirect->setPath("*/*/index");
            return $resultRedirect;
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Eguana_Contactus::Contactus');
        $resultPage->addBreadcrumb(__('Edit Contactus'), __('Edit Contactus'));
        $resultPage->getConfig()->getTitle()->prepend(__($contact->getName()));
        return $resultPage;
    }
}
