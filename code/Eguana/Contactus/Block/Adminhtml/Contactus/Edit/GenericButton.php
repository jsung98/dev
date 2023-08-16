<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 6/12/21
 * Time: 2:17 PM
 */
namespace Eguana\Contactus\Block\Adminhtml\Contactus\Edit;

use Magento\Backend\Block\Widget\Context;
use Eguana\Contactus\Api\ContactusRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * This class is use for form buttons
 *
 * Class GenericButton
 */
class GenericButton
{

    protected Context $context;

    protected ContactusRepositoryInterface $contactusRepository;
    private LoggerInterface $logger;

    /**
     * @param Context $context
     * @param LoggerInterface $logger
     * @param ContactusRepositoryInterface $contactusRepository
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        ContactusRepositoryInterface $contactusRepository
    ) {
        $this->context = $context;
        $this->contactusRepository = $contactusRepository;
        $this->logger = $logger;
    }

    /**
     * Return Vendor Contact ID
     *
     * @return int|null
     */
    public function getContactId()
    {
        try {
            return $this->contactusRepository->getById(
                $this->context->getRequest()->getParam('contact_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
    /**
     * Check where button can be rendered
     *
     * @param string $name
     * @return string
     */
    public function canRender(string $name)
    {
        return $name;
    }
}
