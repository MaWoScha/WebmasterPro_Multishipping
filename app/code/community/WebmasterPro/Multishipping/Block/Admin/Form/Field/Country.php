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
class WebmasterPro_Multishipping_Block_Admin_Form_Field_Country
    extends Mage_Core_Block_Html_Select
{
    /**
     * Customer groups cache
     *
     * @var array
     */
    private $_countries;

    /**
     * Retrieve complete country list from magento
     */
    protected function _getCountries($countryCode = null)
    {
        if (is_null($this->_countries))
        {
            $this->_countries = array();

            $collection = Mage::getModel('directory/country')->getCollection();

            if (!is_null($countryCode))
            {
                $collection->addFieldToFilter('country_id', $countryCode);
            }

            foreach ($collection as $country)
            {
                $this->_countries[$country->getId()] = $country->getName();
            }

        }

        return $this->_countries;
    }

    /**
     * Set the name of the country
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions())
        {
            foreach ($this->_getCountries() as $countryId => $countryLabel)
                $this->addOption($countryId, $countryLabel);
        }

        return parent::_toHtml();
    }

}