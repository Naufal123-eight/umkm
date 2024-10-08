@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Ubah Produk</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.update',$product->id)}}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Judul <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Masukkan judul"  value="{{$product->title}}" class="form-control">
                    @error('title')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Toko <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="store" placeholder="Masukkan nama toko"  value="{{$product->store}}" class="form-control">
                    @error('store')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
          <label for="summary" class="col-form-label">Ringkasan <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{$product->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Deskripsi</label>
          <textarea class="form-control" id="description" name="description">{{$product->description}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>


        <div class="form-group">
          <label for="is_featured">Utama</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='{{$product->is_featured}}' {{(($product->is_featured) ? 'checked' : '')}}> Yes
        </div>
              {{-- {{$categories}} --}}


        {{-- {{$product->child_cat_id}} --}}
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="cat_id">Kategori <span class="text-danger">*</span></label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">--Pilih Kategori--</option>
                        @foreach($categories as $key=>$cat_data)
                            <option value='{{$cat_data->id}}' {{(($product->cat_id==$cat_data->id)? 'selected' : '')}}>{{$cat_data->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="size">Ukuran</label>
                    <select name="size[]" class="form-control selectpicker"  multiple data-live-search="true">
                        <option value="">--Pilih Ukuran--</option>
                        @foreach($items as $item)
                            @php
                            $data=explode(',',$item->size);
                            // dd($data);
                            @endphp
                        <option value="S"  @if( in_array( "S",$data ) ) selected @endif>Small</option>
                        <option value="M"  @if( in_array( "M",$data ) ) selected @endif>Medium</option>
                        <option value="L"  @if( in_array( "L",$data ) ) selected @endif>Large</option>
                        <option value="XL"  @if( in_array( "XL",$data ) ) selected @endif>Extra Large</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="price" class="col-form-label">Harga(Rp) <span class="text-danger">*</span></label>
                    <input id="price" type="number" name="price" placeholder="Masukkan harga"  value="{{$product->price}}" class="form-control">
                    @error('price')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="discount" class="col-form-label">Diskon(%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Masukkan diskon"  value="{{$product->discount}}" class="form-control">
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
              <option value="default" {{(($product->condition=='default')? 'selected':'')}}>Default</option>
              <option value="new" {{(($product->condition=='new')? 'selected':'')}}>New</option>
              <option value="hot" {{(($product->condition=='hot')? 'selected':'')}}>Hot</option>
          </select>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="stock">Jumlah Stok <span class="text-danger">*</span></label>
                    <input id="quantity" type="number" name="stock" min="0" placeholder="Masukkan jumlah stok"  value="{{$product->stock}}" class="form-control">
                    @error('stock')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active" {{(($product->status=='active')? 'selected' : '')}}>Aktif</option>
                        <option value="inactive" {{(($product->status=='inactive')? 'selected' : '')}}>Tidak Aktif</option>
                    </select>
                    @error('status')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Foto <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                  Pilih
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$product->photo}}">
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Garis Lintang <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="number" step="0.000001" name="latitude" placeholder="Masukkan Koordinat Garis Lintang"  value="{{$product->latitude}}" class="form-control">
                    @error('latitude')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="inputTitle" class="col-form-label">Garis Bujur <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="number" step="0.0000001" name="longtitude" placeholder="Masukkan Koordinat Garis Bujur"  value="{{$product->longtitude}}" class="form-control">
                    @error('longtitude')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
            <label for="address" class="col-form-label">Alamat <span class="text-danger">*</span></label>
            <textarea class="form-control" id="address" name="address">{{$product->address}}</textarea>
            @error('address')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Perbaharui</button>
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
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Write detail Description.....",
          tabsize: 2,
          height: 150
      });
    });
</script>

<script>
  var  child_cat_id='{{$product->child_cat_id}}';
        // alert(child_cat_id);
        $('#cat_id').change(function(){
            var cat_id=$(this).val();

            if(cat_id !=null){
                // ajax call
                $.ajax({
                    url:"/admin/category/"+cat_id+"/child",
                    type:"POST",
                    data:{
                        _token:"{{csrf_token()}}"
                    },
                    success:function(response){
                        if(typeof(response)!='object'){
                            response=$.parseJSON(response);
                        }
                        var html_option="<option value=''>--Select any one--</option>";
                        if(response.status){
                            var data=response.data;
                            if(response.data){
                                $('#child_cat_div').removeClass('d-none');
                                $.each(data,function(id,title){
                                    html_option += "<option value='"+id+"' "+(child_cat_id==id ? 'selected ' : '')+">"+title+"</option>";
                                });
                            }
                            else{
                                console.log('no response data');
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

        });
        if(child_cat_id!=null){
            $('#cat_id').change();
        }
</script>
@endpush
