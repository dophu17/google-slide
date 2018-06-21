<?php

function getSlide($service, $presentationId) {
	echo '<br><br>---Get slide: ' . $presentationId;
	$presentation = $service->presentations->get($presentationId);
	$slides = $presentation->getSlides();

	echo "<br>The presentation contains slides:" . count($slides);
	foreach ($slides as $i => $slide) {
	    echo "<br>- Slide #" . ($i + 1) . " contains elements:" . count($slide->getPageElements());
	}
	echo '<br>---End get slide';
}

function createPresentation($service, $presentation) {
	echo '<br><br>---createPresentation:';
	$title = 'Create slider from API PHP';
	$presentation = new Google_Service_Slides_Presentation(array(
	    'title' => $title
	));
	$presentation = $service->presentations->create($presentation);
	$presentationLastId = $presentation->presentationId;
	echo "Created presentation with ID: " . $presentationLastId;
	echo '<br>---createPresentation';
	return $presentationLastId;
}

function addSlide($service, $presentationId) {
	echo '<br><br>Add slide:';

	// Add a slide at index 1 using the predefined 'TITLE_AND_TWO_COLUMNS' layout and
	// the ID page_id.
	$requests = array();
	$requests[] = new Google_Service_Slides_Request(array(
		'createSlide' => array (
			'objectId' => 'Slide_1',
			// 'insertionIndex' => 1,
			'slideLayoutReference' => array (
			  	'predefinedLayout' => 'BLANK' //'TITLE_AND_TWO_COLUMNS'
			)
		),
	));

	// Execute the request.
	$batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
		'requests' => $requests
	));
	$response = $service->presentations->batchUpdate($presentationId, $batchUpdateRequest);
	$createSlideResponse = $response->getReplies()[0]->getCreateSlide();
	$slideLatsId = $createSlideResponse->getObjectId();
	printf("Created slide with ID: %s\n", $slideLatsId);

	addShape($service, $presentationId, $slideLatsId);

	echo '<br><br>End add slide';
}

function addShape($service, $presentationId, $slideId) {
	// Create a new square textbox, using the supplied element ID.
	$elementId = 'TextBox_1';
	$pt350 = array('magnitude' => 100, 'unit' => 'PT');
	$requests = array();
	$requests[] = new Google_Service_Slides_Request(array(
	  'createShape' => array (
	    'objectId' => $elementId,
	    'shapeType' => 'TEXT_BOX',
	    'elementProperties' => array(
	      'pageObjectId' => $slideId,
	      'size' => array(
	        'height' => $pt350,
	        'width' => $pt350
	      ),
	      'transform' => array(
	        'scaleX' => 1,
	        'scaleY' => 1,
	        'translateX' => 0,
	        'translateY' => 0,
	        'unit' => 'PT'
	      )
	    )
	  ),
	));

	// Insert text into the box, using the supplied element ID.
	$requests[] = new Google_Service_Slides_Request(array(
	  'insertText' => array(
	    'objectId' => $elementId,
	    'insertionIndex' => 0,
	    'text' => 'New Box Text Inserted!'
	  )
	));

	// Insert image, using the supplied element ID.
	$requests[] = new Google_Service_Slides_Request(array(
	  'createImage' => array(
	  	'objectId' => 'Img_1',
	    'elementProperties' => array(
	      'pageObjectId' => $slideId,
	      'size' => array(
	        'height' => $pt350,
	        'width' => $pt350
	      ),
	      'transform' => array(
	        'scaleX' => 1,
	        'scaleY' => 1,
	        'translateX' => 0,
	        'translateY' => 100,
	        'unit' => 'PT'
	      )
	    ),
	    'url' => 'http://9mobi.vn/cf/images/2015/03/nkk/hinh-dep-1.jpg'
	  )
	));

	// Execute the requests.
	$batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
	  'requests' => $requests
	));
	$response = $service->presentations->batchUpdate($presentationId, $batchUpdateRequest);
	$createShapeResponse = $response->getReplies()[0]->getCreateShape();
	printf("Created textbox with ID: %s\n", $createShapeResponse->getObjectId());
}