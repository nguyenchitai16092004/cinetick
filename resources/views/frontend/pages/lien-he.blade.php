@extends('frontend.layouts.master')
@section('title', 'Liên hệ')
@section('main')
    <section class="filmoja-blog-page container bg-main section_15"
        style="background: #e6e7e9; max-width: 100% !important; border-top: 1px solid;">
        <div class="container">
            <main class="contact-page ptb100">
                <div class="container">
                    <div class="row">


                        <!-- Start of Contact Details -->
                        <div class="col-md-4 col-sm-12">
                            <h3 class="title"
                                style=" margin-bottom: 15px; text-transform: uppercase; font-weight: 500; color: #444444; text-decoration: underline; background-image: url(Content/img/tag.png); -webkit-background-size: 21px 6px; background-size: 21px 6px; background-position: left center; background-repeat: no-repeat; padding-left: 30px;">
                                Thông tin</h3>

                            <div class="details-wrapper">
                                <ul class="contact-details">
                                    <li>
                                        <i class="icon-phone"></i>
                                        <strong>Hotline:</strong>
                                        <span>19001722</span>
                                    </li>
                                    <li>
                                        <i class="icon-printer"></i>
                                        <strong>TRỤ SỞ CHÍNH:</strong>
                                        <span> 39 TRẦN KHÁNH DƯ, PHƯỜNG TÂN LỢI, TP. BUÔN MA THUỘT, TỈNH ĐẮK LẮK, VIỆT NAM
                                        </span>
                                    </li>
                                    <li>
                                        <i class="icon-globe"></i>
                                        <strong>Web:</strong>
                                        <span><a href="#">www.starlight.vn</a></span>
                                    </li>
                                    <li>
                                        <i class="icon-paper-plane"></i>
                                        <strong>E-Mail:</strong>
                                        <span><a href="#">support@starlight.vn</a></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Start of Contact Details -->
                        <!-- Start of Contact Form -->
                        <div class="col-md-8 col-sm-12">
                            <h3 class="title"
                                style=" margin-bottom: 15px; text-transform: uppercase; font-weight: 500; color: #444444; text-decoration: underline; background-image: url(Content/img/tag.png); -webkit-background-size: 21px 6px; background-size: 21px 6px; background-position: left center; background-repeat: no-repeat; padding-left: 30px;">
                                Gửi liên hệ</h3>

                            <!-- Start of Contact Form -->
                            <form id="contact-form">

                                <!-- contact result -->
                                <div id="contact-result"></div>
                                <!-- end of contact result -->
                                <!-- Form Group -->
                                <div class="form-group">
                                    <input class="form-control input-box" type="text" name="name" id="cName"
                                        placeholder="Họ tên" autocomplete="off">
                                </div>

                                <!-- Form Group -->
                                <div class="form-group">
                                    <input class="form-control input-box" type="email" name="email" id="cEmail"
                                        placeholder="your-email@gmail.com" autocomplete="off">
                                </div>


                                <!-- Form Group -->
                                <div class="form-group">
                                    <input class="form-control input-box" type="text" name="subject" id="cPhone"
                                        placeholder="SĐT" autocomplete="off">
                                </div>

                                <!-- Form Group -->
                                <div class="form-group mb20">
                                    <textarea class="form-control textarea-box" rows="8" id="cContent" name="message"
                                        placeholder="Nội dung cần liên hệ..."></textarea>
                                </div>

                                <!-- Form Group -->
                                <div class="form-group text-center">
                                    <button class="btn btn-main btn-effect" type="submit"
                                        style=" background: #f37a3b; color: #fff;" onclick="sendContact()">Send</button>
                                </div>
                            </form>
                            <!-- End of Contact Form -->
                        </div>
                        <!-- Start of Contact Form -->

                    </div>
                </div>
            </main>
        </div>
    </section>
@stop
