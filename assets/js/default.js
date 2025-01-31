const message = document.getElementById('message');

if (message != null && message.checkVisibility()) {
	setTimeout(() => {
		message.classList.add("hide");
	}, 5000);
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