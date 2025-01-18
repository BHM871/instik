const modals =  document.querySelectorAll(".modal");
const btnsLoad = document.querySelectorAll("a[data-toggle=modal]");

btnsLoad.forEach((ele) => {
	const modalId = ele.getAttribute("href");

	ele.addEventListener("click", (ev) => {
		const modal = document.querySelector(modalId);
		modal.classList.add("modal-in");

		document.querySelectorAll(modalId + " [data-dismiss=modal]").forEach((el) => {
			el.addEventListener("click", (ev) => {
				modal.classList.remove("modal-in")
			});
		});
	});
});

modals.forEach((ele) => {
	ele.addEventListener("click", (ev) => {
		if (ev.target == ele)
			ele.classList.remove("modal-in");
	});
});