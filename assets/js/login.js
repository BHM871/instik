const main = document.getElementById("main");

const registerLink = document.getElementById("register-link");
const registerBtn = document.getElementById("register-btn");

const loginLink = document.getElementById("login-link");
const loginBtn = document.getElementById("login-btn");

registerLink.addEventListener("click", function (e) {
	e.preventDefault();

	main.classList.add("deslocate");
});

loginLink.addEventListener("click", function (e) {
	e.preventDefault();

	main.classList.remove("deslocate");
});