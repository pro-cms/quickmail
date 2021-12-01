@extends('admin.messenger.template')

@section('title', $title)

@section('messenger-content')
<div class="row">
    <div class="col-lg-12">
        <div class="list-group">
            @forelse($topics as $topic)
                <div class="row list-group-item d-flex m-1 shadow-sm">
                    <div class="col-lg-4">
                        <b>
                            <a href="{{ route('admin.messenger.showMessages', [$topic->id]) }}" class="text-dark text-uppercase" >
                                <li>
                                    @php($receiverOrCreator = $topic->receiverOrCreator())
                                    @if($topic->hasUnreads())
                                        <strong>
                                            Sender: {{ $receiverOrCreator !== null ? $receiverOrCreator->name : '' }}
                                        </strong>
                                    @else
                                    Sender: {{ $receiverOrCreator !== null ? $receiverOrCreator->name : '' }}
                                    @endif
                                </li>
                            </a>
                        </b>
                    </div>
                    <div class="col-lg-5">
                        <a href="{{ route('admin.messenger.showMessages', [$topic->id]) }}" class="text-dark">
                            @if($topic->hasUnreads())
                                <strong>
                                    {{ $topic->subject }}
                                </strong>
                            @else
                                {{ $topic->subject }}
                            @endif
                        </a>
                    </div>
                    <div class="col-lg-2 text-right">{{ $topic->created_at->diffForHumans() }}</div>
                    <div class="col-lg-1 text-center">
                        <form action="{{ route('admin.messenger.destroyTopic', [$topic->id]) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                            <input type="hidden" name="_method" value="DELETE">
                            @csrf
                            <button class="btn btn-danger btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> </button>

                        </form>
                    </div>
                </div>
                @empty
                <div class="row list-group-item">
                    {{ trans('global.you_have_no_messages') }}
                </div>
            @endforelse
        </div>
        @if(!Request::is('admin/messenger/inbox'))

        {{ $topics->links() }}
        @endif
    </div>
</div>
@endsection
