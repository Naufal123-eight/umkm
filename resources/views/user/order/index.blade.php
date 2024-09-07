@extends('user.layouts.master')

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('user.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Order Lists</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($orders) > 0)
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
              <th>Aksi</th>
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
              <th>Aksi</th>
            </tr>
          </tfoot>
          <tbody>
            @foreach($orders as $order)
                <tr class="text-center justify-content-center">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$order->order_number}}</td>
                    <td>{{$order->first_name}} {{$order->last_name}}</td>
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
                    <td>
                        <a href="{{route('user.order.show', $order->id)}}" class="btn btn-warning btn-sm" style="height:30px; width:30px; border-radius:50%; display: inline-block;" data-toggle="tooltip" title="View" data-placement="bottom">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="{{route('user.order.delete', [$order->id])}}" style="display: inline-block;">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm dltBtn" data-id="{{$order->id}}" style="height:30px; width:30px; border-radius:50%;" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        @else
          <h6 class="text-center">No orders found! Please order some products.</h6>
        @endif
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
@endpush

@push('scripts')
  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
        $(document).ready(function() {
            $('#order-dataTable').DataTable({
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [7] // Pastikan ini sesuai dengan jumlah kolom yang ada
                    }
                ]
            });

          // Sweet alert
        $('.dltBtn').click(function(e) {
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
            e.preventDefault();
            swal({
                    title: "Apakah Anda Yakin?",
                    text: "Setelah Menghapus, Anda tidak dapat memulihkan data ini!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                       form.submit();
                    } else {
                        swal("Data Anda di amankan!");
                    }
                });
        });
    });
  </script>
@endpush
