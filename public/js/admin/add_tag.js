$(function() {

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

        var newTagValue = $('#new-tag').val();
        if (newTagValue !== '' && newTagValue.length >= 3) {

            var createTagUrl = Routing.generate('tag_create');
            var data = {name: newTagValue};

            var success = function () {
                console.log('success');
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