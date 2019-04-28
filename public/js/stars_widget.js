$(function() {
    
    var commentNoteWidget = $('#randomovies_comment_note');
    commentNoteWidget.hide();

    var starsCollection = $('#stars-zone i');

    function setPlainStars(untill) {
        starsCollection.each(function(index, element) {
            if (index <= untill) {
                $(element).addClass('fas').removeClass('far');
            } else {
                $(element).addClass('far').removeClass('fas');
            }
        })
    }

    function getWidgetValue() {
        return commentNoteWidget.val();
    }

    function setWidgetValue(value) {
        commentNoteWidget.val(value + 1);
    }

    setPlainStars(getWidgetValue() - 1); // init the thing
    
    starsCollection.each(function(index, element) {
        $(element)
            .hover(function() {
                setPlainStars(index);
            }, function() {
                setPlainStars(getWidgetValue() - 1);
            })
            .on('click', function() {
                setWidgetValue(index);
                setPlainStars(index);
            });
    });
    
});