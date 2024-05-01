
// Function to calculate the total amount
function calculateTotalAmount() {
    var totalAmount = 0;
    var totalAmountSpan = document.getElementById("totalAmount");
    var priceText = totalAmountSpan.textContent;
    totalAmount = parseFloat(priceText.substring(1)); // Extract the numeric value from the text
    return totalAmount.toFixed(2); // Return the total amount rounded to 2 decimal places
}

// Function to update the total amount span
function updateTotalAmount(amount) {
    var totalAmountSpan = document.getElementById("totalAmount");
    totalAmountSpan.textContent = "$" + amount; // Update the span with the total amount
}
    function promoCodeValidation(promoCode) {
        // Add your promo code validation logic here
        return promoCode === "matchmate01!"; // Return true if the promo code matches, false otherwise
    }
// Function to handle the promo code application
function applyPromoCode() {
    var promoCodeInput = document.getElementById("promoCodeInput");
    var promoCode = promoCodeInput.value;

    if (promoCodeValidation(promoCode)) {
        // Apply the promo code logic here
        // For example, calculate the new total amount and update the span with the result
        var totalAmount = calculateTotalAmount();
        var discountedAmount = totalAmount - 10; // Subtract $10 for example
        updateTotalAmount(discountedAmount.toFixed(2)); // Update the span with the discounted amount
    } else {
        // Display an error message or take appropriate action for an invalid promo code
        alert("Invalid promo code!");
    }
}


