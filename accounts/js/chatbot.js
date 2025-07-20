// Toggle Chatbot Window
document.getElementById("chatbotToggle").addEventListener("click", function () {
  const chatbotWindow = document.getElementById("chatbotWindow");
  chatbotWindow.style.display =
    chatbotWindow.style.display === "none" ? "flex" : "none";
});

// Chatbot Logic
document.getElementById("chatbotSend").addEventListener("click", function () {
  const input = document.getElementById("chatbotInput");
  const query = input.value.trim();
  if (!query) return;

  const chatbotBody = document.getElementById("chatbotBody");
  const userMessage = `<div><strong>You:</strong> ${query}</div>`;
  chatbotBody.innerHTML += userMessage;

  // Generate chatbot response
  let response = "";
  if (query.toLowerCase().includes("deworming")) {
    response = `Deworming is essential for your pet's health to prevent intestinal parasites. We use safe and effective deworming treatments at our clinic. Book an appointment today to ensure your pet stays healthy!`;
  } else if (query.toLowerCase().includes("flea")) {
    response = `To treat fleas, we recommend using vet-approved products like Frontline Plus or Advantix. Our clinic offers professional flea treatment and prevention services. Schedule an appointment now to protect your pet from fleas!`;
  } else if (query.toLowerCase().includes("vaccination")) {
    response = `Vaccination is vital to protect your pet from serious diseases. At our clinic, we provide trusted vaccines like Nobivac to keep your pet safe. Book a vaccination appointment today and give your pet the best protection!`;
  } else if (query.toLowerCase().includes("surgery")) {
    response = `Our clinic provides a range of surgical procedures, from spaying and neutering to more advanced surgeries. Rest assured, your pet will be in expert hands. Contact us and book a surgery consultation today!`;
  } else if (query.toLowerCase().includes("grooming")) {
    response = `Pamper your pet with our grooming services, including bathing, haircuts, and nail trimming. Regular grooming keeps your pet looking and feeling great. Schedule a grooming session today!`;
  } else if (query.toLowerCase().includes("supplies")) {
    response = `We offer a variety of pet supplies, from food and toys to hygiene products. Visit us or book an appointment to learn more about the best supplies for your pet's needs!`;
  } else if (query.toLowerCase().includes("home service")) {
    response = `Our home service allows your pet to receive top-quality care in the comfort of your home. From vaccinations to check-ups, we bring our services to you. Book a home service appointment today!`;
  } else if (query.toLowerCase().includes("consultation")) {
    response = `Our veterinary consultation services are here to address all your pet's health concerns. Speak with our experienced vets and get personalized care for your pet. Book a consultation appointment now!`;
  } else if (query.toLowerCase().includes("how to set appointment")) {
    response = `Setting an appointment is simple! Log in to your account and navigate to the "Book Appointment" section. Select the service you need, choose an available date and time, and confirm your booking. Book your appointment today for a seamless experience!`;
  } else if (query.toLowerCase().includes("how to book")) {
    response = `Setting an appointment is simple! Log in to your account and navigate to the "Book Appointment" section. Select the service you need, choose an available date and time, and confirm your booking. Book your appointment today for a seamless experience!`;
  } else if (query.toLowerCase().includes("how to create account")) {
    response = `Creating an account is easy! On the homepage, click "Create Account" and fill in your details, including your name, contact number, and email address. Once you submit the form, you'll be able to log in and start managing your pet's needs. Create your account now and get started!`;
  } else if (query.toLowerCase().includes("how to change password")) {
    response = `To change your password, log in and go to the "Settings" or "User Icon" at navigation section. Select "Edit profile icon" enter your current password, and then type your new password. Save the changes, and your password will be updated. Change your password now for better security!`;
  } else if (query.toLowerCase().includes("where is user profile")) {
    response = `You can find your user profile in the top navigation bar. Click on edit icon to view or edit your account information, and update your preferences. Explore your profile now to get the most out of our services!`;
  } else {
    response = `I'm not sure about that. Please contact us directly for more detailed advice or let us know how we can assist you further. Book an appointment today for expert care!`;
  }

  chatbotBody.innerHTML += `<div><strong>Chatbot:</strong> ${response}</div>`;
  input.value = "";
  chatbotBody.scrollTop = chatbotBody.scrollHeight;
});
