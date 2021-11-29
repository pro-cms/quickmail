@extends('admin.messenger.template')

@section('title', $topic->subject)

@section('messenger-content')
<div class="row">
    <p>
        @if($topic->receiverOrCreator() !== null && !$topic->receiverOrCreator()->trashed())
            <a href="{{ route('admin.messenger.reply', [$topic->id]) }}"  class="btn btn-info">
               <i class="fa fa-check-circle" aria-hidden="true"></i>Resend Message
            </a>
        @endif
    </p>
    <div class="col-lg-12">
        <div class="list-group">
            @foreach($topic->messages as $message)
                <div class="row list-group-item shadow-sm p-3 m-2 rounded-1" style="border-radius: 20px!important">
                    <div class="row">
                        <div class="col col-lg-10">
                           <h5> <strong>{{ $message->sender->email }}</strong></h5>
                        </div>
                        <div class="col col-lg-2">
                           <b> {{ $message->created_at->diffForHumans() }}</b>
                        </div>
                    </div>
                    <div>
                    </div>
                    <div class="row">
                        <div class="col col-lg-12">
                            <p style="font-size: 16px">{{ $message->content }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="card m-1 p-2">
                <div class="card-head p-2 ">EMAIL RECEIVERS</div>

                    <div class="card-body">
                        @foreach (json_decode($topic->receivers_email) as $email )
                        <b class="">{{ $email }}</b> |
                        @endforeach
                    </div>

            </div>
        </div>
    </div>
</div>
@endsection
