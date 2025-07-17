<?php
	
	/*******************/
	/* REQUIRED STUFFS */
	/*******************/
	
	// Session is required as we stored our product information inside it
	session_start();
	// Bring in composer since that's how everything works nowadays
	require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
	
	// Uses always go at the top, we are using Dompdf and it's options class
	use Dompdf\Dompdf;
    use Dompdf\Options;
	
	
	/********************/
	/* INITALIZE STUFFS */
	/********************/
	
    // Create our Dompdf Options class
    $options = new Options();
    // Set our options
    $options->set("defaultFont", "Helvetica");
    $options->set("isRemoteEnabled", "true");
    $options->setChroot('/');

	// Initialize Dompdf using our just set up Options
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
	
	// Our product id is passed in manually so we know which session array to access to get our product information
	$product_id = $_POST['product_id'];
	
	// In order to avoid having to figure out where our image is stored, as $product_data only contains the filename, instead
  	// we pass in the file structure plus product name by grabbing it with jQuery on the Product Reader page itself. Then, we 
  	// create $img_src which is the full address to the image
  	$img_src = $_SERVER["DOCUMENT_ROOT"] . "/../" . $_POST['product_image'];
	
	
	/**********************************/
	/* PRODUCT DATA STORED IN SESSION */
	/**********************************/
	
	// Get our product information that is stored in the session
  	$product_data = unserialize($_SESSION['pdf_data'][$product_id]);
  	$product_options = unserialize($_SESSION['product_options'][$product_id]);
	
	
	/*******************/
	/* TEMPLATE STUFFS */
	/*******************/
  	
    // Load our HTML template
    $html = file_get_contents('../templates/product_type_'.$product_data->type.'.html', true);
    
    // Replace our tags with the proper values
    $html = str_replace("{{img_src}}", $img_src, $html);
    
    // Find all instances of our tag brackets '{{tag}}' and store them in the $tags array
    preg_match_all('/\{{2}(.*?)\}{2}/is', $html, $tags);
    
    // Loop through those tags and replace them with the correct product data
    foreach($tags[0] as $tag) {
        
        // Remove brackets from our tag
        $cleanTag = str_replace("{{","",$tag);
        $cleanTag = str_replace("}}","",$cleanTag);
        
        // Explode our tag into two parts
	    $explodedTag = explode("::", $cleanTag);
	    
	    // Do different things based on the first part of our tag
	    switch($explodedTag[0]) {
		    
		    // If the first part of our exploded tag is "product" we are looking for an attribute
		    case 'product':
		        
		        // Get the product attribute that is based on our second half of the tag
		        // This thing is super powerful. Trying to reference the class data typically required knowing the name ahead of time
		        // But now this way by closing our variable in "{ }" brackets it acts as if it's a fixed name, so we can get any product
		        // data without having to write each tag manually. Just put in a products attribute name in the template and we will get
		        // back the correct data. Super happy with this, I did not know it was possible.
		        switch($explodedTag[1]) {
		            case 'name':
		                $html = str_replace($tag, $product_data->{$explodedTag[1]}, $html);
		                break;
		            case 'description':
		                $html = str_replace($tag, $product_data->{$explodedTag[1]}, $html);
		                break;
		            default:
		                
		                $title = ucwords($explodedTag[1]);
		                $title = str_replace("_"," ",$title);
		                
		                $buffer = '';
		                $buffer .= "<div class='attribute " . $explodedTag[1] . "'>";
		                $buffer .= "<h3>" . $title . "</h3>";
		                $buffer .= $product_data->{$explodedTag[1]};
		                $buffer .= "</div>";
		                
		                $html = str_replace($tag, $buffer, $html);
		                
		                break;
		        }
		        
		    
		    break;
		    
		    //colors
		    case 'options':
		        
		        $buffer = '';
	            foreach($product_options as $thing) {
	                $cleaned = str_replace("https://acousticalproducts.com/", $_SERVER["DOCUMENT_ROOT"] . '/../', $thing[$explodedTag[1]]);
                    $buffer .= $cleaned;
                }
                $html = str_replace($tag, $buffer, $html);
                
		        break;
		        
		    case 'year':
		        $buffer = date('Y');
                $html = str_replace($tag, $buffer, $html);
		        
		        break;
		        
		    case 'site_url':
		        //$buffer = "https://" . $_SERVER['SERVER_NAME'];
                $buffer = $_SERVER["DOCUMENT_ROOT"] . '/..';
                $html = str_replace($tag, $buffer, $html);
		        
		        break;
		        
		  
	    }
        
    }
    
    
    /***********************/
	/* GENERATE PDF STUFFS */
	/***********************/
	
	// DEBUG
	//$myfile = fopen("debug_".rand(111111,999999).".txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $html);
    //fclose($myfile);
	
    // Load our HTML into dompdf
	$dompdf->loadHtml($html);
	
	// Set our paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render our PDF using the loaded HTML
	$dompdf->render();
	
	// Output the generated PDF to Browser
	$dompdf->stream("spec_sheet.pdf");

?>
