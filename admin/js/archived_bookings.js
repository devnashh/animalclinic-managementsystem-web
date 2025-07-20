document.addEventListener("DOMContentLoaded", function () {
    const archivedBookingsTable = document.getElementById("archivedBookingsTable");

    function fetchArchivedBookings() {
        fetch("php/archive_bookings.php")
            .then(response => response.json())
            .then(data => {
                archivedBookingsTable.innerHTML = ""; // Clear existing data
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(booking => {
                        const row = `
                        <tr>
                            <td>${booking.id}</td>
                            <td>${booking.client_id}</td>
                            <td>${booking.pet_id}</td>
                            <td>${booking.service_type}</td>
                            <td>${booking.appointment_date}</td>
                            <td>${booking.status || "Archived"}</td>
                        </tr>
                    `;
                        archivedBookingsTable.innerHTML += row;
                    });
                } else {
                    archivedBookingsTable.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">No archived bookings found.</td>
                    </tr>
                `;
                }
            })
            .catch(error => console.error("Error fetching archived bookings:", error));
    }

    // Load archived bookings when the modal is opened
    const viewArchivedBookingsModal = document.getElementById("viewArchivedBookingsModal");
    viewArchivedBookingsModal.addEventListener("show.bs.modal", fetchArchivedBookings);
});