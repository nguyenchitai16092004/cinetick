@extends('frontend.layouts.master')
@section('title', 'Đổi mật khẩu')
@section('main')
    <div class="sign section--bg" data-bg="/Content/img/section/section.jpg"
        style="background: #e6e7e9; max-width: 100% !important; border-top: 1px solid;">
        <div class="container register" style="max-width: 100% !important;">
            <div class="row">
                <div class="col-md-3 register-left">
                    <img src="Content/img/logoCinetick.png" alt="" />
                    <p>Đổi mật khẩu tài khoản của bạn!</p>
                </div>
                <div class="col-md-9 register-right">

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h3 class="register-heading">Đổi mật khẩu</h3>
                            <div class="row register-form">
                                <div class="col-md-9">
                                    
                                    <div class="form-group">
                                        <input type="password" minlength="6" maxlength="50" name="txtEmpPhone"
                                            class="form-control" id="lgPassword" placeholder="Mật khẩu hiện tại" value="">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" minlength="6" maxlength="50" name="txtEmpPhone"
                                            class="form-control" id="lgPasswordNew" placeholder="Mật khẩu mới"
                                            value="">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" minlength="6" maxlength="50" name="txtEmpPhone"
                                            class="form-control" id="lgRePasswordNew" placeholder="Xác nhận mật khẩu mới"
                                            value="">
                                    </div>
                                    <div class="form-group">
                                        <ul style="display:flex">
                                            <li>
                                                <a href="{{ route('quen-mat-khau.get') }}">Quên mật khẩu?</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <input type="submit" class="btnRegister" onclick="changePass()" value="Đổi mật khẩu">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function MemberChangePasswordModel(oldPass, newPassWord, reNewPassWord) {
            var t = this;
            t.OldPass = oldPass,
                t.NewPass = newPassWord,
                t.ReNewPass = reNewPassWord;
        }

        function changePass() {
            $(".main-reloader").css("display", "block");
            var oldPass = $("#lgPassword").val();
            var newPassWord = $("#lgPasswordNew").val();
            var reNewPassWord = $("#lgRePasswordNew").val();
            if (oldPass == "" || oldPass == undefined) {
                $.sweetModal({
                    content: 'Vui lòng nhập mật khẩu cũ.',
                    title: '',
                    icon: $.sweetModal.ICON_WARNING,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB'
                        }
                    }
                });
                $(".main-reloader").css("display", "none");
                $("#lgPassword").focus();
                return false;
            }

            if (newPassWord == "" || newPassWord == undefined) {
                $.sweetModal({
                    content: 'Vui lòng nhập mật khẩu mới.',
                    title: '',
                    icon: $.sweetModal.ICON_WARNING,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB'
                        }
                    }
                });
                $(".main-reloader").css("display", "none");
                $("#lgPasswordNew").focus();
                return false;
            }

            if (reNewPassWord == "" || reNewPassWord == undefined) {
                $.sweetModal({
                    content: 'Vui lòng nhập lại mật khẩu mới.',
                    title: '',
                    icon: $.sweetModal.ICON_WARNING,
                    theme: $.sweetModal.THEME_DARK,
                    buttons: {
                        'OK': {
                            classes: 'redB'
                        }
                    }
                });
                $(".main-reloader").css("display", "none");
                $("#lgRePasswordNew").focus();
                return false;
            }

            var data = {
                OldPass: oldPass,
                NewPass: newPassWord,
                ReNewPass: reNewPassWord,
                _token: $('meta[name="csrf-token"]').attr('content') // Thêm dòng này
            };
            $.ajax({
                url: "{{ route('doi-mat-khau.post') }}", 
                type: "POST",
                data: JSON.stringify(data),
                datatype: "json",
                contentType: 'application/json; charset=utf-8',
                success: function(result) {
                    //alert(result);
                    if (result === "true" || result === true) {
                        $("#lgPassword").val("");
                        $("#lgPasswordNew").val("");
                        $("#lgRePasswordNew").val("");
                        $(".main-reloader").css("display", "none");
                        $.sweetModal({
                            content: 'Đổi mật khẩu thành công. Bạn có thể đăng nhập <a href="{{ asset('/') }}">tại đây</a>',
                            title: 'Thông báo',
                            icon: $.sweetModal.ICON_WARNING,
                            theme: $.sweetModal.THEME_DARK,
                            buttons: {
                                'OK': {
                                    classes: 'redB'
                                }
                            }
                        }, function(confirm) {
                            if (confirm) {
                                location.href = "{{ asset('/') }}";
                            }
                        });
                        //location.href = "/";
                    } else {
                        switch (result) {
                            case "1":
                                $(".main-reloader").css("display", "none");
                                $.sweetModal({
                                    content: 'Phiên đăng nhập của bạn đã hết hạn vui lòng đăng nhập lại. <a href="{{ asset('/') }}">tại đây</a>',
                                    title: '',
                                    icon: $.sweetModal.ICON_WARNING,
                                    theme: $.sweetModal.THEME_DARK,
                                    buttons: {
                                        'OK': {
                                            classes: 'redB'
                                        }
                                    }
                                });
                                break;
                            case "2":
                                $(".main-reloader").css("display", "none");
                                $.sweetModal({
                                    content: 'Mật khẩu cũ không chính xác',
                                    title: '',
                                    icon: $.sweetModal.ICON_WARNING,
                                    theme: $.sweetModal.THEME_DARK,
                                    buttons: {
                                        'OK': {
                                            classes: 'redB'
                                        }
                                    }
                                });
                                break;
                            case "3":
                                $(".main-reloader").css("display", "none");
                                $.sweetModal({
                                    content: 'Mật khẩu mới nhập lại không trùng khớp',
                                    title: '',
                                    icon: $.sweetModal.ICON_WARNING,
                                    theme: $.sweetModal.THEME_DARK,
                                    buttons: {
                                        'OK': {
                                            classes: 'redB'
                                        }
                                    }
                                });
                                break;
                            default:
                                $(".main-reloader").css("display", "none");
                                $.sweetModal({
                                    content: result,
                                    title: '',
                                    icon: $.sweetModal.ICON_WARNING,
                                    theme: $.sweetModal.THEME_DARK,
                                    buttons: {
                                        'OK': {
                                            classes: 'redB'
                                        }
                                    }
                                });
                                break;
                        }
                    }
                },
                error: function(xhr) {
                    $(".main-reloader").css("display", "none");
                    $.sweetModal({
                        content: 'Có lỗi xảy ra: ' + xhr.status + ' - ' + xhr.responseText,
                        title: 'Lỗi',
                        icon: $.sweetModal.ICON_WARNING,
                        theme: $.sweetModal.THEME_DARK,
                        buttons: {
                            'OK': {
                                classes: 'redB'
                            }
                        }
                    });
                }
            });
        }
    </script>
@stop
