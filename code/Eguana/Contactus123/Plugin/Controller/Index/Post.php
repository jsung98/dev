<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 6/12/21
 * Time: 12:47 PM
 */
namespace Eguana\Contactus\Plugin\Controller\Index;

use Eguana\Contactus\Model\ContactusFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Store\Model\StoreManagerInterface;
use Eguana\Contactus\Model\ContactusRepository;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Eguana\Contactus\Model\Config;
use Psr\Log\LoggerInterface;
use Magento\Newsletter\Model\SubscriberFactory;

/**
 *
 * Class Post to save and send email
 */
class Post
{

    protected StoreManagerInterface $storeManager;

    private ContactusFactory $contactFactory;

    private ContactusRepository $contactRepo;

    private ManagerInterface $messageManager;

    private RedirectFactory $redirectFactory;

    private Config $config;

    private LoggerInterface $logger;

    protected $redirect;
    private SubscriberFactory $subscriberFactory;

    /**
     * @param ContactusFactory $contactFactory
     * @param ContactusRepository $contactRepo
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $redirectFactory
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        ContactusFactory  $contactFactory,
        ContactusRepository $contactRepo,
        ManagerInterface  $messageManager,
        RedirectFactory   $redirectFactory,
        Config $config,
        LoggerInterface $logger,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        SubscriberFactory $subscriberFactory
    ) {
        $this->contactFactory = $contactFactory;
        $this->contactRepo = $contactRepo;
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->config = $config;
        $this->logger = $logger;
        $this->redirect = $redirect;
        $this->subscriberFactory = $subscriberFactory;
    }

    /**
     * Use around Plugin on execute fun to override
     * @param \Magento\Contact\Controller\Index\Post $subject
     * @param callable $proceed
     * @return Redirect
     */
    public function aroundExecute(\Magento\Contact\Controller\Index\Post $subject, callable $proceed): Redirect
    {
        $data = $subject->getRequest()->getPostValue();
        $contact = $this->contactFactory->create();
        $contact->setData($data);
        $resultRedirect = $this->redirectFactory->create();

        try {
            $this->contactRepo->save($contact);
            if (isset($contact['news_letter'])) {
                $this->subscriberFactory->create()->subscribe($contact['email']);
            }
            return $resultRedirect->setPath('contact/index/contactsuccess');

        } catch (\Exception $ex) {
            $this->logger->critical($ex);
            $this->messageManager->addErrorMessage(
                __('Your Data has not been Saved')
            );
        }
        if ($this->config->isEnabled()) {
            return  $proceed();
        }

        $redirectUrl = $this->redirect->getRedirectUrl();
        $resultRedirect->setPath($redirectUrl);

        return $resultRedirect;
    }
}
