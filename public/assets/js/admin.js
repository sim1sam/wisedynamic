/**
 * WiseDynamic Admin JavaScript
 */

$(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Confirm delete actions
    $('.btn-delete-confirm').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success, .alert-info').fadeOut(500);
    }, 5000);
    
    // File input preview
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
        
        // Image preview if it's an image
        let fileInput = this;
        if (fileInput.files && fileInput.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let previewElement = $(fileInput).closest('.form-group').find('.img-preview');
                if (previewElement.length) {
                    previewElement.attr('src', e.target.result);
                }
            }
            reader.readAsDataURL(fileInput.files[0]);
        }
    });
});
