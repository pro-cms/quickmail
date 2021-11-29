@extends('layouts.admin')

@section('content')
<div class="content card p-3">
    <div class="row">
        <p class="col-lg-12">
           <b class="text-uppercase"> @yield('title')</b>
        </p>
    </div>
    <div class="row">
        <div class="col-lg-3 ">
            <p>
                <a href="{{ route('admin.messenger.createTopic') }}" class="btn btn-primary btn-block">
                   <i class="fa fa-edit"></i> {{ trans('global.new_message') }}
                </a>
            </p>
            <div class="list-group  shadow-sm">
                <a href="{{ route('admin.messenger.index') }}" class="list-group-item">
                    {{ trans('global.all_messages') }}
                </a>
                <a href="{{ route('admin.messenger.showInbox') }}" class="list-group-item">
                 <i class="fa fa-check-circle" aria-hidden="true"></i>   @if($unreads['inbox'] > 0)
                        <strong>
                            {{ trans('global.inbox') }}
                            ({{ $unreads['inbox'] }})
                        </strong>
                    @else
                        {{ trans('global.inbox') }}
                    @endif
                </a>
                <a href="{{ route('admin.messenger.showOutbox') }}" class="list-group-item">
                   <i class="fa fa-inbox" aria-hidden="true"></i>  @if($unreads['outbox'] > 0)
                        <strong>
                            {{ trans('global.outbox') }}
                            ({{ $unreads['outbox'] }})
                        </strong>
                    @else
                        {{ trans('global.outbox') }}
                    @endif
                </a>
            </div>
        </div>
        <div class="col-lg-9">
            @yield('messenger-content')
        </div>
    </div>
</div>
@stop
