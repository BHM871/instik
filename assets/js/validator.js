const forms = document.querySelectorAll("form");

forms.forEach((form) => {
	form.addEventListener("submit", (ev) => {
		let id = form.getAttribute("id");
		id = "form#" + id;

		let stop = false;
		let inputs = document.querySelectorAll(id + " input");
		inputs.forEach((input) => {
			if (!validate(input)) {
				stop = true;
			}
		});

		if (stop) return;
	});
});

function validate(input) {
	const type = input.getAttribute("validate-type");
	const maxLength = input.getAttribute("maxlength");
	const value = input.value;

	if (value.trim() == "") {
		notify("Algum campo está vazio");
		paintInput(input);
		return false;
	}

	if (maxLength != undefined && maxLength != null && maxLength.trim() != "") {
		if (value.length > Number(maxLength.trim())) {
			notify("Algum campo é maior do que o permitido");
			paintInput(input);
			return false;
		}
	}

	if (type == "email") {
		let regex = "/[a-z0-9._-]{5,40}\@[A-z]{2,10}\.[a-z]{2,10}\/i";
		if (!value.match(regex)) {
			notify("Preencha o campo de email corretamente");
			paintInput(input);
			return false;
		}
	}

	if (type == "password") {
		let regex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$*&@#])[0-9a-zA-Z$*&@#]{8,}$/";
		if (!value.match(regex)) {
			notify("Preencha o campo de senha corretamente");
			paintInput(input);
			return false;
		}
	}

	return true;
}

function notify(message) {
	let HTMLmessage = document.getElementById("message");
	if (HTMLmessage != null)
		HTMLmessage.remove();

	const body = document.body;
	const html = body.innerHTML;
	body.innerHTML = html +
		'<div id="message" class="message">' +
			'<p>' + message + '</p>' +
		'</div>';

	HTMLmessage = document.getElementById("message");
	setTimeout(() => {
		HTMLmessage.classList.add("hide");
	}, 5000);
}

function paintInput(input) {
	input.style.boderColor = "red !important";
}