
// When the page is loaded
document.addEventListener("DOMContentLoaded", function() {
    console.log("PAGE LOADED");
    
    // Get Training Image element
    var training_image = document.getElementById("ctrl_training_image");
    var to_append = document.getElementById("pal_certificate_legend");
    
    // If we have a value, display the image
    if(training_image.value) {
        
        removeOldTrainingImage();
        var image_url = '/files/content/certificate_images/' + training_image.value + '.jpeg';
        to_append.insertAdjacentHTML("afterbegin", "<div id='training_image_helper' class='clr widget' style='padding-top:5px;'><img id='hotspot_image' src='" + image_url + "' width='300px'></div>");
        
    }
    
    
    const selectElement = document.getElementById("ctrl_training_image");
    selectElement.addEventListener("change", function() {
        
        removeOldTrainingImage();
        var image_url = '/files/content/certificate_images/' + training_image.value + '.jpeg';
        to_append.insertAdjacentHTML("afterbegin", "<div id='training_image_helper' class='clr widget' style='padding-top:5px;'><img id='hotspot_image' src='" + image_url + "' width='300px'></div>");
        
    });
    
});



function removeOldTrainingImage() {
    var deleteOld = document.getElementById("training_image_helper");
    if(deleteOld != null)
        deleteOld.remove();
}
