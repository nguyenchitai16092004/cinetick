document
    .querySelector(".header__search button")
    .addEventListener("click", function () {
        document.querySelector(".header__search input").focus();
    });

function logOut() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    });

    $.ajax({
        url: "{{ route('logout') }}",
        type: "POST",
        success: function (result) {
            window.location.href = "{{ url('/') }}";
        },
        error: function (xhr) {
            console.error("Lỗi đăng xuất:", xhr.responseText);
        },
    });
}
