// Share button logic
const shareBtn = document.getElementById("shareBtn");
shareBtn.addEventListener("click", () => {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        shareBtn.innerHTML =
            '<i class="fa-solid fa-share-nodes"></i> Đã sao chép!';
        setTimeout(
            () =>
                (shareBtn.innerHTML =
                    '<i class="fa-solid fa-share-nodes"></i> Chia sẻ'),
            1400
        );
    }
});
document.addEventListener("DOMContentLoaded", function () {
    // Khi vào bài viết, tự động tăng lượt xem
    fetch(`/goc-dien-anh/${postSlug}/view`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": Laravel.csrfToken },
    })
        .then((res) => res.json())
        .then((data) => {
            document.getElementById("viewCount").textContent = data.LuotXem;
        });

    // Kiểm tra trạng thái đã thích chưa từ localStorage
    const likedKey = `liked_goc_dien_anh_${postSlug}`;
    let liked = localStorage.getItem(likedKey) === "true";

    // Cập nhật giao diện theo trạng thái
    if (liked) {
        likeBtn.classList.add("liked");
        likeIcon.classList.remove("fa-regular");
        likeIcon.classList.add("fa-solid");
    }

    likeBtn.addEventListener("click", () => {
        if (!liked) {
            fetch(`/goc-dien-anh/${postSlug}/like`, {
                method: "POST",
                headers: { "X-CSRF-TOKEN": Laravel.csrfToken },
            })
                .then((res) => res.json())
                .then((data) => {
                    likeCount.textContent = data.LuotThich;
                    likeBtn.classList.add("liked");
                    likeIcon.classList.remove("fa-regular");
                    likeIcon.classList.add("fa-solid");
                    liked = true;
                    localStorage.setItem(likedKey, "true");
                });
        } else {
            fetch(`/goc-dien-anh/${postSlug}/unlike`, {
                method: "POST",
                headers: { "X-CSRF-TOKEN": Laravel.csrfToken },
            })
                .then((res) => res.json())
                .then((data) => {
                    likeCount.textContent = data.LuotThich;
                    likeBtn.classList.remove("liked");
                    likeIcon.classList.remove("fa-solid");
                    likeIcon.classList.add("fa-regular");
                    liked = false;
                    localStorage.setItem(likedKey, "false");
                });
        }
    });
});
