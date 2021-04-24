@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <h2 class="title-bar no-border-bottom">
        {{ $page_title }}
    </h2>
    @include('admin.message')
    <div class="booking-history-manager">
        <div class="tabbable">
            @if(!empty($rows) and $rows->total() > 0)
                <div class="tab-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-booking-history">
                            <thead>
                            <tr>
                                <th>{{__("Commission ID")}}</th>
                                <th>{{__('Booking ID')}}</th>
                                <th>{{__('Commission Rate')}}</th>
                                <th>{{__('Amount Gain')}}</th>
                                <th>{{__('Created At')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($rows->total() > 0)
                                @foreach($rows as $row)
                                    <tr>
                                        <td class="booking-history-type">
                                            {{$row->id}}
                                        </td>
                                        <td>
                                            <b style="color: #007bff">{{$row->booking_id}}</b>
                                        </td>
                                        <td>
                                            {{$row->commission_rate}}
                                        </td>
                                        <td>
                                            {{$row->amount}} {{strtoupper($row->unit)}}
                                        </td>
                                        <td>
                                            {{$row->created_at}}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">{{__("No data")}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="bravo-pagination">
                        {{$rows->appends(request()->query())->links()}}
                    </div>
                </div>
            @else
                {{__("No data")}}
            @endif
        </div>
    </div>
@endsection
@section('footer')

@endsection
