<?php
/**
 * HTML Compressor
 * Author: Razorphyn
 * Suport Email: est.garndi@gmail.com
 * Copyright: WTFPL(http://www.wtfpl.net/)
**/
?>
<?php
	class Razorphyn_HTMLCompressor_Model_Observer
	{
		public function alterOutput($observer)
		{
			$lib_path = Mage::getBaseDir('lib').'/Razorphyn/HTMLCompressor/html_compressor.php';
			require_once($lib_path);

			//Retrieve html body
			$response = $observer->getResponse();       
			$html     = $response->getBody();
			
			//Compress HTML
			$html=html_compress($html);
			
			//Send Response
			$response->setBody($html);
		}
	}
?>