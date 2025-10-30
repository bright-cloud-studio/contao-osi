
// When the page is loaded
document.addEventListener("DOMContentLoaded", function() {
    console.log("PAGE LOADED");
    
    // Get Training Image element
    var training_image = document.getElementById("ctrl_training_image");
    var to_append = document.getElementById("pal_certificate_legend");
    
    // If we have a value, display the image
    if(training_image.value) {
        
        removeOldTrainingImage();
        var image_url = getTrainingImageUrl(training_image.value);
        to_append.insertAdjacentHTML("afterbegin", "<div id='training_image_helper' class='clr widget' style='padding-top:5px;'><img id='hotspot_image' src='" + image_url + "' width='300px'></div>");
        
    }
    
    
    const selectElement = document.getElementById("ctrl_training_image");
    selectElement.addEventListener("change", function() {
        
        removeOldTrainingImage();
        var image_url = getTrainingImageUrl(training_image.value);
        to_append.insertAdjacentHTML("afterbegin", "<div id='training_image_helper' class='clr widget' style='padding-top:5px;'><img id='hotspot_image' src='" + image_url + "' width='300px'></div>");
        
    });
    
});



function getTrainingImageUrl(field_value) {
    let image_url;
    
    switch (field_value) {
        case 'image_1':
          image_url = '/files/content/certificate_images/test_image_1.jpg'; // Root path for the homepage
          break;
        case 'image_2':
          image_url = '/files/content/certificate_images/test_image_2.jpg';
          break;
      }
    
    return image_url;
}

function removeOldTrainingImage() {
    var deleteOld = document.getElementById("training_image_helper");
    if(deleteOld != null)
        deleteOld.remove();
}
