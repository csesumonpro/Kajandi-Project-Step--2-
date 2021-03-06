@extends('backend.admin_master')
@section('page_title','Add Today Featured Item')
@section('admin_content')
    <?php
    ?>
    <div class="col-md-10 offset-1">
        <div class="card">
            <div class="card-header">
                <small>
                    <p class="text-center  alert-success">{{Session::get('message_success')}}</p>
                    <p class="text-center  alert-danger">{{Session::get('message_error')}}</p>
                </small>
            </div>
            <div class="card-header">
                <strong>Home Featured Product</strong>
            </div>
            <div class="card-body">
                <div class="card-body card-block">
                    <form action="{{route('storeAdvert')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="admin_id" value="{{Auth::id()}}">
                        <div class="form-group">
                            <label for="ads_section" class=" form-control-label">Select Ads Section</label>
                            <select class="form-control" name="ads_section">
                                <option value="">Select Ads Section</option>
                                <option value="1">Advert Section 1</option>
                                <option value="2">Today Featured</option>
                                <option value="3">Best of Tech</option>
                                <option value="4">Advert Section 2</option>

                            </select>
                            @if ($errors->has('ads_section'))
                                <div class="error">{{ $errors->first('ads_section') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="seller_id" class=" form-control-label">Select Vendor</label>
                            <select class="form-control" name="seller_id">
                                <option value="">Select Vendor</option>
                                @foreach($all_vendor as $av)
                                    <option value="{{$av->id}}">{{$av->vendorname}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('seller_id'))
                                <div class="error">{{ $errors->first('seller_id') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="product_id" class=" form-control-label">Select Product</label>

                            <select class="form-control" name="product_id">
                                <option>Select Vendor For Loading Product</option>
                            </select>
                            @if ($errors->has('product_id'))
                                <div class="error">{{ $errors->first('product_id') }}</div>
                            @endif
                        </div>



                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="product_id" class=" form-control-label">Banner Color(Only For Banner)</label>
                                <input type="color" style="height:39px;" class="form-control" name="banner_color" placeholder="Select Banner Color" required>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="photo">
                                {!!Html::image('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvP_6f292mLf2VVWb3H8evjUPUawbJehCn_JetCsajjZc-ah_T',null,['class'=>'student-photo col-md-4','id'=>'showPhoto'])!!}<br>
                                <input type="file" class="form-control" name="ads_image" id="photo" accept="image/x-png,image/png,image/jpg,image/jpeg">

                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">Submit</button>
                        </div>


                    </form>



                </div>
            </div>
        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

    <script type="text/javascript">
        $("select[name='seller_id']").change(function(){
            var seller_id = $(this).val();
            var token = $("input[name='_token']").val();
            $.ajax({
                url: "<?php echo route('select-pro') ?>",
                method: 'POST',
                data: {seller_id:seller_id, _token:token},
                success: function(data) {
                    $("select[name='product_id'").html('');
                    $("select[name='product_id'").html(data.options);
                }
            });
        });
    </script>
    <script type="text/javascript">
        $('#photo').on('change',function(e){
            showFile(this,'#showPhoto');
        })
        //=============================================
        function showFile(fileInput,img,showName){
            if (fileInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e){
                    $(img).attr('src', e.target.result);
                }
                reader.readAsDataURL(fileInput.files[0]);
            }
            $(showName).text(fileInput.files[0].name)
        };
    </script>

@endsection