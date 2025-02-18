let usernameInput = document.getElementById("username");
let passwordInput = document.getElementById("password");

document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    username = usernameInput.value;
    password = passwordInput.value;

    const validUsername = "admin";
    const validPassword = "admin";

    if (username === "" || password === "") {
      alert("Please fill in all fields.");
    } else if (username === validUsername && password === validPassword) {
      alert("Login successful! Redirecting to dashboard...");
      window.location.href = "dashboard.html";
    } else if (username !== validUsername && password !== validPassword) {
      alert("Invalid username and password.");
      usernameInput.value = "";
      passwordInput.value = "";
      usernameInput.focus();
    } else if (password !== validPassword) {
      alert("Invalid password.");
      passwordInput.value = "";
      passwordInput.focus();
    } else {
      alert("Invalid username.");
      usernameInput.value = "";
      usernameInput.focus();
    }
  });
document
  .getElementById("signupForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const fullname = document.getElementById("fullname").value;
    const email = document.getElementById("email").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

    if (
      fullname === "" ||
      email === "" ||
      username === "" ||
      password === "" ||
      confirmPassword === ""
    ) {
      alert("Please fill in all fields.");
    } else if (password !== confirmPassword) {
      alert("Passwords do not match.");
    } else {
      alert("Signup successful! Redirecting to login page...");
      window.location.href = "index.html";
    }
  });