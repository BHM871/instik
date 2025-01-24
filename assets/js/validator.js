const forms = document.querySelectorAll("form");

forms.forEach((form) => {
	form.addEventListener("submit", (ev) => {
		ev.preventDefault();
		// return;

		let stop = false;
		let inputs = form.querySelectorAll("input[required]");
		inputs.forEach((input) => {
			if (!isValid(input)) {
				stop = true;
			}
		});

		if (stop) return;

		form.submit();
	});
});

function isValid(input) {
	const type = input.getAttribute("validate-type");
	const maxLength = input.getAttribute("maxlength");
	const value = input.value;

	if (value.trim() == "") {
		notify("Algum campo está vazio");
		error(input);
		return false;
	}

	if (maxLength != undefined && maxLength != null && maxLength.trim() != "") {
		if (value.length > Number(maxLength.trim())) {
			notify("Algum campo é maior do que o permitido");
			error(input);
			return false;
		}
	}

	if (type == "email") {
		let regex = /[a-z0-9\._-]{5,40}\@[a-z0-9]{2,10}(\.[a-z]{2,10})+/i;
		if (!value.match(regex)) {
			notify("Algum campo de email está incorreto");
			error(input);
			return false;
		}
	}

	if (type == "password") {
		let regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$*&@#])[0-9a-zA-Z$*&@#]{8,}$/;
		if (!value.match(regex)) {
			notify("Preencha o campo de senha corretamente");
			error(input);
			return false;
		}
	}

	return true;
}

function notify(message) {
	let HTMLmessage = document.getElementById("message");
	if (HTMLmessage != null)
		HTMLmessage.remove();

	const p = document.createElement("p");
	p.innerHTML = message;

	const div = document.createElement("div");
	div.setAttribute("id", "message");
	div.setAttribute("class", "message");
	div.appendChild(p);

	document.body.appendChild(div);

	HTMLmessage = document.getElementById("message");
	setTimeout(() => {
		HTMLmessage.classList.add("hide");
	}, 5000);
}

function error(input) {
	input.classList.add("error");
	input.addEventListener("input", () => {input.classList.remove("error")})
}