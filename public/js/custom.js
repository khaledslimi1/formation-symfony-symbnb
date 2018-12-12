

$('#add-image').click(function () {
    const index = +$('#widgets-counter').val();

    const template =  $('#ad_images').data('prototype').replace(/__name__/g, index);

    $('#ad_images').append(template);

    $("#widgets-counter").val(index + 1)

    deleteImageBlock();

});

function deleteImageBlock ()
{
    $('button.delete-img-block').click(function () {
        const block_deleted = $(this).data('target');
        $(block_deleted).remove();
    })
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $("#widgets")
}

deleteImageBlock();