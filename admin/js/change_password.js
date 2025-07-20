document.addEventListener("DOMContentLoaded", function () {
  const changePasswordForm = document.getElementById("changePasswordForm");

  changePasswordForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const currentPassword = document.getElementById("currentPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    fetch("php/change_password.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        currentPassword: currentPassword,
        newPassword: newPassword,
        confirmPassword: confirmPassword,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(data.success);
          // Optionally close the modal
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("changePasswordModal")
          );
          modal.hide();
          changePasswordForm.reset();
        } else if (data.error) {
          alert(data.error);
        }
      })
      .catch((error) => console.error("Error:", error));
  });
});
