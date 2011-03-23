<?php

class ErrorController extends Zend_Controller_Action
{

    public function indexAction()
    {
		$this->returnError($this->getResponse()->getBody());
    }
    
    public function errorAction()
    {
    		$error = $this->_getParam('error_handler');
    	
    		switch ($error->type) {
    			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
    			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
    			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
    				$errorMessage = 'Function not found';
    				break;
    			default:
    				// application error
    				$errorMessage = 'Application error';
    		}
    	
   		$this->returnError($errorMessage);
    	
    }

    /**
    * Send formatted (XML, JSON)) error message
    *
    * @param string $errorMessage
    */
    protected function returnError($errorMessage)
    {
    	$data = array(
    			'response' => array(
    				'message' => $errorMessage
    	),
    			'status' => 'failed'
    	);
    
    	$this->sendResponse($data);
    }
    
	/**
	 * 
	 * Send the response as a XML or JSON
	 * 
	 * @param unknown_type $data
	 */
	protected function sendResponse($data)
	{
		$format = $this->_getParam('format', 'xml');
		
		if ($format=='xml') {
			header('Content-type: text/xml');
			echo $this->formatXmlString(My_Rest_Controller::toXml($data));			
		} else {
			header('Content-type: text/json');
			echo Zend_Json::encode($data, false, array('enableJsonExprFinder' => true));
		}
		
		exit;
	}
	
	/** 
	 * Format XML string
	 * 
	 * @param unknown_type $xml
	 */
	function formatXmlString($xml)
	{  
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml);
		
		echo $dom->saveXML();
	} 


}
