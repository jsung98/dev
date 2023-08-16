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

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class for the deletebutton toolbar on create/edit board
 *
 * Class DeleteButton
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get delete button data
     *
     * @return array
     */
    public function getButtonData() : array
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label' => __('Delete Board'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * Url to send delete requests to
     *
     * @return string
     */
    private function getDeleteUrl() : string
    {
        return $this->getUrl('*/*/delete', ['board_id' => $this->getId()]);
    }
}
