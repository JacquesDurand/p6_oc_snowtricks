import $ from 'jquery';

$(document).ready(function() {
    $('#mainPictureEditButton').click(function (e) {
        e.preventDefault();
        $('#mainPictureEdit').click();
    });
});

$(document).ready(function() {
    $('#mainPictureEdit').on('change', function () {
        $('#editMainPictureForm').submit();
    });
});

$(document).ready(function () {
    $('.click').click(function (e) {
        e.preventDefault();
        let button = '#'+e.target.id
       $(button).prev('.inputFile').click();
    });
});

$(document).ready(function() {
    $('.inputFile').on('change', function (e) {
        let input = '#'+e.target.id
        $(input).parent('.fileForm').submit();
    });
});