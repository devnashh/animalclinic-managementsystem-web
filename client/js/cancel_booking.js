function cancelBooking(bookingId) {
  if (!confirm("Are you sure you want to cancel this booking?")) {
    return;
  }

  fetch("../php/cancel_booking.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ booking_id: bookingId }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        location.reload(); // Reload the page to update bookings
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Error cancelling booking:", error);
      alert("An error occurred while cancelling the booking.");
    });

  data.forEach((booking) => {
    const bookingCard = document.createElement("div");
    bookingCard.classList.add("card", "mb-3");
    bookingCard.innerHTML = `
        <div class="card-body" style="box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);">
            <h5 class="card-title" style="color: #007bff">${
              booking.service_type
            }</h5>
            <div class="grid-layout">
                <div class="grid-column">
                    <strong>Date:</strong> ${booking.appointment_date}<br>
                    <strong>Time:</strong> ${booking.appointment_time}
                </div>
                <div class="grid-column">
                    <strong>Pet:</strong> ${booking.pet_name} (${
      booking.pet_type
    }, ${booking.pet_breed})<br>
                    <strong>Notes:</strong> ${booking.additional_notes || "N/A"}
                </div>
                <div class="grid-column">
                    <strong>Status:</strong> ${booking.status}
                </div>
            </div>
            <div class="button-group">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBookingModal-${
                  booking.id
                }">Edit</button>
                <button class="btn btn-danger" 
                        onclick="cancelBooking(${booking.id})" 
                        ${booking.status !== "Pending" ? "disabled" : ""}>
                    Cancel
                </button>
            </div>
        </div>`;
    bookingsList.appendChild(bookingCard);
  });
}
