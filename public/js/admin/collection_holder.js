$(function(){

    $collectionHolder = $('ul.form-collection');
    $collectionHolder.data('index', $collectionHolder.find('li').length); // divisé par 3 car compte le nombre de champs

    $collectionHolder.on('click', '.delete-button', function(event) {
        $(event.target).closest('li').remove();
    });

    function addItemToCollection($collectionHolder) {

        var prototype = $collectionHolder.data('prototype');

        var index = $collectionHolder.data('index');

        var newForm = prototype;
        // You need this only if you didn't set 'label' => false in your tags field in TaskType
        // Replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__label__/g, index);

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        // var $newFormLi = $('<li></li>').append(newForm);
        $collectionHolder.append(newForm);

    }

    $addButton = $('.add_item_to_collection_btn');

    $addButton.on('click', function(e) {
        e.preventDefault();

        addItemToCollection($collectionHolder);
    });
    
});