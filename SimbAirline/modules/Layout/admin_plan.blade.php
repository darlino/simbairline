@extends('Layout::admin.app')

@section('content')
    <style>
        .bravo_wrap, body{
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>

    @php
        $hours = array("10" , "30" , "60" , "120" , "120" , "150" ,"180" ,"200" ,"220" ,"250","300","320","360" , "400");
    @endphp


    <div class="container">
        <h1 class="text-center mx-auto">
            Please check the form bellow
        </h1>
        <div class="col-12 justify-content-center mx-auto">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            @else
            <div class="alert alert-success">
                <h4> great job</h4>
            </div>

            @endif



            <form class="col-12 form-group " action="/admin/plan" method ="post">
                @csrf
                <div class=" p-4 col-12 d-flex flex-row justify-content-around">
                    <div class="col-6 d-flex flex-column ">
                        <label for="routing" class="form-label"> Routing </label>
                        <input class="form-control" name="routing" placeholder="enter the routing ex : leg ACC - BGF">
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label for="plane" class="form-label"> Plane </label>
                        <select class="form-control" name="plane">
                            <option> Plane </option>
                            @foreach($data_plane as $plane)
                                <option class="text-dark" value={{ $plane->title }}>
                                    {{ $plane->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="p-4 col-12 d-flex flex-row  justify-content-around">
                    <div class="col-6 d-flex flex-column ">
                        <label for="capacity" class="form-label"> Capacity </label>
                        <input class="form-control" name="capacity" type="number" placeholder="enter the number of passengers">
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label for="Routing" class="form-label"> Date of disponibilty </label>
                        <input class="form-control datepicker" type="text" name="date_dispo" id="datetimepicker" placeholder="enter the date of diponibility">
                    </div>
                </div>

                <div class="p-4 col-12 d-flex flex-row  justify-content-around">
                    <div class="col-6 d-flex flex-column ">
                        <label for="Routing" class="form-label"> From </label>
                        <select class="form-control" name="from">
                            @foreach( $data_location as $location)
                                <option value={{ $location->name }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label for="Routing" class="form-label"> Time of departure </label>
                        <input class="form-control" type="time" name="date_depart" id="datetimepicker" placeholder="enter the date of departure">
                    </div>
                </div>
                <div class="p-4 col-12 d-flex flex-row  justify-content-around">
                    <div class="col-6 d-flex flex-column">
                        <label for="Routing" class="form-label"> Time of return </label>
                        <input class="form-control " type="time" name="date_return" id="datetimepicker" placeholder="enter the date of return">
                    </div>
                    <div class="col-6 d-flex flex-column ">
                        <label for="Routing" class="form-label"> To </label>
                        <select class="form-control" name="to">
                            @foreach( $data_location as $location)
                                <option value={{ $location->name }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="p-4 col-12 d-flex flex-row  justify-content-around">
                    <div class="col-6 d-flex flex-column ">
                        <label for="Routing" class="form-label"> Time of the flight </label>
                        <input class="form-control " type="time" name="time_flight" id="datetimepicker" placeholder="enter the date of return">
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label for="Routing" class="form-label"> Ground Time </label>
                        <input class="form-control " type="time" name="time_ground" id="datetimepicker" placeholder="enter the date of return">
                    </div>
                </div>
                <div class="p-4 col-12 d-flex flex-row  justify-content-around">
                    <div class="col-6 d-flex flex-column">
                        <label for="Routing" class="form-label"> Night time </label>
                        <input class="form-control " type="time" name="time_night_rest" id="datetimepicker" placeholder="enter the date of return">
                    </div>
                    <div class="col-6 d-flex flex-column ">
                        <label for="routing" class="form-label"> Routing nature 1 </label>
                        <input class="form-control" placeholder="enter the first routing nature " name="routing_nature_1">
                    </div>
                </div>
                <div class="p-4 col-12 d-flex flex-row  justify-content-around">
                    <div class="col-6 d-flex flex-column ">
                        <label for="routing" class="form-label"> Routing nature  </label>
                        <input class="form-control" placeholder="enter the second routing nature " name="routing_nature_2">
                    </div>
                    <div class="col-6 d-flex flex-column ">
                        <label for="routing" class="form-label"> Call sign </label>
                        <input class="form-control" placeholder="enter the call sign " name="call_sign">
                    </div>
                </div>
                <div class="p-4 col-12 d-flex flex-row  justify-content-around">
                    <div class="col-6 d-flex flex-column ">
                        <label for="routing" class="form-label"> Flight number  </label>
                        <input class="form-control" placeholder="enter the flight number " name="flight_number">
                    </div>
                    <div class="col-6 d-flex flex-column ">
                        <label for="routing" class="form-label"> Number of baggages  </label>
                        <input class="form-control" type="number" placeholder="enter the number of baggages " name="baggages">
                    </div>
                </div>
            <button class="btn btn-primary mx-auto" type="submit"> Save </button>
            </form>
        </div>
    </div>
    <script src="/js/moment.min.js"></script>
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.datepicker').datepicker({
                format: "yy/mm/dd",
                weekStart: 0,
                autoclose: true,
                todayHighlight: true,
                orientation: "auto"
            });

            $('.datepickerhour').datepicker({
                "timePicker": true,
                "linkedCalendars": false,
                "startDate": "04/17/2021",
                "endDate": "04/23/2021"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });

        });
    </script>




@endsection
