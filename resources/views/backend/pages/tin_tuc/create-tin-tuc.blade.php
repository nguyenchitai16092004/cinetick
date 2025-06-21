@extends('backend.layouts.master')
@section('title', 'Tạo bảng tin tức')

@section('main')
    <div class="container mt-4">
        <form action="{{ route('tin_tuc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Ảnh đại diện</label>
                <br>
                <img id="preview-AnhDaiDien" src="{{ asset('images/no-image.jpg') }}" width="120" style="display:block; margin-bottom:10px;">
                <input type="file" name="AnhDaiDien" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label>Tiêu đề</label>
                <input type="text" name="TieuDe" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nội dung</label>
                <textarea name="NoiDung" class="form-control" rows="10"></textarea>
            </div>

            <div class="form-group">
                <label>Loại bài viết</label>
                <select name="LoaiBaiViet" class="form-control" required>
                    <option value="1">Khuyến Mãi</option>
                    <option value="2">Giới thiệu</option>
                    <option value="3">Chính sách</option>
                    <option value="4">Phim</option>
                </select>
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select name="TrangThai" class="form-control" required>
                    <option value="0">Chờ xuất bản</option>
                    <option value="1">Xuất bản</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm mới</button>
        </form>
    </div>
    <script src="https://cdn.tiny.cloud/1/sasoygoht1uf9889ttoe6d3ut0fkhp824q1z9fmh7zoea39y/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea[name=NoiDung]',
        plugins: [
            'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media',
            'searchreplace', 'table', 'visualblocks', 'wordcount',
            'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker',
            'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage',
            'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags',
            'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [{
                value: 'First.Name',
                title: 'First Name'
            },
            {
                value: 'Email',
                title: 'Email'
            },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
            'See docs to implement AI Assistant')),
        images_upload_url: '{{ route('tin_tuc.tinymce.upload') }}',
        images_upload_credentials: true,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true
    });
    document.querySelector('input[name="AnhDaiDien"]').addEventListener('change', function(e) {
    const input = e.target;
    const preview = document.getElementById('preview-AnhDaiDien');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
});
</script>
@endsection
