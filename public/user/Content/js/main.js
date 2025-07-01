// Ẩn loader khi trang đã load xong
window.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".loader-overlay").classList.add("hidden");
});

// Hiện loader khi chuyển trang (click link hoặc submit form)
document.addEventListener(
    "click",
    function (e) {
        let target = e.target;
        if (
            target.tagName === "A" &&
            target.href &&
            !target.target &&
            !target.href.startsWith("javascript")
        ) {
            document
                .querySelector(".loader-overlay")
                .classList.remove("hidden");
        }
    },
    true
);

document.addEventListener(
    "submit",
    function (e) {
        document.querySelector(".loader-overlay").classList.remove("hidden");
    },
    true
);
