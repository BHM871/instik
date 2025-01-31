const ctx = document.getElementById("ctx");

const loggedUserId = document.getElementById("userId");
const likeBtns = document.querySelectorAll("button[type='like']");

likeBtns.forEach((btn) => {
	btn.addEventListener("click", (ev) => {
		const postId = btn.getAttribute("id");

		if (!btn.getAttribute("liked")) {
			fetch(ctx.value + "/post/like", {
				method: "POST",
				body: {
					userId: loggedUserId,
					postId: postId
				}
			})
			.then(res => {
				if (!res.ok)
					return;

				btn.setAttribute("liked", "true");
				
				const amount = btn.querySelector("small").innerHTML;
				btn.querySelector("small").innerHTML = Number(amount) + 1;
			})
			.catch(error => {
				notify("Houve algum erro ao curtir vídeo");
			});
		} else {
			fetch(ctx.value + "/post/unlike", {
				method: "POST",
				body: {
					userId: loggedUserId,
					postId: postId
				}
			})
			.then(res => {
				if (!res.ok)
					return;

				btn.removeAttribute("liked");
				
				const amount = btn.querySelector("small").innerHTML;
				btn.querySelector("small").innerHTML = Number(amount) - 1;
			})
			.catch(error => {
				notify("Houve algum erro ao curtir vídeo");
			});
		}
	});
});