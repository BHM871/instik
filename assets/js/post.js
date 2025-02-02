const ctx = document.getElementById("ctx");

const likeBtns = document.querySelectorAll("button[type='like']");

likeBtns.forEach((btn) => {
	const headers = new Headers();
	headers.append('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

	btn.addEventListener("click", (ev) => {
		const postId = btn.getAttribute("id");

		if (!btn.getAttribute("liked")) {
			fetch(ctx.value + "/post/like", {
				method: "POST",
				headers: headers,
				body: new URLSearchParams(Object.entries({
					postId: postId
				})).toString()
			})
			.then(async (res) => {
				const type = res.headers.get('Content-Type');

				if (!res.ok || type == null) {
					notify("Houve algum erro ao curtir vídeo");
					return;
				}

				if (!type.includes('json')) {
					throw new Error(await res.text());
				}

				const copy = res.clone();

				try {
					await copy.json();
					return res.json();
				} catch (error) {
					throw new Error(await res.text());
				}
			})
			.then(json => {
				if (!json.success) {
					notify("Houve algum erro ao curtir vídeo");
					return;
				}

				btn.setAttribute("liked", "true");
				
				const amount = btn.querySelector("small").innerHTML;
				btn.querySelector("small").innerHTML = Number(amount) + 1;
			})
			.catch(error => {
				notify(error.message);
			});
		} else {
			fetch(ctx.value + "/post/unlike", {
				method: "POST",
				body: {
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