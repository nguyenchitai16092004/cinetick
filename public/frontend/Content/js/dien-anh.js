function likeMovie(btn) {
    var countSpan = btn.parentElement.querySelector(".like-num");
    var icon = btn.querySelector("i");
    var liked = btn.classList.toggle("liked");
    // Toggle icon style
    if (liked) {
        icon.classList.remove("fa-regular");
        icon.classList.add("fa-solid");
        let current = parseInt(countSpan.textContent, 10);
        countSpan.textContent = current + 1;
    } else {
        icon.classList.remove("fa-solid");
        icon.classList.add("fa-regular");
        let current = parseInt(countSpan.textContent, 10);
        countSpan.textContent = current - 1;
    }
}
