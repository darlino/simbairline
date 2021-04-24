<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{$html_class ?? ''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php event(new \Modules\Layout\Events\LayoutBeginHead()); @endphp
    @php
        $favicon = setting_item('site_favicon');
    @endphp
    @if($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if(!empty($file))
            <link rel="icon" type="{{$file['file_type']}}" href="{{asset('uploads/'.$file['file_path'])}}" />
        @else:
            <link rel="icon" type="image/png" href="{{url('images/favicon.png')}}" />
        @endif
    @endif
    @include('Layout::parts.seo-meta')
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">
    <link href="{{ asset('dist/frontend/css/app.css?_ver='.config('app.version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/daterange/daterangepicker.css") }}" >
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <script src="sweetalert2.all.min.js"></script>
<!-- Optional: include a polyfill for ES6 Promises for IE11 -->
    <script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel='stylesheet' id='google-font-css-css'  href='https://fonts.googleapis.com/css?family=Poppins%3A300%2C400%2C500%2C600' type='text/css' media='all' />
    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}
    <script>
        var bookingCore = {
            url:'{{url( app_get_locale() )}}',
            url_root:'{{ url('') }}',
            booking_decimals:{{(int)get_current_currency('currency_no_decimal',2)}},
            thousand_separator:'{{get_current_currency('currency_thousand')}}',
            decimal_separator:'{{get_current_currency('currency_decimal')}}',
            currency_position:'{{get_current_currency('currency_format')}}',
            currency_symbol:'{{currency_symbol()}}',
			currency_rate:'{{get_current_currency('rate',1)}}',
            date_format:'{{get_moment_date_format()}}',
            map_provider:'{{setting_item('map_provider')}}',
            map_gmap_key:'{{setting_item('map_gmap_key')}}',
            routes:{
                login:'{{route('auth.login')}}',
                register:'{{route('auth.register')}}',
                checkout:'{{is_api() ? route('api.booking.doCheckout') : route('booking.doCheckout')}}'
            },
            module:{
                hotel:'{{route('hotel.search')}}',
                car:'{{route('car.search')}}',
                tour:'{{route('tour.search')}}',
                space:'{{route('space.search')}}',
            },
            currentUser: {{(int)Auth::id()}},
            isAdmin : {{is_admin() ? 1 : 0}},
            rtl: {{ setting_item_with_lang('enable_rtl') ? "1" : "0" }},
            markAsRead:'{{route('core.notification.markAsRead')}}',
            markAllAsRead:'{{route('core.notification.markAllAsRead')}}',
            loadNotify : '{{route('core.notification.loadNotify')}}',
            pusher_api_key : '{{setting_item("pusher_api_key")}}',
            pusher_cluster : '{{setting_item("pusher_cluster")}}',
        };
        var i18n = {
            warning:"{{__("Warning")}}",
            success:"{{__("Success")}}",
        };
        var daterangepickerLocale = {
            "applyLabel": "{{__('Apply')}}",
            "cancelLabel": "{{__('Cancel')}}",
            "fromLabel": "{{__('From')}}",
            "toLabel": "{{__('To')}}",
            "customRangeLabel": "{{__('Custom')}}",
            "weekLabel": "{{__('W')}}",
            "first_day_of_week": {{ setting_item("site_first_day_of_the_weekin_calendar","1") }},
            "daysOfWeek": [
                "{{__('Su')}}",
                "{{__('Mo')}}",
                "{{__('Tu')}}",
                "{{__('We')}}",
                "{{__('Th')}}",
                "{{__('Fr')}}",
                "{{__('Sa')}}"
            ],
            "monthNames": [
                "{{__('January')}}",
                "{{__('February')}}",
                "{{__('March')}}",
                "{{__('April')}}",
                "{{__('May')}}",
                "{{__('June')}}",
                "{{__('July')}}",
                "{{__('August')}}",
                "{{__('September')}}",
                "{{__('October')}}",
                "{{__('November')}}",
                "{{__('December')}}"
            ],
        };
    </script>
    <!-- Styles -->
    @yield('head')
    {{--Custom Style--}}
    <link href="{{ route('core.style.customCss') }}" rel="stylesheet">
    <link href="{{ asset('libs/carousel-2/owl.carousel.css') }}" rel="stylesheet">
    @if(setting_item_with_lang('enable_rtl'))
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif
    {!! setting_item('head_scripts') !!}
    {!! setting_item_with_lang_raw('head_scripts') !!}
    @php event(new \Modules\Layout\Events\LayoutEndHead()); @endphp
    <style>
        .goog-te-banner-frame {display:none;}
        #goog-gt-tt {display:none!important;visibility:hidden!important;}
        #google_translate_element {
            position: absolute;
            bottom: calc(53px + 16px);
            right: 16px!important;
            z-index: 99999;
        }
        .goog-te-gadget {
            font-family: Roboto, 'Open Sans', sans-serif!important;
            text-transform: uppercase;
        }
        .goog-te-gadget-simple  {
            background-color: rgba(255,255,255,0.20)!important;
            border: 1px solid rgba(255,255,255,0.50) !important;
            padding: 2px!important;
            border-radius: 4px!important;
            font-size: 12px!important;
            line-height:2rem!important;
            display: inline-block;
            cursor: pointer;
            zoom: 1;
        }
        .goog-te-menu2 {
            max-width: 100%;
        }
        .goog-te-menu-value {
            color: #fff !important;
        }

        .goog-te-menu-value span:nth-child(5) {
            display:none;
        }
        .goog-te-menu-value span:nth-child(3) {
            border:none!important;
            font-family: 'Material Icons';
        }
        .goog-te-gadget-icon {
            background-image: url(https://placehold.it/32)!important;
            background-position: 0px 0px;
            height: 32px!important;
            width: 32px!important;
            margin-right: 8px!important;
            display: none;
        }
        .goog-te-banner-frame.skiptranslate {display: none!important;}
        body {
            top: 0 !important;
        }
        #google_translate_element {
            position: fixed !important;
            /*bottom: calc(53px + 16px);*/
            bottom: 50px !important;
            right: 55px !important;
            z-index: 99999;
        }
        .goog-te-gadget-simple {
            border-top-left-radius: 15px !important;
            border-bottom-left-radius: 15px !important;
            background-color: #000 !important;
            border-left: 1px solid #d5d5d5;
            border-top: 1px solid #9b9b9b;
            border-bottom: 1px solid #e8e8e8;
            border-right: 1px solid #d5d5d5;
            font-size: 10pt;
            display: inline-block;
            padding-top: 1px;
            padding-bottom: 2px;
            cursor: pointer;
            zoom: 1;
        }
        #xbtn {
            position: fixed;
            bottom: 51px;
            height: 37px;
            right: 30px;
            z-index: 999999;
            font-size: 12px;
            border: none;
            outline: none;
            background-color: black;
            color: white;
            cursor: pointer;
            padding: 15px;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        #xbtn:hover {
            background-color: #555;
        }
    </style>
    <script>
        //Get the button
        var mybutton = document.getElementById("xbtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</head>
<body class="frontend-page {{$body_class ?? ''}} @if(setting_item_with_lang('enable_rtl')) is-rtl @endif @if(is_api()) is_api @endif">
    <button onclick="topFunction()" id="xbtn" title="Go to top">
        <b class="fa fa-arrow-up" style="position:relative; top: -5px"></b>
    </button>
    @php event(new \Modules\Layout\Events\LayoutBeginBody()); @endphp
    {!! setting_item('body_scripts') !!}
    {!! setting_item_with_lang_raw('body_scripts') !!}
    <div class="bravo_wrap">
            @if(!is_api())
                @include('Layout::parts.topbar')
                @include('Layout::parts.header')
            @endif
            <div id="google_translate_element"></div>
            <script type="text/javascript">
                function googleTranslateElementInit() {
                    new google.translate.TranslateElement({pageLanguage: 'fr', includedLanguages: 'ar,en,es,fr,de,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
                }
            </script>
            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            <script type="text/javascript">
                // WORK IN PROGRESS BELOW

                $('document').ready(function () {


                    // RESTYLE THE DROPDOWN MENU
                    $('#google_translate_element').on("click", function () {

                        // Change font family and color
                        $("iframe").contents().find(".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div, .goog-te-menu2 *")
                            .css({
                                'color': '#544F4B',
                                'font-family': 'Roboto',
                                'width':'100%'
                            });
                        // Change menu's padding
                        $("iframe").contents().find('.goog-te-menu2-item-selected').css ('display', 'none');

                        // Change menu's padding
                        $("iframe").contents().find('.goog-te-menu2').css ('padding', '0px');

                        // Change the padding of the languages
                        $("iframe").contents().find('.goog-te-menu2-item div').css('padding', '20px');

                        // Change the width of the languages
                        $("iframe").contents().find('.goog-te-menu2-item').css('width', '100%');
                        $("iframe").contents().find('td').css('width', '100%');

                        // Change hover effects
                        $("iframe").contents().find(".goog-te-menu2-item div").hover(function () {
                            $(this).css('background-color', '#4385F5').find('span.text').css('color', 'white');
                        }, function () {
                            $(this).css('background-color', 'white').find('span.text').css('color', '#544F4B');
                        });

                        // Change Google's default blue border
                        $("iframe").contents().find('.goog-te-menu2').css('border', 'none');

                        // Change the iframe's box shadow
                        $(".goog-te-menu-frame").css('box-shadow', '0 16px 24px 2px rgba(0, 0, 0, 0.14), 0 6px 30px 5px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.3)');



                        // Change the iframe's size and position?
                        $(".goog-te-menu-frame").css({
                            'height': '100%',
                            'width': '100%',
                            'top': '0px'
                        });
                        // Change iframes's size
                        $("iframe").contents().find('.goog-te-menu2').css({
                            'height': '100%',
                            'width': '100%'
                        });
                    });
                });

            </script>
            @yield('content')
            @include('Layout::parts.footer')
    </div>
    {!! setting_item('footer_scripts') !!}
    {!! setting_item_with_lang_raw('footer_scripts') !!}
    @php event(new \Modules\Layout\Events\LayoutEndBody()); @endphp
</body>
</html>
