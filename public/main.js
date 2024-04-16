document.addEventListener('DOMContentLoaded', function() {
    // Get all purchase buttons
    const purchaseButtons = document.querySelectorAll('.purchase-btn');

    // Add event listener to each purchase button
    purchaseButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Display SweetAlert2 alert
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Product added to cart.',
                confirmButtonText: 'Go to Cart'
            }).then((result) => {
                // Redirect to cart page if 'Go to Cart' is clicked
                if (result.isConfirmed) {
                    window.location.href = '#'; // Change '/cart' to your cart page URL
                }
            });
        });
    });
});

function confirmDelete() {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            document.getElementById('deleteForm').submit();
        }
    });
}

