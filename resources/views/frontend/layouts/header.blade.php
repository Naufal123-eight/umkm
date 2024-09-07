<header id="header" class="header fixed-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
            @php
                $settings=DB::table('settings')->get();
            @endphp
          <i class="bi bi-envelope d-flex align-items-center mr-2"><a href="mailto:@foreach($settings as $data) {{$data->email}} @endforeach">@foreach($settings as $data) {{$data->email}} @endforeach</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>@foreach($settings as $data) {{$data->phone}} @endforeach</span></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <a href="{{route('order.track')}}" class="twitter"><i class="ti-location-pin"></i>Lacak Pesanan</a>
          @auth
          @if(Auth::user()->role=='admin')
          <a href="{{route('admin')}}" class="facebook"><i class="ti-user"></i>Dashboard</a>
          @else
          <a href="{{route('user')}}" class="instagram"><i class="ti-user"></i>Dashboard</a>
          @endif
          <a href="{{route('user.logout')}}" class="linkedin"><i class="ti-power-off"></i>Keluar</a>
          @else
          <a href="{{route('login.form')}}" class="linkedin"><i class="ti-power-off"></i>Masuk/</a><a style="margin-left: -1.2px" href="{{route('register.form')}}">Daftar</a>
          @endauth
            @php
                $total_prod=0;
                $total_amount=0;
            @endphp
            @if(session('wishlist'))
                @foreach(session('wishlist') as $wishlist_items)
                    @php
                        $total_prod+=$wishlist_items['quantity'];
                        $total_amount+=$wishlist_items['amount'];
                    @endphp
                @endforeach
            @endif
            <a title="Keinginan" href="{{route('wishlist')}}" class="single-icon"><i class="fa fa-heart-o"></i> <span class="total-count">{{Helper::wishlistCount()}}</span></a>
            <a title="Keranjang" href="{{route('cart')}}" class="single-icon"><i class="ti-bag"></i> <span class="total-count">{{Helper::cartCount()}}</span></a>
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <img src="{{$data->logo}}" alt="" width="8%">

        <nav id="navmenu" class="navmenu">
          <ul>
            <li class="{{Request::path()=='home' ? 'active' : ''}}"><a href="{{route('home')}}">Beranda</a></li>
            <li class="{{Request::path()=='about' ? 'active' : ''}}"><a href="{{route('about-us')}}">Tentang Kami</a></li>
            <li class="@if(Request::path()=='product-grids'||Request::path()=='product-lists')  active  @endif"><a href="{{route('product-grids')}}">Produk</a></li>
            {{Helper::getHeaderCategory()}}
            <li class="{{Request::path()=='contact' ? 'active' : ''}}"><a href="{{route('contact')}}">Kontak</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>

    </div>

  </header>
