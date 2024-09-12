import $ from "jquery";

// Make jQuery available globally
window.$ = window.jQuery = $;

// Ensure that csrf_token is defined and accessible
(function () {
    $(".item-quantity").on("change", function (e) {
        const itemId = $(this).data("id");
        const quantity = $(this).val();

        // Perform the AJAX request to update the cart
        $.ajax({
            url: `/cart/${itemId}`,
            method: "PUT",
            data: {
                quantity: quantity,
                _token: csrf_token, // Make sure csrf_token is available
            },
            success: function (response) {
                // Handle success (e.g., update the cart UI or notify the user)
                console.log("Cart updated successfully:", response);
            },
            error: function (xhr, status, error) {
                // Handle error (e.g., display an error message)
                console.error("Failed to update cart:", error);
                alert(
                    "There was an issue updating the cart. Please try again."
                );
            },
        });
    });
})();
