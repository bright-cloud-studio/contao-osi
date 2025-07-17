
function genPDF() {
    
    /* Get information that's on page */
    var product_image = $('.main_image img').attr('src');
    var product_id = $('.hidden_data #product_id').text();
    
    /* Get Product Name and format for use as filename */
    var product_name = $('.mod_iso_productreader [itemprop=name]').text().toLowerCase();
    product_name = product_name.replace(/ /g, "_");
    product_name = product_name.replace(/[^a-zA-Z0-9]/g,'_');
    
    
    console.log("product_id: " + product_id);
    

    // Ajax call is using a plugin "jquery-ajax-native.js" that allows it to get the returned data in the correct format, otherwise we were downloading a blank PDF
    $.ajax({
        dataType: 'native',
        url:'/system/modules/isotope_spec_sheet_pdf/assets/php/action.generate.pdf.php',
        type:'POST',
        data: { product_image: product_image, product_id: product_id },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data) {
            
            // Get our returned data as a blob
            var blob = new Blob([data], {type: 'application/pdf'});
            // Create a new link
            var link = document.createElement('a');
            // Set that link to our returned blob
            link.href = window.URL.createObjectURL(blob);
            // Set the file name
            link.download = product_name + ".pdf";
            // for firefox
            document.body.appendChild(link);
            // CLick our invisible link to initiate our download
            link.click();
            // Push a success message to the console so we know things went well
            console.log("PDF: Generation Successful");
        },
        error:function(result){
            // Push a failure message to the console so we know we messed up somewhere
            console.log("PDF: Generation Failure");
        }
    }); // END OF Ajax call
    
}
