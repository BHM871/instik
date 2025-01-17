const message = document.getElementById('message');

if (message != null && message.checkVisibility()) {
	setTimeout(() => {
		message.classList.add("hide");
	}, 5000);
}