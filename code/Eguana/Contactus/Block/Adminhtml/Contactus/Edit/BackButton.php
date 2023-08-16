<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 6/12/21
 * Time: 2:16 PM
 */
namespace Eguana\Contactus\Block\Adminhtml\Contactus\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Eguana\Contactus\Block\Adminhtml\Contactus\Edit\GenericButton as GenericButton;

/**
 * This class is responsible for back button of form of contact us
 *
 * Class BackButton
 */
class BackButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get Button
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 190,
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }
}
