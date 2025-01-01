const registerContent = document.getElementById("register");
const registerLink = document.getElementById("register-link");
const registerBtn = document.getElementById("register-btn");

const loginContent = document.getElementById("login");
const loginLink = document.getElementById("login-link");
const loginBtn = document.getElementById("login-btn");

registerLink.addEventListener("click", function (e) {
	registerContent.classList.remove("hide");
	loginContent.classList.add("hide");
});

loginLink.addEventListener("click", function (e) {
	loginContent.classList.remove("hide");
	registerContent.classList.add("hide");
});