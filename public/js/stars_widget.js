$(function() {

    var starsCollection = $('#note-stars-zone span');

    function setPlainStars(untill) {
        starsCollection.each(function(index, element) {
            if (index <= untill) {
                $(element).find('i').addClass('fas').removeClass('far');
            } else {
                $(element).find('i').addClass('far').removeClass('fas');
            }
        })
    }

    function getWidgetValue() {
        return $('#note-input-zone select').val();
    }

    function setWidgetValue(value) {
        $('#note-input-zone select').val(value+1);
    }

    setPlainStars(getWidgetValue()-1); // init the thing
    $('#note-input-zone').css('display', 'none');

    starsCollection.each(function(index, element) {
        $(element)
            .hover(function() {
                setPlainStars(index);
            }, function() {
                setPlainStars(getWidgetValue()-1);
            })
            .on('click', function() {
                setWidgetValue(index);
                setPlainStars(index);
            });
    });

});