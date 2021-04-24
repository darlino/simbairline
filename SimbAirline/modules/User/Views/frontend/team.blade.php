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
                                <th>{{__("User ID")}}</th>
                                <th>{{__('User Name')}}</th>
                                <th>{{__('Display Name')}}</th>
                                <th>{{__('Email Address')}}</th>
                                <th>{{__('Phone Number')}}</th>
                                <th>{{__('Action')}}</th>
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
                                            @<b style="color: #007bff">{{$row->user_name}}</b>
                                        </td>
                                        <td>
                                            {{$row->display_name}}
                                        </td>
                                        <td>
                                            {{$row->email}}
                                        </td>
                                        <td>
                                            {{$row->phone}}
                                        </td>
                                        <td class="text-center">
                                            <a href="/user/chat?user_id={{$row->id}}" class="text-decoration-none" title="Chat"><button type="button" class="btn btn-primary py-1 px-3"><b class="fa fa-comments-o"></b></button></a>
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
