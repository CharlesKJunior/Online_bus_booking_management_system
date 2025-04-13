document.addEventListener("DOMContentLoaded", function () {
    // Get all PAY buttons
    const payButtons = document.querySelectorAll('.pay-btn');

    payButtons.forEach(function(button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            // Retrieve trip details from data attributes
            const bookingId = button.getAttribute('data-booking-id');
            const fare = parseFloat(button.getAttribute('data-fare'));
            const tripName = button.getAttribute('data-trip-name');
            const origin = button.getAttribute('data-origin');
            const stopovers = button.getAttribute('data-stopovers');
            const destination = button.getAttribute('data-destination');
            const departure = button.getAttribute('data-departure');
            const arrival = button.getAttribute('data-arrival');
            const seats = parseInt(button.getAttribute('data-seats'));

            // Calculate total fare (assuming fare is per seat)
            const totalFare = fare * seats;

            // Fill modal content with the fetched details
            document.getElementById('modalFare').textContent = "UGX " + totalFare.toLocaleString();
            document.getElementById('modalTripName').textContent = tripName;
            document.getElementById('modalOrigin').textContent = origin;
            document.getElementById('modalStopovers').textContent = stopovers;
            document.getElementById('modalDestination').textContent = destination;
            document.getElementById('modalDeparture').textContent = departure;
            document.getElementById('modalArrival').textContent = arrival;
            document.getElementById('modalSeats').textContent = seats;

            // Set the Confirm Payment button's URL to update the payment status and redirect
            document.getElementById('confirmPaymentBtn').href = "update_payment_status.php?booking_id=" + bookingId;

            // Show the modal using Bootstrap's modal functionality
            var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        });
    });
});
