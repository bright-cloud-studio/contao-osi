// When the page is loaded
document.addEventListener("DOMContentLoaded", function() {
    // Get Training Image element
    var training_image = document.getElementById("ctrl_training_image");
    var to_append = document.getElementById("pal_certificate_legend");
    
    // If we have a value, display the image
    if(training_image.value) {
        // Remove a previous Training Image
        removeOldTrainingImage();
        // Assemble our image's URL
        var image_url = '/files/content/certificate_images/' + training_image.value + '.jpeg';
        // Append HTML containing our Training Image so the user can preview it
        to_append.insertAdjacentHTML("afterbegin", "<div id='training_image_helper' class='clr widget' style='padding-top:5px;'><img id='hotspot_image' src='" + image_url + "' width='300px'></div>");
    }
    
    const selectElement = document.getElementById("ctrl_training_image");
    selectElement.addEventListener("change", function() {
        
        // Remove a previous Training Image
        removeOldTrainingImage();
        // Assemble our image's URL
        var image_url = '/files/content/certificate_images/' + training_image.value + '.jpeg';
        // Append HTML containing our Training Image so the user can preview it
        to_append.insertAdjacentHTML("afterbegin", "<div id='training_image_helper' class='clr widget' style='padding-top:5px;'><img id='hotspot_image' src='" + image_url + "' width='300px'></div>");
    });
    
});

// Attempts to find an already existing "#training_image_helper" and remove() it, so we only display one at a time
function removeOldTrainingImage() {
    var deleteOld = document.getElementById("training_image_helper");
    if(deleteOld != null)
        deleteOld.remove();
}
