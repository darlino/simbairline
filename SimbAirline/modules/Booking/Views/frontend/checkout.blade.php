@extends('layouts.app')
@section('head')
    <link href="{{ asset('module/booking/css/checkout.css?_ver='.config('app.version')) }}" rel="stylesheet">
    <style>
        .myImageHover {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }
        .myImgsrc{
            opacity: 1;
            transition: .5s ease;
            backface-visibility: hidden;
        }
        #myImgCard:hover .myImgsrc {
            opacity: 0.3;
        }

        #myImgCard:hover .myImageHover {
            opacity: 1;
        }
    </style>
    <script>
        /*
            AUTHOR : EMRE UTKU UYGUÇ
            UPDATE : 24.04.2019
            fixed max size problem
        */
        class multipleImageUploader{
            constructor(){
                this.ImageCountErrorMsg = 'Maximum 4 Photos(2 passeports et 2 carnet jaune) ';
                this.ImageSizeErrorMsg = ' Taille trop grande(Max: 300 Ko)';
                this.AreaHeaderText = 'Passport(2) + Carnet Jaune(2)';
                this.ImageExtErrorMsg = 'Extension incompatible(jpg, png, jpeg)';
            }
            showMessage(type,msg){
                $('#msgArea').append('<div class="alert alert-'+ type +'" role="alert">'+ msg +'</div>').fadeTo(2000, 500).slideUp(500, function(){
                    $('#msgArea').remove('').slideUp(500);
                });
            }
            bindFormSubmit(formId , formSubmitAction){
                var self = this;
                this.formId = formId;
                $(formId).submit(function(e){
                    e.preventDefault();
                    if(self.sendDatasMode == 'form'){
                        var otherFormData = $(this).serializeArray();
                        for(var item in otherFormData){
                            self.formData.append(otherFormData[item]['name'] , otherFormData[item]['value']);
                        }
                    }

                    formSubmitAction();
                });
            }
            sendDatas(succesCallback , errorCallback){
                var self = this;
                $.ajax({
                    type: $(self.formId).attr('method'),
                    url: $(self.formId).attr('action'),
                    data: self.formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async:true,
                    success: function (data) {
                        succesCallback(data);
                    },
                    error: function (data) {
                        errorCallback(data);
                    },
                });
            }
            init(){
                var self = this;
                self.maxImageMb = self.maxImageMb * 1024 * 1024;
                $(this.inputElement).change( function(e){
                    self.formData = new FormData();
                    var images = e.target.files;
                    if (images.length > 0) {
                        $(self.uploadArea).html('\
                            <div class="card text-center">\
                                <div class="card-header"> \
                                '+ self.AreaHeaderText +' \
                                <div id="msgArea"> </div> \
                                </div>\
                                <div class="card-body" id="imagesArea" style="display:flex; flex-wrap:wrap; ">\
                                </div>\
                            </div>\
                        ');

                        for (var i = 0; i < images.length; i++) {
                            if(typeof self.maxImageCount != 'undefined' & i == self.maxImageCount ){
                                self.showMessage('info',self.ImageCountErrorMsg  + self.maxImageCount );
                                break;
                            }
                            else{
                                var imageExt = images[i].name.split('.').pop().toLowerCase();
                                var imageSize = images[i].size;
                                var isValidExt = self.validExtensions.indexOf(imageExt) > -1;
                                if (isValidExt) {
                                    if (imageSize <= self.maxImageMb) {
                                        var reader = new FileReader();
                                        reader.onload = function(e) {
                                            var imageLoader = new Image();
                                            imageLoader.src = e.target.result;
                                            imageLoader.onload = function(){
                                                var scaleHeight = imageLoader.height * 200 / imageLoader.width;
                                                $('#imagesArea').append('' +
                                                    '<div class="card text-white bg-dark" style="max-width: 15rem;margin-top:5px; margin-right : 5px" id="imgCard-' + e.target.imageId + '"> \
														<div class="card-header">' + e.target.imageName.slice(0,25) + '</div> \
															<div class="card-body" id="myImgCard"> \
																<img src="' + e.target.result + '" class="card-title myImgsrc" height="'+scaleHeight+'"  width="200"></img> \
																<textarea style="display: none" class="d-none" name="data' + e.target.imageId + '" value="' + e.target.result + '">' + e.target.result + '</textarea>\
																<div class="myImageHover"> \
																<a class="btn btn-danger" id="imageDelete" card-id="' + e.target.imageId + '" image-name="' + e.target.imageName + '">X \
																</a> \
															</div> \
                                                        </div> \
													</div> ');
                                            };

                                        }
                                        reader.imageName = images[i].name;
                                        reader.imageId = i;
                                        reader.readAsDataURL(images[i]);

                                        self.formData.append('images[]', images[i]);
                                    }
                                    else{
                                        self.showMessage('danger',  images[i].name + self.ImageSizeErrorMsg.replace('$maxSize',self.maxImageMb / Math.pow(1024,2)));
                                    }
                                }
                                else{
                                    self.showMessage('warning',images[i].name + self.ImageExtErrorMsg);
                                }
                            }
                        }
                        $(self.inputElement).val('');
                    }
                });

                $(document).on('click', '#imageDelete', function(e) {
                    var deleteImageId = $(this).attr("card-id");

                    //

                    var images = self.formData.getAll("images[]");
                    var cloneData = {};

                    jQuery.each(images, function(i, image) {
                        cloneData['image-'+i] = image;
                    });

                    cloneData['image-'+deleteImageId] = '';

                    self.formData = new FormData();
                    for(var item in cloneData){
                        self.formData.append('images[]', cloneData[item]);
                    }

                    //


                    $("#imgCard-"+deleteImageId).remove();


                });

            }
        }

    </script>
@endsection
@section('content')
    <div class="bravo-booking-page padding-content" >
        <div class="container">
            <div id="bravo-checkout-page" >
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="form-title">{{__('Booking Submission')}}</h3>
                         <div class="booking-form">
                             @include ($service->checkout_form_file ?? 'Booking::frontend/booking/checkout-form')

                         </div>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-detail booking-form">
                            @include ($service->checkout_booking_detail_file ?? '')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('module/booking/js/checkout.js') }}"></script>
    <script>

        const myUploader = new multipleImageUploader;

        myUploader.uploadArea = '#uploadArea';
        myUploader.inputElement = '#passport';
        myUploader.validExtensions = ['jpg', 'jpeg', 'png', 'tiff', 'webp'];
        myUploader.maxImageCount = 4;
        myUploader.maxImageMb = 1;
        myUploader.dataMode = 'form';

        myUploader.bindFormSubmit('#myForm',function onClickSubmit(){
            myUploader.sendDatas(
                function success(data){
                    $('#response').html(data);
                    console.log(data);
                },
                function error(data){
                }
            );

        });

        myUploader.init();

    </script>
    <script type="text/javascript">
        jQuery(function () {
            $.ajax({
                'url': bookingCore.url + '{{$is_api ? '/api' : ''}}/booking/{{$booking->code}}/check-status',
                'cache': false,
                'type': 'GET',
                success: function (data) {
                    if (data.redirect !== undefined && data.redirect) {
                        window.location.href = data.redirect
                    }
                }
            });
        })

        $('.deposit_amount').on('change', function(){
            checkPaynow();
        });

        $('input[type=radio][name=how_to_pay]').on('change', function(){
            checkPaynow();
        });

        function checkPaynow(){
            var credit_input = $('.deposit_amount').val();
            var how_to_pay = $("input[name=how_to_pay]:checked").val();
            var convert_to_money = credit_input * {{ setting_item('wallet_credit_exchange_rate',1)}};

            if(how_to_pay == 'full'){
                var pay_now_need_pay = parseFloat({{floatval($booking->total)}}) - convert_to_money;
            }else{
                var pay_now_need_pay = parseFloat({{floatval($booking->deposit == null ? $booking->total : $booking->deposit)}}) - convert_to_money;
            }

            if(pay_now_need_pay < 0){
                pay_now_need_pay = 0;
            }
            $('.convert_pay_now').html(bravo_format_money(pay_now_need_pay));
            $('.convert_deposit_amount').html(bravo_format_money(convert_to_money));
        }
    </script>
@endsection
