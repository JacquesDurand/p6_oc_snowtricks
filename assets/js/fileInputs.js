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
    })
});

$(document).ready(function () {
    $('.click').click(function (e) {
        e.preventDefault();
       $('.click').prev('.inputFile').click();
    })
});

$(document).ready(function() {
    $('.inputFile').on('change', function () {
        $('.inputFile').parent('.fileForm').submit();
    })
});