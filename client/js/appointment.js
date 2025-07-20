document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded and parsed");

  // Check if the calendar element exists
  const calendarEl = document.getElementById("calendar");
  if (!calendarEl) {
    console.error("Calendar element not found!");
    return; // Stop further execution if the calendar element is missing
  }

  // Initialize FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    selectable: true,
    select: function (info) {
      alert("Selected Date: " + info.startStr); // Replace with actual logic
    },
  });
  calendar.render();
  console.log("Calendar initialized successfully");

  // Initialize Flatpickr for Time Picker
  flatpickr("#timePicker", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "h:i K",
    minTime: "08:00",
    maxTime: "17:00",
    time_24hr: false,
  });
  console.log("Flatpickr initialized successfully");
});
