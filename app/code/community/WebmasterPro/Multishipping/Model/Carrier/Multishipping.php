<?php
/**
 * @category  WebmasterPro
 * @package   WebmasterPro_Multishipping
 * @authors   Marc Rochow <http://gironimo.org/>, Fabian Ziegler <http://team23.de/>
 * @developer
 * @version   1.0.0
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @source    http://www.webmasterpro.de/coding/article/php-tutorial-magento-extension-erstellen.html
 */
class WebmasterPro_Multishipping_Model_Carrier_MultistoreShipping
    extends Mage_Shipping_Model_Carrier_Abstract
{
    /**
     * unique internal shipping method identifier
     *
     * @var string [a-z0-9_]
     */
    protected $_code = 'multistore_shipping';

    /**
     * Collect rates for this shipping method based on information in $request
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        // skip if not enabled
        if (!Mage::getStoreConfig('carriers/' . $this->_code . '/active'))
            return false;

        $result = Mage::getModel('shipping/rate_result');

        $priceTable = unserialize(Mage::getStoreConfig('carriers/' . $this->_code . '/pricetable'));

        // check country
        if (is_array($priceTable))
        {
            foreach ($priceTable as $key => $price)
            {
                if ($price['country_id'] == $request->getDestCountryId())
                {
                    $method = Mage::getModel('shipping/rate_result_method');

                    $method->setCarrier($this->_code);
                    $method->setCarrierTitle(Mage::getStoreConfig('carriers/' . $this->_code . '/title'));

                    $quote = Mage::getSingleton('checkout/session')->getQuote();
                    $total = $request->getBaseSubtotalInclTax();

                    // check coupon code
                    if ($quote->getCouponCode() !== '' && $quote->getCouponCode() !== false)
                    {
                        $calcDiscountAmount = $request->getPackageValue() - $request->getPackageValueWithDiscount();
                        $total = $total - $calcDiscountAmount;
                    }

                    if ($total >= $price['free_shipping_price'] || $request->getFreeShipping() === true)
                    {
                        $shippingCost = '0.00';
                    }
                    else
                    {
                        $shippingCost = $price['price'];
                    }

                    $method->setMethod('multistore_shipping');
                    $method->setMethodTitle(Mage::getStoreConfig('carriers/' . $this->_code . '/name'));
                    $method->setCost($shippingCost);
                    $method->setPrice($shippingCost);

                    $result->append($method);
                }
            }
        }

        return $result;
    }

    public function getAllowedMethods()
    {
        return array('multistore_shipping' => $this->getConfigData('name'));
    }

}