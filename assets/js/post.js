const ctx = document.getElementById("ctx");

const likeBtns = document.querySelectorAll("button[type='like']");
const commentBtns = document.querySelectorAll("button[type='send-comment']");

const headers = new Headers();
headers.append('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

likeBtns.forEach((btn) => {
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
				btn.removeAttribute("liked");
				
				const amount = btn.querySelector("small").innerHTML;
				btn.querySelector("small").innerHTML = Number(amount) - 1;
			})
			.catch(error => {
				notify(error.message);
			});
		}
	});
});

commentBtns.forEach((btn) => {
	btn.addEventListener("click", (ev) => {
		const input = btn.previousElementSibling;

		fetch(ctx.value + "/post/comment", {
			method: "POST",
			headers: headers,
			body: new URLSearchParams(Object.entries({
				postId: input.getAttribute("id"),
				comment: input.value
			})).toString()
		})
		.then(async (res) => {
			const type = res.headers.get("Content-Type");

			if (!res.ok || type == null) {
				notify("Houve algum problema ao adicionar comentário");
				return;
			}

			if (!type.includes("json")) {
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
				notify("Houve algum erro ao salvar o comentário");
				return;
			}


			const comment = btn.parentElement.nextElementSibling;
			comment.innerHTML = comment.innerHTML +
			'<div class="comment">' +
				'<div class="comment-user">' + 
					'<img class="profile" src="' + ctx.value + '/' + json.user.image_path + '" />' +
					'<small><a>' + json.user.username + '</a></small>' +
				'</div>' +
				'<div class="comment-content">' +
					'<small>' + json.content + '</small>' +
				'</div>' +
				'<div class="comment-replies">' +
				'</div>' +
			'</div>';
		})
		.catch(error => {
			notify(error.message);
		})
	});
})