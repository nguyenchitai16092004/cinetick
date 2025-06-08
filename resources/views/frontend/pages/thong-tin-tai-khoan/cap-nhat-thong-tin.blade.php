@extends('frontend.layouts.master')
@section('title', 'Cập nhật thông tin tài khoản')
@section('main')


    <div class="sign section--bg" data-bg="/Content/img/section/section.jpg"
        style="background: #e6e7e9; max-width: 100% !important; border-top: 1px solid;">
        <div class="container register" style="max-width: 100% !important;">
            <div class="row">
                <div class="col-md-3 register-left">
                    <img src="Content/img/logoCinetick.png" alt="" />
                    <p>Đăng ký tài khoản thành viên và nhận ngay ưu đãi!</p>
                    <br>
                </div>
                <div class="col-md-9 register-right">

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h3 class="register-heading">Thông tin tài khoản</h3>
                            <form method="POST" action="{{ route('user.updateInfo.post') }}">
                                @csrf
                                <div class="row register-form">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="rgFullName" name="HoTen"
                                                value="{{ $thongTin->HoTen ?? '' }}" placeholder="Họ & tên(*)" >
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="rgCMND" name="ID_CCCD"
                                                value="{{ $thongTin->ID_CCCD ?? '' }}" placeholder="CMND(*)" readonly>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="rgEmail" name="Email"
                                                placeholder="Email (*)" value="{{ $thongTin->Email ?? '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" id="rgBirthDay" class="form-control" name="NgaySinh"
                                                placeholder="Ngày sinh"
                                                value="{{ $thongTin->NgaySinh ? \Carbon\Carbon::parse($thongTin->NgaySinh)->format('d/m/Y') : '' }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" minlength="10" maxlength="10" id="rgPhone"
                                                name="SDT" class="form-control" placeholder="Điện thoại(*)"
                                                value="{{ $thongTin->SDT ?? '' }}" >
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="maxl">
                                                <label class="radio inline">
                                                    <input type="radio" id="rgGenderTrue" name="GioiTinh" value="1"
                                                        {{ ($thongTin->GioiTinh ?? 1) == 1 ? 'checked' : '' }} >
                                                    <span> Nam </span>
                                                </label>
                                                <label class="radio inline">
                                                    <input type="radio" id="rgGenderFalse" name="GioiTinh" value="0"
                                                        {{ ($thongTin->GioiTinh ?? 1) == 0 ? 'checked' : '' }} >
                                                    <span> Nữ </span>
                                                </label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btnRegister">Cập nhật</button>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    @if (session('success'))
    <script>
        $(function() {
            $.sweetModal({
                content: `{{ session('success') }}`,
                title: 'Thông báo',
                icon: $.sweetModal.ICON_SUCCESS,
                theme: $.sweetModal.THEME_DARK,
                buttons: {
                    'OK': {
                        classes: 'redB'
                    }
                }
            });
        });
    </script>
@endif
@stop
