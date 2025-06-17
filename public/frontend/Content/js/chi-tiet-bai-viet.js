const likeBtn = document.getElementById("likeBtn");
const likeCount = document.getElementById("likeCount");
const likeIcon = document.getElementById("likeIcon");
let liked = false,
    likes = 0;

likeBtn.addEventListener("click", () => {
    if (!liked) {
        likes += 1;
        likeBtn.classList.add("liked");
        likeIcon.classList.remove("fa-regular");
        likeIcon.classList.add("fa-solid");
        likeBtn.style.transform = "scale(1.07)";
        setTimeout(() => (likeBtn.style.transform = "scale(1)"), 120);
    } else {
        likes -= 1;
        likeBtn.classList.remove("liked");
        likeIcon.classList.remove("fa-solid");
        likeIcon.classList.add("fa-regular");
    }
    liked = !liked;
    likeCount.textContent = likes;
});

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
