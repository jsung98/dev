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

namespace Eguana\WorkBoard\Ui\Component\Listing\Column;

use Magento\Store\Ui\Component\Listing\Column\Store;

/**
 * Class for displaying Store views in Grid
 *
 * Class StoreRenderer
 *
 */
class StoreRenderer extends Store
{
    /**
     * Prepare data source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item[$name])) {
                    $item[$name] = $this->prepareItem($item);
                }
            }
        }

        return $dataSource;
    }

    /**
     * Prepare Store views data source
     *
     * @param array $dataSource
     * @return string
     */
    protected function prepareItem(array $dataSource)
    {
        $origStores = null;
        if (isset($dataSource[$this->storeKey])) {
            $origStores = $dataSource[$this->storeKey];
        }

        if (!is_array($origStores)) {
            $origStores = explode(',', $origStores);
        }

        if (in_array(0, $origStores) && count($origStores) == 1) {
            return __('All Store Views');
        }

        $content = '';
        $data = $this->systemStore->getStoresStructure(false, $origStores);
        foreach ($data as $website) {
            $content .= $website['label'] . "<br/>";
//            foreach ($website['children'] as $group) {
  //              $content .=  '56';
    //            foreach ($group['children'] as $store) {
      //              $content .= '78';
        //        }
          //  }
        }
        return $content;
    }
}
