<?php
	
	/*******************/
	/* REQUIRED STUFFS */
	/*******************/
	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
	
	use Dompdf\Dompdf;
    use Dompdf\Options;
	
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

	$product_id = $_POST['product_id'];

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
		                $html = str_replace($tag, "member_name", $html);
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
