@extends('layouts.app')

@section('content')
    <style>
        .bravo_wrap, body{
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center bravo-login-form-page bravo-login-page">
            <div class="col-md-12">
                <div class="">
                    <h4 class="form-title">{{ __('Plan de Vol') }}</h4>
                    <br/>

                    <table class="table table-striped table-responsive table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Routing</th>
                            <th scope="col">Plane</th>
                            <th scope="col">PAX</th>
                            <th scope="col">Date</th>
                            <th scope="col">FROM</th>
                            <th scope="col">ETD</th>
                            <th scope="col">ETA</th>
                            <th scope="col">TO</th>
                            <th scope="col">EET</th>
                            <th scope="col">GROUND TIME</th>
                            <th scope="col">NIGHT STOP</th>
                            <th scope="col">ROUTING NATURE</th>
                            <th scope="col">ROUTING NATURE</th>
                            <th scope="col">CALL SIGN</th>
                            <th scope="col">FLIGHT NUMBER</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plan as $p)
                            @php
                                $i = \Modules\Space\Models\Space::where('slug', $p->flight_id)->first()
                            @endphp
                            <tr>
                                <th scope="row">{{ $p->routing }}</th>
                                
                                <td>{{ $p->pax }}</td>
                                <td>{{ $p->date }}</td>
                                <td>{{ $p->from }}</td>
                                <td>{{ $p->etd }}</td>
                                <td>{{ $p->eta }}</td>
                                <td>{{ $p->to }}</td>
                                <td>{{ $p->eet }}</td>
                                <td>{{ $p->ground_time }}</td>
                                <td>{{ $p->night_stop }}</td>
                                <td>{{ $p->routing_nature1 }}</td>
                                <td>{{ $p->routing_nature2 }}</td>
                                <td>{{ $p->call_sign }}</td>
                                <td>{{ $p->flight_number }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
