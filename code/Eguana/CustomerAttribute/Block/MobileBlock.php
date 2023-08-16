<?php
namespace Eguana\CustomerAttribute\Block;

class MobileBlock extends \Magento\Framework\View\Element\Template
{
    const VALUE_NO = 'no';
    const VALUE_OPTIONAL = 'opt';
    const VALUE_REQUIRED = 'req';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * MobileBlock constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Check if mobile should be shown
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        $showMobile = $this->_scopeConfig->getValue('customer/address/telephone_show');
        if ($showMobile == self::VALUE_REQUIRED || $showMobile == self::VALUE_OPTIONAL) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * Check if mobile should be required
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        $showMobile = $this->_scopeConfig->getValue('customer/address/telephone_show');
        if ($showMobile == self::VALUE_REQUIRED) {
            return true;
        }else{
            return false;
        }
    }

}
