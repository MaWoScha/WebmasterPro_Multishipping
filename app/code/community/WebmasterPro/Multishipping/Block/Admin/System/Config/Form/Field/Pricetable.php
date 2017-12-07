<?php
/**
 * @category  WebmasterPro
 * @package   WebmasterPro_Multishipping
 * @authors   Marc Rochow <http://gironimo.org/>, Fabian Ziegler <http://team23.de/>
 * @developer
 * @version   1.0.1
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @source    http://www.webmasterpro.de/coding/article/php-tutorial-magento-extension-erstellen.html
 */
class WebmasterPro_Multishipping_Block_Admin_System_Config_Form_Field_Pricetable
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    const PRICE_TABLE_PREFIX = "carriers_multishipping_pricetable_";

    /** @var Mage_CatalogInventory_Block_Adminhtml_Form_Field_Customergroup */
    protected $_priceRenderer;

    /**
     * Retrieve group column renderer
     *
     * @return WebmasterPro_Multishipping_Block_Admin_Form_Field_Country (Mage_Core_Block_Html_Select)
     */
    protected function _getPriceRenderer()
    {
        $countryCode = str_replace(
            self::PRICE_TABLE_PREFIX, '', $this->getElement()->getId()
        );

        if (!$this->_priceRenderer)
        {
            $this->_priceRenderer = $this->getLayout()->createBlock(
                'multishipping/admin_form_field_country', '', array('country_code' => $countryCode)
            );
        }

        return $this->_priceRenderer;
    }

    /**
     * Default class constructor
     */
    public function __construct()
    {
        $this->addColumn('country_id', array(
            'label' => Mage::helper('adminhtml')->__('Country'),
            'style' => 'width: 150px'
        ));

        $this->addColumn('price', array(
            'label' => Mage::helper('adminhtml')->__('Shipping Price'),
            'style' => 'width: 100px'
        ));

        $this->addColumn('free_shipping_price', array(
            'label' => Mage::helper('multishipping')->__('Free Shipping with Minimum Order Amount'),
            'style' => 'width: 100px'
        ));

        parent::__construct();

        $this->setTemplate('multishipping/system/config/form/field/array.phtml');
        $this->_addButtonLabel = Mage::helper('multishipping')->__('Add Country');
    }

    /**
     * Get the grid and scripts contents
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $this->addColumn('country_id', array(
            'label' => Mage::helper('adminhtml')->__('Country'),
            'renderer' => $this->_getPriceRenderer()
        ));

        return parent::_getElementHtml($element);
    }

}