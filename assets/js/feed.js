const ctx = document.getElementById("ctx");

const filtersForm = document.getElementById("filters-form");
const filtersSelects = document.querySelectorAll("select.filter");

filtersSelects.forEach((select) => {
	select.addEventListener("change", (ev) => {
		const value = ev.target.value;
		if (value != null && value != undefined && value.trim() != "") {
			filtersForm.submit();
		}
	});
});