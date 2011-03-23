<?php

class AnnouncementsController extends My_Rest_Controller
{

	public function getAction() {

		$functionName = $this->_getParam('id');
    	
    	$data = array();
    	if ($functionName=='list') {
    		$data['response'] = $this->listAnnouncements();
    	}

    	$data['status'] = 'success';
    	$this->sendResponse($data);
	}



	public function postAction() {

		$functionName = $this->_getParam('id');
		
		if ($functionName=='add') {
			$data = $this->addAnnouncement();
		}

		$data['status'] = 'success';
		 
		$this->sendResponse($data);
	}

	protected function addAnnouncement() {
		
		$type = $this->_getParam('type');
		$title = $this->_getParam('title');
		$text = $this->_getParam('text');

		$response = array(
				'id' => '33344'
		);

		return $response;
		
	}

	
	/**
	 * list all announcements
	 */
	protected function listAnnouncements() {
		$data = array(
			'annocument' => 
				array (
					'id' => '1',
					'title' => 'Announcement'
				)
		);
		
	
			
		return $data;
	}
	
	

}