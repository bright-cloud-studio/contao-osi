<?php
	
	use Dompdf\Dompdf;
    use Dompdf\Options;
    
    use Bcs\Model\TestResult;
    
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

	// Load our database connection information from a txt file on the root of the server, for safety
	$serializedData = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../db.txt');
	$db_info = unserialize($serializedData);
    
    $dbh = new mysqli("localhost", $db_info[0], $db_info[1], $db_info[2]);
    if ($dbh->connect_error) {
        die("Connection failed: " . $dbh->connect_error);
    }
    
    
    

    
	
	/********************/
	/* INITALIZE STUFFS */
	/********************/
	
    $options = new Options();
    $options->set("defaultFont", "Helvetica");
    $options->set("isRemoteEnabled", "true");
    $options->setChroot('/');
	$dompdf = new Dompdf($options);
	$context = stream_context_create([ 
    	'ssl' => [ 
    		'verify_peer' => FALSE, 
    		'verify_peer_name' => FALSE,
    		'allow_self_signed'=> TRUE 
    	] 
    ]);
    $dompdf->setHttpContext($context);
	
	/*****************************/
	/* MANUALLY PASSED IN STUFFS */
	/*****************************/

	$test_result_id = $_POST['result_id'];
    
    $test_result = [];
    $test = [];
    
    $test_result_query =  "SELECT * FROM tl_test_result WHERE id='".$test_result_id."'";
    $test_result_db = $dbh->query($test_result_query);
    if($test_result_db) {
        while($result = $test_result_db->fetch_assoc()) {
            $test_result['test'] = $result['test'];
            $test_result['result_percentage'] = $result['result_percentage'];
        }
    }
    
    $test_query =  "SELECT * FROM tl_form WHERE id='".$test_result['test']."'";
    $test_db = $dbh->query($test_query);
    if($test_db) {
        while($result = $test_db->fetch_assoc()) {
            $test['title'] = $result['title'];
        }
    }

	

	/*******************/
	/* TEMPLATE STUFFS */
	/*******************/
  	
    // Load our HTML template
    $html = file_get_contents('../templates/certificate.html', true);
    
    preg_match_all('/\{{2}(.*?)\}{2}/is', $html, $tags);
    foreach($tags[0] as $tag) {
        
        // Remove brackets from our tag
        $cleanTag = str_replace("{{","",$tag);
        $cleanTag = str_replace("}}","",$cleanTag);
        
	    $explodedTag = explode("::", $cleanTag);
	    
	    // Do different things based on the first part of our tag
	    switch($explodedTag[0]) {
		    
		    // If the first part of our exploded tag is "product" we are looking for an attribute
		    case 'member':
		        switch($explodedTag[1]) {
		            case 'name':
		                $html = str_replace($tag, $asdf, $html);
		                break;
		        }
		    break;
		    
		    case 'result':
		        switch($explodedTag[1]) {
		            case 'id':
		                $html = str_replace($tag, $test_result_id, $html);
		                break;
		            case 'title':
		                $html = str_replace($tag, $test['title'], $html);
		                break;
		            case 'result_percentage':
		                $html = str_replace($tag, $test_result['result_percentage'], $html);
		                break;
		        }
		    break;

	    }
        
    }
    
    /***********************/
	/* GENERATE PDF STUFFS */
	/***********************/
	
    // Load our HTML into dompdf
	$dompdf->loadHtml($html);
	
	// Set our paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render our PDF using the loaded HTML
	$dompdf->render();
	
	// Output the generated PDF to Browser
	$dompdf->stream("certificate.pdf");

?>
