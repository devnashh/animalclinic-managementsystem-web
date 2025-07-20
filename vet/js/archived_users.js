$(document).ready(function () {
  $("#viewArchivedBookingsModal").on("show.bs.modal", function () {
    $.ajax({
      url: "php/archive_users.php", // Adjust the path to your PHP file
      type: "GET",
      dataType: "json",
      success: function (data) {
        let tableBody = $("#archivedBookingsTable");
        tableBody.empty(); // Clear previous data
        data.forEach(function (user) {
          tableBody.append(`
                      <tr>
                          <td>${user.id}</td>
                          <td>${user.full_name}</td>
                          <td>${user.email}</td>
                          <td>${user.phone_number}</td>
                          <td>${user.role}</td>
                          <td><span class="badge bg-danger">Archived</span></td>
                      </tr>
                  `);
        });
      },
    });
  });
});
