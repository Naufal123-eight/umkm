@extends('frontend.layouts.master')

@section('title','Checkout page')

@section('main-content')
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{config('midtrans.client_key')}}"></script>
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Beranda<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="checkout-form">
                        <div class="card shadow-sm border-1 mb-4">
                            <div class="card-header bg-dark text-white text-center">
                                <h5 class="mb-0">Data Pembeli</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3">Cek Kembali Data Diri Anda Sebelum Melakukan Pembayaran</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item bg-transparent d-flex align-items-center">
                                        <i class="fas fa-user me-2"></i>
                                        <span><strong>Nama:</strong> {{$order->first_name}}</span>
                                    </li>
                                    <li class="list-group-item bg-transparent d-flex align-items-center">
                                        <i class="fas fa-envelope me-2"></i>
                                        <span><strong>Email:</strong> {{$order->email}}</span>
                                    </li>
                                    <li class="list-group-item bg-transparent d-flex align-items-center">
                                        <i class="fas fa-phone me-2"></i>
                                        <span><strong>Nomor Telepon:</strong> {{$order->phone}}</span>
                                    </li>
                                    <li class="list-group-item bg-transparent d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <span><strong>Alamat:</strong> {{$order->address1}}, {{$order->post_code}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="order-details">
                        <!-- Order Widget -->
                        <div class="single-widget">
                            <h2>TOTAL BELANJA</h2>
                            <div class="content">
                                <ul>
                                        <li class="last"  id="order_total_price">Total<span>Rp{{number_format($order->total_amount,2)}}</span></li>
                                        <li class="last"  id="order_total_price">Total<span>Rp{{number_format($order->total_amount,2)}}</span></li>
                                </ul>
                            </div>
                        </div>
                        <!--/ End Order Widget -->
                        <!-- Payment Method Widget -->
                        <div class="single-widget payement">
                            <div class="content">
                            </div>
                        </div>
                        <!--/ End Payment Method Widget -->
                        <!-- Button Widget -->
                        <div class="single-widget get-button">
                            <div class="content">
                                <div class="button">
                                    <button id="pay-button" class="btn">bayar sekarang</button>
                                </div>
                            </div>
                        </div>
                        <!--/ End Button Widget -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Checkout -->

    <!-- Start Shop Services Area  -->
    <!-- End Shop Services -->

@endsection
@push('styles')
	<style>
        .list-group-item {
            border: none; /* Menghilangkan border pada list item */
        }
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function (event) {
            event.preventDefault();
            window.snap.pay('{{$snapToken}}', {
                onSuccess: function (result) {
                    alert("Pembayaran Berhasil!");
                    window.location.href="{{route('user.order.index')}}";

                },
                onPending: function (result) {
                    alert("Menunggu Pembayaran Anda!");
                    window.location.href="{{route('user.order.index')}}";
                },
                onError: function (result) {
                    alert("Pembayaran Gagal!");
                    window.location.href="{{route('user.order.index')}}";
                },
                onClose: function () {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        });
    </script>

@endpush
