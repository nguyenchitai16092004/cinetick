document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".date-tabs .tab").forEach(function (tab) {
        tab.addEventListener("click", function () {
            document
                .querySelectorAll(".date-tabs .tab")
                .forEach((t) => t.classList.remove("active"));
            this.classList.add("active");
            const date = this.getAttribute("data-date");
            document.querySelectorAll(".films-by-date").forEach((fbd) => {
                fbd.style.display =
                    fbd.getAttribute("data-date") === date ? "" : "none";
            });
        });
    });
});
