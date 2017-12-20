var $collectionHolder;

// setup an "add a Photo" link
var $addPhotoLink = $('<a href="#" class="add_photo_link">Add a photo</a>');
var $newLinkLi = $('<li></li>').append($addPhotoLink);

jQuery(document).ready(function() {

    function addPhotoForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    }

    
    // Get the ul that holds the collection of photos
    $collectionHolder = $('ul.photos');

    // add the "add a photo" anchor and li to the photos ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addPhotoLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new photo form (see next code block)
        addPhotoForm($collectionHolder, $newLinkLi);
    });
});
