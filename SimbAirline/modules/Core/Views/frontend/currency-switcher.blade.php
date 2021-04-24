@php
    $actives = \App\Currency::getActiveCurrency();
    $current = \App\Currency::getCurrent('currency_main');
@endphp
{{--Multi Language--}}
@if(!empty($actives) and count($actives) > 1)
    <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="is_login">
            {{ __('Monnaie') }}
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu text-left width-auto">
            @foreach($actives as $currency)
                @if($current != $currency['currency_main'])
                    <li>
                        <a href="{{get_currency_switcher_url($currency['currency_main'])}}" class="is_login">
                            {{strtoupper($currency['currency_main'])}}  {{var_dump($currency)}}
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{get_currency_switcher_url($currency['currency_main'])}}" class="is_login">
                            {{strtoupper($currency['currency_main'])}} <b class="fa fa-check text-success"></b>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </li>
@endif
{{--End Multi language--}}
