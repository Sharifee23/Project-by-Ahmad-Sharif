$(document).ready(function () {
    $('#addProductForm').on('submit', function (e) {
        e.preventDefault(); // Prevent form from submitting normally

        let formData = $(this).serialize(); // Get form data

        $.ajax({
            url: "{{ url('add_product') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.success) {
                    $('#successMessage').text(response.message).show(); // Show success message
                    $('#addProductForm')[0].reset(); // Reset the form
                }
            },
            error: function (xhr) {
                console.error("Error:", xhr.responseJSON);
            }
        });
    });
});