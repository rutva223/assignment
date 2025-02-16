$(document).on('click', 'a[data-ajax-popup="true"]', function () {
    var title = $(this).data("title") || $(this).data("bs-original-title");
    var size = $(this).data('size') || 'md';
    var url = $(this).data('url');

    // Update Modal Title and Size
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").removeClass().addClass('modal-dialog modal-' + size);

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            $('#commonModal .modal-body').html(data);
            $("#commonModal").modal('show'); // Open modal after content loads
        },
        error: function (xhr) {
            var errorMsg = xhr.responseJSON?.error || 'Something went wrong!';
            show_toastr('Error', errorMsg, 'error');
        }
    });
});
