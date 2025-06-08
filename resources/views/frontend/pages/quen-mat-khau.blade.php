@extends('frontend.layouts.master')
@section('title', 'Quên mật khẩu')
@section('main')
    <div class="sign section--bg" data-bg="/Content/img/section/section.jpg"
        style="background: #e6e7e9; max-width: 100% !important; border-top: 1px solid;">
        <div class="container register" style="max-width: 100% !important;">
            <div class="row">
                <div class="col-md-3 register-left">
                    <img src="Content/img/logo-white.png" alt="" />
                    <p>Nhập Email để reset lại mật khẩu của bạn!</p>
                </div>
                <div class="col-md-9 register-right">

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h3 class="register-heading">Lấy lại mật khẩu</h3>
                            <div class="row register-form">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="fgEmail" placeholder="Email"
                                            value="">
                                    </div>
                                    <input type="submit" class="btnRegister" onclick="forgotPass()"
                                        value="Lấy lại mật khẩu">
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
        function forgotPass() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".main-reloader").css("display", "block");
            var email = $("#fgEmail").val();
            if (email == "" || email == undefined) {
                $.sweetModal({
                    content: 'Vui lòng nhập Email để lấy lại mật khẩu',
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
                return false;
            }

            var data = JSON.stringify({
                email: email,
                _token: $('meta[name="csrf-token"]').attr('content')
            });
            $.ajax({
                url: "{{ asset('/quen-mat-khau') }}",
                type: "POST",
                data: data,
                traditional: true,
                datatype: "json",
                contentType: 'application/json; charset=utf-8',
                success: function(result) {
                    //alert(result);
                    if (result === "true" || result === true) {
                        $(".main-reloader").css("display", "none");
                        $.sweetModal({
                            content: 'Vui lòng kiểm tra email để nhận mật khẩu mới. Và đăng nhập <a href="{{ route('login.form') }}">tại đây</a>',
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

                    } else {
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
                    }
                },
                error: function(xhr) {
                    $(".main-reloader").css("display", "none");
                    $.sweetModal({
                        content: xhr.responseText || 'Có lỗi xảy ra!',
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
