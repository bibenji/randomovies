$(function() {

    // var routes = require('../../public/js/fos_js_routes.json');
    // var Routing = require('../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js');

    function updateMovieFormTags() {
        $.get(window.location.href, function(data) {
            $('#movie-form-tags').replaceWith(
                $(data).find('#movie-form-tags')
            );
        })
    }

    // création de nouveaux tags
    $('#new-tag-submit').on('click', function (event) {
        event.preventDefault();

        console.log('new tag submit');

        var newTagValue = $('#new-tag').val();
        if (newTagValue !== '' && newTagValue.length >= 3) {

            var createTagUrl = Routing.generate('tag_create');
            var data = {name: newTagValue};

            var success = function () {
                // console.log('success');
                $('#new-tag').val('');
                updateMovieFormTags();
            };

            $.ajax({
                type: "POST",
                url: createTagUrl,
                data: data,
                success: success,
                dataType: 'json'
            });

        }
    })

});