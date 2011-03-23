<?php 

require_once 'ThreeScale/ThreeScaleClient.php';

class My_Controller_Plugin_RestAuth extends Zend_Controller_Plugin_Abstract
{
	
	public function __construct($threeScaleAPI)
	{
		$this->threeScaleAPIKey = $threeScaleAPI;
	}
	
    /**
     * Contains Rest authentication with 3scale authentication
     * 
     * @param Zend_Controller_Request_Abstract $request
     */
     public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
		$app_id = $request->getParam('app_id');
        $app_key = $request->getParam('app_key');
                
        $threeScaleApiKey = $this->threeScaleAPIKey;
        		
		$this->_client = new ThreeScaleClient($threeScaleApiKey);
		
		$missingArguments = array();
		$errorMessage = '';
		
		if (empty($app_id)) {
			$errorMessage = 'missing "app_id" variable';
		}
		
		if (empty($app_key)) {
			$errorMessage = 'missing "app_key" variable';
		}

		if (empty($errorMessage)) {
			// 3scale authentication
			$response = $this->_client->authorize($app_id, $app_key);
			
			if (empty($errorMessage) && $response->isSuccess()) {
				$response = $this->_client->report(array(
					  array('app_id' => $app_id, 'usage' => array('hits' => 1)),
					));	
				
			} else {
				$errorMessage = $this->sendError($response->getErrorMessage());
			}
		}
		
		
		if (!empty($errorMessage)) {
			$this->sendError($errorMessage);
		}
    }
    
    /**
     * Send error message to error controller
     * 
     * @param Zend_Controller_Request_Abstract $request
     * @param string $errorMessage
     */
    public function sendError($errorMessage)
	{
		$this->getResponse()
              ->setHttpResponseCode(403)
              ->setBody($errorMessage);
                 
		$this->_request->setModuleName('default');
		$this->_request->setControllerName('error');
		$this->_request->setActionName('index');
		

	}
}