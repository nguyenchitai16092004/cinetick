@extends('user.layouts.master')
@section('title', 'CineTick - Danh sách tin khuyến mãi')
@section('main')
    <link rel="stylesheet" href="{{ asset('user/Content/css/khuyen-mai.css') }}">
    <link rel="stylesheet" href="{{ asset('user/Content/css/home.css') }}">

    <div class="bg-gradient"></div>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="promotion-section">
        <header class="section-header">
            <h1 class="section-title">Tin khuyến mãi</h1>
        </header>
        @if ($khuyenMais->count())
          <div class="promotion-list">
            @foreach ($khuyenMais as $index => $khuyenMai)
              <a href="{{ route('bai-viet.chiTiet.khuyen-mai', ['slug' => $khuyenMai->Slug]) }}">
                <div class="promotion-card">
                  <img class="promotion-img"
                    src="{{ $khuyenMai->AnhDaiDien ? asset('storage/' . $khuyenMai->AnhDaiDien) : asset('images/no-image.jpg') }}"
                    alt="{{ $khuyenMai->TieuDe }}">
                  <div class="promotion-overlay">
                    <div class="promotion-overlay-title">
                      {{ $khuyenMai->TieuDe }}
                    </div>
                  </div>
                </div>
              </a>
              @if (($index + 1) % 4 == 0 && $index + 1 < $khuyenMais->count())
                </div><div class="promotion-list">
              @endif
            @endforeach
          </div>
        @else
          <p class="update-showtime">
            <span class="marquee-text">Ôi không! Các chương trình khuyến mãi đâu rồi? Đừng lo lắng chúng tôi sẽ cập nhật chúng ngay thôi.</span>
          </p>
        @endif
        <div class="pagination-wrapper">
            {{ $khuyenMais->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop