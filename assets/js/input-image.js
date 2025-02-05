const labelsImg = document.querySelectorAll(".input-image");

labelsImg.forEach((label) => {
	let img = label.querySelector("img");
	let input = label.nextElementSibling != null 
		? label.nextElementSibling
		: label.previousElementSibling;

	if (input != null) {
		input.addEventListener("change", (ev) => {
			let tgt = ev.target,
			file = tgt.files[0];
   
			if (!file) {
				return;
			}

			let type = file.type;
			if (!type.includes("image")) {
				return;
			}

			if (FileReader) {
				let fr = new FileReader();
				fr.onload = function () {
					img.src = fr.result;
				}

				fr.readAsDataURL(file);
				console.log(file);
				label.classList.remove("person");
				label.classList.remove("placeholder");
		    }
		});
	}
});