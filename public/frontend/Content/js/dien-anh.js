document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-like").forEach(function (btn) {
        const slug = btn.getAttribute("data-slug");
        const likedKey = `liked_goc_dien_anh_${slug}`;
        let liked = localStorage.getItem(likedKey) === "true";
        const icon = btn.querySelector("i");
        const likeCountSpan = btn.querySelector("span");

        if (liked) {
            btn.classList.add("liked");
            icon.classList.remove("fa-regular");
            icon.classList.add("fa-solid");
        }

        btn.addEventListener("click", function (e) {
            e.preventDefault();
            if (!liked) {
                fetch(`/goc-dien-anh/${slug}/like`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.Laravel.csrfToken,
                        Accept: "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        likeCountSpan.textContent = data.LuotThich;
                        btn.classList.add("liked");
                        icon.classList.remove("fa-regular");
                        icon.classList.add("fa-solid");
                        liked = true;
                        localStorage.setItem(likedKey, "true");
                    });
            } else {
                fetch(`/goc-dien-anh/${slug}/unlike`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.Laravel.csrfToken,
                        Accept: "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        likeCountSpan.textContent = data.LuotThich;
                        btn.classList.remove("liked");
                        icon.classList.remove("fa-solid");
                        icon.classList.add("fa-regular");
                        liked = false;
                        localStorage.setItem(likedKey, "false");
                    });
            }
        });
    });
});
