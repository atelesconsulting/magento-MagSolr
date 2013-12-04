<?php

/**
 * Solr data helper
 *
 * @category Asm
 * @package Asm_Solr
 * @author Ingo Renner <ingo@infielddesign.com>
 */
class Asm_Solr_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Gets the current Solr query
	 *
	 * @return Asm_Solr_Model_Solr_Query
	 */
	public function getQuery()
	{
		return Mage::getSingleton('solr/solr_query');
	}

	/**
	 * Generates the result page URL
	 *
	 * @param string $keywords
	 * @return string
	 */
	public function getResultUrl($keywords = null)
	{
		return $this->_getUrl('search/result', array(
			'_query' => array(Asm_Solr_Model_Solr_Query::QUERY_PARAMETER_NAME => $keywords),
			'_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
		));
	}

	/**
	 * Generates the suggest URL
	 *
	 * @return string
	 */
	public function getSuggestUrl()
	{
		return $this->_getUrl('search/suggest', array(
			'_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
		));
	}

	/**
	 * Generates a document id for documents representing product records.
	 *
	 * @param integer $productId Product ID
	 * @return string The document id for that product
	 */
	public function getProductDocumentId($productId)
	{
		$baseUrl  = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
		$host     = parse_url($baseUrl, PHP_URL_HOST);
		$siteHash = $this->getSiteHashForDomain($host);

		$documentId = $siteHash . '/' . Mage_Catalog_Model_Product::ENTITY . '/' . $productId;

		return $documentId;
	}

	/**
	 * Gets the site hash for a domain
	 *
	 * @param string $domain Domain to calculate the site hash for.
	 * @return string site hash for $domain
	 */
	public function getSiteHashForDomain($domain)
	{
		$encryptionKey = Mage::getStoreConfig('global/crypt/key');

		$siteHash = sha1(
			$domain .
			$encryptionKey .
			'Asm_Solr'
		);

		return $siteHash;
	}

	/**
	 * Takes a Magento date string or Zend_Date and turns it into an
	 * ISO 8601 compliant formatted string of the date.
	 *
	 * @param string|Zend_Date $date Magento date string
	 * @return string ISO date (using Z instead of +00:00)
	 */
	public function dateToIso($date)
	{
		if (is_string($date)) {
			$date = new Zend_Date($date);
		}

		return str_replace('+00:00', 'Z', $date->getIso());
	}

}
