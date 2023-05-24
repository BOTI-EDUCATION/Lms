window.addEventListener('load', function () {

    // setTimeout to simulate the delay from a real page load
    setTimeout(lazyLoad, 1000);

});
function lazyLoad() {
    var images = document.querySelectorAll('img[data-src]');
    // loop over each card image
    images.forEach(function (img) {
        var image_url = img.getAttribute('data-src');
        img.src = image_url;
        // listen for load event when the new photo is finished loading
        img.addEventListener('load', function () {
            // swap out the visible background image with the new fully downloaded photo
            img.style.backgroundImage = 'url(' + image_url + ')';
            // add a class to remove the blur filter to smoothly transition the image change
            img.className = card_image.className + ' is-loaded';
        });

    });
}