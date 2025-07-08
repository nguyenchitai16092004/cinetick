@extends('admin.layouts.master')

@section('title', 'Thêm tin tức mới')

@section('main')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 text-primary font-weight-bold mb-0">
            <i class="fas fa-newspaper"></i> Thêm tin tức mới
        </h1>
        <a href="{{ route('tin_tuc.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
    <div class="card shadow border-0">
        <div class="card-header text-white" style="background-color: rgb(111, 66, 193)">
            <h5 class="mb-0 font-weight-bold">Thông tin tin tức</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tin_tuc.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3 text-center">
                    <label class="fw-bold">Ảnh đại diện</label><br>
                    <img id="preview-AnhDaiDien" src="{{ asset('images/no-image.jpg') }}" width="120"
                        style="display:block; margin:0 auto 10px;">
                    <input type="file" name="AnhDaiDien" class="form-control" accept="image/*">
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="TieuDe" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Nội dung <span class="text-danger">*</span></label>
                    <textarea name="NoiDung" class="form-control" rows="10"></textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Loại bài viết <span class="text-danger">*</span></label>
                    <select name="LoaiBaiViet" class="form-control" required>
                        <option value="1">Khuyến Mãi</option>
                        <option value="2">Giới thiệu</option>
                        <option value="3">Chính sách</option>
                        <option value="4">Phim</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="fw-bold">Trạng thái <span class="text-danger">*</span></label>
                    <select name="TrangThai" class="form-control" required>
                        <option value="0">Chờ xuất bản</option>
                        <option value="1">Xuất bản</option>
                    </select>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Lưu tin tức
                    </button>
                    <a href="{{ route('tin_tuc.index') }}" class="btn btn-outline-secondary ml-2 px-4">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
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
            'advtemplate', 'ai', /* 'mentions', */ 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags',
            'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' }
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
            'See docs to implement AI Assistant')),
        images_upload_url: '{{ route('tin_tuc.upload_image') }}',
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