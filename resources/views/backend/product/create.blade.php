@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Tambah Produk</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.store')}}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Judul <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Masukkan judul"  value="{{old('title')}}" class="form-control">
                    @error('title')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Toko <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="store" placeholder="Masukkan nama toko"  value="{{old('store')}}" class="form-control">
                    @error('store')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Ringkasan <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Deskripsi</label>
          <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>


        <div class="form-group">
          <label for="is_featured">Utama</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='1' checked> Iya
        </div>
              {{-- {{$categories}} --}}

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="cat_id">Kategori <span class="text-danger">*</span></label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">--Pilih Kategori--</option>
                        @foreach($categories as $key=>$cat_data)
                            <option value='{{$cat_data->id}}'>{{$cat_data->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="size">Ukuran</label>
                    <select name="size[]" class="form-control selectpicker"  multiple data-live-search="true">
                        <option value="">--Pilih Ukuran--</option>
                        <option value="S">Small (S)</option>
                        <option value="M">Medium (M)</option>
                        <option value="L">Large (L)</option>
                        <option value="XL">Extra Large (XL)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="price" class="col-form-label">Harga(Rp) <span class="text-danger">*</span></label>
                    <input id="price" type="number" name="price" placeholder="Masukkan harga"  value="{{old('price')}}" class="form-control">
                    @error('price')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="discount" class="col-form-label">Diskon(%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Masukkan diskon"  value="{{old('discount')}}" class="form-control">
                    @error('discount')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
          <label for="condition">Kondisi</label>
          <select name="condition" class="form-control">
              <option value="">--Pilih Kondisi--</option>
              <option value="default">Default</option>
              <option value="new">New</option>
              <option value="hot">Hot</option>
          </select>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="stock">Jumlah Stok <span class="text-danger">*</span></label>
                    <input id="quantity" type="number" name="stock" min="0" placeholder="Masukkan jumlah stok"  value="{{old('stock')}}" class="form-control">
                    @error('stock')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                    @error('status')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Garis Lintang <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="number" step="0.000001" name="latitude" placeholder="Masukkan Koordinat Garis Lintang"  value="{{old('latitude')}}" class="form-control">
                    @error('latitude')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Garis Bujur <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="number" step="0.0000001" name="longtitude" placeholder="Masukkan Koordinat Garis Bujur"  value="{{old('longtitude')}}" class="form-control">
                    @error('longtitude')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Foto <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                  Pilih
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{old('photo')}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
            <label for="address" class="col-form-label">Alamat <span class="text-danger">*</span></label>
            <textarea class="form-control" id="address" name="address">{{old('address')}}</textarea>
            @error('address')
            <span class="text-danger">{{$message}}</span>
            @enderror
          </div>

        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Ulangi</button>
           <button class="btn btn-success" type="submit">Kirim</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
      $('#summary').summernote({
        placeholder: "Tulis deskripsi singkat.....",
          tabsize: 2,
          height: 100
      });
    });

    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Tulis deskripsi detail.....",
          tabsize: 2,
          height: 150
      });
    });
    // $('select').selectpicker();

</script>

<script>
  $('#cat_id').change(function(){
    var cat_id=$(this).val();
    // alert(cat_id);
    if(cat_id !=null){
      // Ajax call
      $.ajax({
        url:"/admin/category/"+cat_id+"/child",
        data:{
          _token:"{{csrf_token()}}",
          id:cat_id
        },
        type:"POST",
        success:function(response){
          if(typeof(response) !='object'){
            response=$.parseJSON(response)
          }
          // console.log(response);
          var html_option="<option value=''>----Select sub category----</option>"
          if(response.status){
            var data=response.data;
            // alert(data);
            if(response.data){
              $('#child_cat_div').removeClass('d-none');
              $.each(data,function(id,title){
                html_option +="<option value='"+id+"'>"+title+"</option>"
              });
            }
            else{
            }
          }
          else{
            $('#child_cat_div').addClass('d-none');
          }
          $('#child_cat_id').html(html_option);
        }
      });
    }
    else{
    }
  })
</script>
@endpush
