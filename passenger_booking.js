$(document).ready(function() {
    $("#booking-form").submit(function(event) {
        event.preventDefault();

        let destination = $("#destination").val().trim();
        if (destination === "") {
            alert("Please enter a destination.");
            return;
        }

        $.ajax({
            url: "process_booking.php",
            method: "POST",
            data: { destination: destination },
            dataType: "json",
            beforeSend: function() {
                $("#available-routes").html("<p>Loading routes...</p>");
            },
            success: function(response) {
                if (response.error) {
                    $("#available-routes").html("<p class='text-danger'>" + response.error + "</p>");
                    return;
                }

                let output = "<h3>Available Routes & Buses</h3><ul class='list-group'>";
                response.buses.forEach(function(bus) {
                    output += `
                        <li class="list-group-item">
                            <strong>Bus Name:</strong> ${bus.bus_name} <br>
                            <strong>Route:</strong> ${bus.route.start_point} → ${bus.route.end_point} <br>
                            <strong>Seats Available:</strong> ${bus.seats_available} <br>
                            <strong>Fare:</strong> $${bus.fare} <br>
                            <button class="btn btn-success mt-2" onclick="proceedToPayment('${bus.id}', '${bus.fare}')">Book Now</button>
                        </li>
                    `;
                });
                output += "</ul>";
                $("#available-routes").html(output);
            },
            error: function() {
                $("#available-routes").html("<p class='text-danger'>Error fetching data.</p>");
            }
        });
    });
});

function proceedToPayment(busId, fare) {
    let confirmBooking = confirm(`The fare is $${fare}. Proceed to payment?`);
    if (confirmBooking) {
        window.location.href = `payment.php?bus_id=${busId}&fare=${fare}`;
    }
}
