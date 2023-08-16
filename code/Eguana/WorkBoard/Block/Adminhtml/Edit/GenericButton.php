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

namespace Eguana\WorkBoard\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Context;
use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Generic class for all buttons
 *
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @param Context $context
     * @param LoggerInterface $logger
     * @param BoardRepositoryInterface $boardRepository
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        BoardRepositoryInterface $boardRepository
    ) {
        $this->context = $context;
        $this->logger  = $logger;
        $this->boardRepository = $boardRepository;
    }

    /**
     * Return Board Id
     *
     * @return int|null
     */
    public function getId()
    {
        try {
            if (empty($this->context->getRequest()->getParam('board_id'))) {
                return null;
            }
            return $this->boardRepository->getById(
                $this->context->getRequest()->getParam('board_id')
            )->getId();
        } catch (\Exception $e) {
            $this->logger->info('Generic Block Exception', $e->getMessage());
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
}
