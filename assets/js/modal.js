const modals =  document.querySelectorAll(".modal");
const btnsLoad = document.querySelectorAll("a[data-toggle=modal]");

btnsLoad.forEach((ele) => {
	const modalId = ele.getAttribute("href");

	ele.addEventListener("click", () => {
		const modal = document.querySelector(modalId);
		modal.classList.add("modal-in");

		const closeModal = document.querySelector(modalId + " [data-dismiss=modal]");

		closeModal.addEventListener("click", (clo) => {
			modal.classList.remove("modal-in")
		});
	});
});

modals.forEach((ele) => {
	ele.addEventListener("click", (ev) => {
		if (ev.target == ele)
			ele.classList.remove("modal-in");
	});
});