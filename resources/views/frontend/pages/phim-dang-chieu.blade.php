@extends('frontend.layouts.master')
@section('title', 'Phim đang chiếu')
@section('main')
    <section class="filmoja-movie-list-area section_30 container bg-main"
        style="min-height: 600px; background: #e6e7e9; max-width: 100% !important; border-top: 1px solid; ">
        <div class="container">
            <div class="movie-grid-box list-film">
                <!-- Single Movie List Start -->
                <div class="amy-mv-grid layout3" style="text-align:center">
                    @foreach ($dsPhimDangChieu as $phim)
                        <div class="col-lg-3 col-md-6 col-sm-12 grid-item" style="float:left" onclick="">
                            <article class="entry-item">
                                <div class="front">
                                    <div class="entry-thumb">
                                        <img src="{{ $phim->HinhAnh }}">
                                    </div>
                                    <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}">
                                        <h4 class="entry-title">{{ $phim->TenPhim }} (T{{ $phim->DoTuoi }})</h4>
                                    </a>
                                    <div class="entry-genre">
                                        <p>{{ $phim->theLoai->TenTheLoai ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="back">
                                    <h3 class="entry-title">
                                        <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}">{{ $phim->TenPhim }}
                                            (T{{ $phim->DoTuoi }})</a>
                                    </h3>
                                    <span class="pg">P</span>
                                    <div class="movie-char-info-left">
                                        <p style="font-style:italic">
                                        <p>{{ $phim->theLoai->TenTheLoai ?? '' }}</p>
                                        </p>
                                    </div>
                                    <div class="entry-time">
                                        <i class="fa fa-clock-o">
                                        </i>@php
                                            $gio = floor($phim->ThoiLuong / 60);
                                            $phut = $phim->ThoiLuong % 60;
                                        @endphp
                                        {{ $gio > 0 ? $gio . ' giờ ' : '' }}{{ $phut > 0 ? $phut . ' phút' : '' }}
                                    </div>
                                    <p></p>
                                    <p>{{ Str::words($phim->MoTaPhim, 40, '...') }}</p>
                                    <p></p>
                                    <div class="entry-button">
                                        <a href="{{ $phim->Trailer }}" class="fancybox.iframe amy-fancybox play-video">
                                            <i aria-hidden="true" class="fa fa-play"></i>Trailer
                                        </a>
                                        <a href="{{ route('phim.chiTiet', ['slug' => $phim->Slug]) }}">
                                            <i aria-hidden="true" class="fa fa-ticket"></i>Đặt vé
                                        </a>
                                    </div>
                                    <div class="movie-char-info">

                                        <div class="clearfix"></div>
                                        <div class="movie-char-info-left">
                                            <h6>Đạo diễn</h6>
                                            <span>{{ $phim->DaoDien }}</span>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="movie-char-info-right">
                                            <h6>Diễn viên</h6>
                                            <span>{{ $phim->DienVien }}</span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>
                            </article>
                        </div>
                    @endforeach
                    <!-- Single Movie List End -->
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </section>
@stop
