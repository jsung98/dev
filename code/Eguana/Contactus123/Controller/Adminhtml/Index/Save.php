<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 8/12/21
 * Time: 6:08 PM
 */
namespace Eguana\Contactus\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Eguana\Contactus\Api\Data\ContactusInterfaceFactory;
use Eguana\Contactus\Api\ContactusRepositoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

/**
 * To Save the form data
 * Class Save
 */
class Save extends Action implements HttpPostActionInterface
{

    private ContactusInterfaceFactory $contactusFactory;

    private ContactusRepositoryInterface $contactusRepository;

    private DataPersistorInterface $dataPersistor;

    private LoggerInterface $logger;

    private RedirectFactory $redirectFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param ContactusInterfaceFactory $contactusFactory
     * @param ContactusRepositoryInterface $contactusRepository
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        Context $context,
        ContactusInterfaceFactory $contactusFactory,
        ContactusRepositoryInterface $contactusRepository,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger,
        RedirectFactory $redirectFactory
    ) {
        parent::__construct($context);
        $this->contactusFactory = $contactusFactory;
        $this->contactusRepository = $contactusRepository;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();
        $previousRecord = $this->contactusFactory->create()->load($data['contact_id']);
        $record = $this->contactusFactory->create();
        $record->adddata($data);
        try {
            $this->contactusRepository->save($record);
            $this->messageManager->addSuccessMessage(__('Status has been updated '));
        } catch (\Exception $ex) {
            $this->logger->critical($ex);
            $this->messageManager->addErrorMessage(
                __('Your Data has not been Saved')
            );
        }
        $resultRedirect = $this->redirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}
