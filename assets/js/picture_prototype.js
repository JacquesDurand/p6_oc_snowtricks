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
        let prototype = $wrapper.data('prototype');
        // get the new index
        let index = $wrapper.data('index');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $wrapper.data('index', index + 1);
        // Display the form in the page before the "new" link
        $(this).before(newForm);
    });
});