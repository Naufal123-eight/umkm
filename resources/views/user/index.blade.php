@extends('user.layouts.master')

@section('main-content')
<div class="container-fluid">
    @include('user.layouts.notification')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        @php
            $orders = DB::table('orders')->where('user_id', auth()->user()->id)->paginate(10);
        @endphp
        <div class="col-xl-12 col-lg-12">
            <table class="table table-bordered" id="order-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jumlah</th>
                        <th>Total Belanja</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jumlah</th>
                        <th>Total Belanja</th>
                        <th>Status</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="text-center justify-content-center">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$order->order_number}}</td>
                            <td>{{$order->first_name}}</td>
                            <td>{{$order->email}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>Rp{{number_format($order->total_amount, 2)}}</td>
                            <td>
                                @if($order->status == 'new')
                                    <span class="badge badge-primary">Baru</span>
                                @elseif($order->status == 'process')
                                    <span class="badge badge-warning">Proses</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge badge-success">Terkirim</span>
                                @else
                                    <span class="badge badge-danger">Di Tolak</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{$orders->links()}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Page level plugins -->
<script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#order-dataTable').DataTable({
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [6]
                }
            ]
        });
    });
</script>
@endpush
