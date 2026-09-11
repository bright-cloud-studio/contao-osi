
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

// Member list group popup handler
document.addEventListener('mouseover', function(e) {
    var wrap = e.target.closest('.group-more-wrap');
    if (!wrap) return;

    var popup = wrap.querySelector('.group-popup');
    if (!popup) return;

    popup.style.display = 'block';
    popup.style.top = '100%';
    popup.style.bottom = 'auto';
    popup.style.left = 'auto';
    popup.style.right = '0';

    var r = popup.getBoundingClientRect();
    if (r.bottom > window.innerHeight) {
        popup.style.top = 'auto';
        popup.style.bottom = '100%';
    }

    if (r.right > window.innerWidth) {
        popup.style.left = 'auto';
        popup.style.right = '0';
    } else if (r.left < 0) {
        popup.style.left = '0';
        popup.style.right = 'auto';
    }
});

document.addEventListener('mouseout', function(e) {
    var wrap = e.target.closest('.group-more-wrap');
    if (!wrap) return;

    if (!wrap.contains(e.relatedTarget)) {
        var popup = wrap.querySelector('.group-popup');
        if (popup) {
            popup.style.display = 'none';
        }
    }
});

