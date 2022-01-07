import $ from 'jquery';

$(document).ready(function() {
    let $wrapper = $('.js-picture-wrapper');
    $wrapper.on('click', '.js-remove-picture', function(e) {
        e.preventDefault();
        $(this).closest('.js-picture-item')
            .fadeOut()
            .remove();
    });
    $wrapper.on('click', '.js-picture-add', function(e) {
        e.preventDefault();
        // Get the data-prototype explained earlier
        let prototype = $wrapper.data('prototype-picture');
        // get the new index
        let index = $wrapper.data('index-picture');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $wrapper.data('index-picture', index + 1);
        // Display the form in the page before the "new" link
        $(this).before(newForm);


        let fileInput = $('#trick_pictures_'+index+'_file');

        fileInput.on('change', function () {
            getImgData();
        })

        function getImgData() {
            const files = fileInput[0].files[0];
            if (files) {
                const fileReader = new FileReader();
                fileReader.readAsDataURL(files);
                fileReader.addEventListener("load", function () {
                    let imgPreview = fileInput.parent().parent().next('.img-preview')
                    console.log(imgPreview)
                    imgPreview.css('display', 'block')
                    imgPreview.html('<img src="' + this.result + '"  alt=""/>');
                });
            }

        }
    });
});

$(document).ready(function() {
    let $wrapper = $('.js-comment-wrapper');
    $wrapper.on('click', '.js-remove-comment', function(e) {
        e.preventDefault();
        $(this).closest('.js-comment-item')
            .fadeOut()
            .remove();
    });
    $wrapper.on('click', '.js-comment-add', function(e) {
        e.preventDefault();
        // Get the data-prototype explained earlier
        let prototype = $wrapper.data('prototype-comment');
        // get the new index
        let index = $wrapper.data('index-comment');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $wrapper.data('index-comment', index + 1);
        // Display the form in the page before the "new" link
        $(this).before(newForm);
    });
});