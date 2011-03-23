<?php

abstract class My_Rest_Controller extends Zend_Rest_Controller
{
	const EXCEPTION_NO_CONTROLLER = 'EXCEPTION_NO_CONTROLLER';
	
	
	private $contexts = array(
        'get' => array('xml', 'json')
    );
    
	public function init()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->no_results = array('status' => 'NO_RESULTS');

        $this->_helper->contextSwitch()->initContext();
		
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);

        parent::init();	
	}
	
	protected function returnError($errorMessage) {
		
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
	 * send the response as a XML or JSON
	 * 
	 * @param unknown_type $data
	 */
	protected function sendResponse($data) {
		
		$format = $this->_getParam('format', 'xml');
		
		if ($format=='xml') {
			header('Content-type: text/xml');
			echo $this->formatXmlString($this->toXml( $data ));			
		} else {
			echo $this->_helper->json($data, array('enableJsonExprFinder' => true));
		}
		
		exit;
		

	}

	/**
	 * The index action handles index/list requests; it should respond with a
	 * list of the requested resources.
	 */
	public function indexAction() {
		//HTTP code 500 might not good choice here.
		$this->getResponse ()->setHttpResponseCode ( 500 );
		$this->getResponse ()->appendBody ( "no list/index allowed" );

	}


	/**
	 * The post action handles POST requests; it should accept and digest a
	 * POSTed resource representation and persist the resource state.
	 */
	public function postAction() {

	}

	/**
	 * The put action handles PUT requests and receives an 'id' parameter; it
	 * should update the server resource state of the resource identified by
	 * the 'id' value.
	 */
	public function putAction() {

	}

	/**
	 * The delete action handles DELETE requests and receives an 'id'
	 * parameter; it should update the server resource state of the resource
	 * identified by the 'id' value.
	 */
	public function deleteAction() {

	}


	/**
	 * The main function for converting to an XML document.
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @param array $data
	 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
	 * @param SimpleXMLElement $xml - should only be used recursively
	 * @return string XML
	 */
	public static function toXml($data, $rootNodeName = 'data', $xml=null)
	{
        if (is_null($xml))
        {
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }

        // loop through the data passed in.
        foreach($data as $key => $value)
        {
            // if numeric key, assume array of rootNodeName elements
            if (is_numeric($key))
            {
                $key = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            // if there is another array found recrusively call this function
            if (is_array($value))
            {
                // create a new node unless this is an array of elements
                $node = self::isAssoc($value) ? $xml->addChild($key) : $xml;

                // recrusive call - pass $key as the new rootNodeName
                self::toXml($value, $key, $node);
            }
            else
            {
                // add single node.
                $value = htmlentities($value);
                $xml->addChild($key,$value);
            }

        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }

    // determine if a variable is an associative array
    public static function isAssoc( $array ) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
    
 	function formatXmlString($xml) {  
	  
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml);
		
		echo $dom->saveXML();
	}  
}