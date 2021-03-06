@extends('admin.messenger.template')

@section('title', trans('global.new_message'))

@section('messenger-content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route("admin.messenger.storeTopic") }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label for="from_email" class="control-label">
                                From
                            </label>
                            <input type="email" name="from_email" value="{{ env('MAIL_FROM_ADDRESS') }}" class="form-control" />
                        </div>

                        <div class="col-lg-12 form-group">
                            <label for="from_email" class="control-label">
                                To
                            </label>
                            <input type="email" name="mail_to"  class="form-control" />
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="recipient" class="control-label">
                                {{ trans('global.recipient') }}
                            </label>

                            <textarea name="recepients" class="form-control" id=""  rows="2">{{ old('recepients') }}</textarea>
                        </div>

                        <div class="col-lg-12 form-group">
                            <label for="subject" class="control-label">
                                {{ trans('global.subject') }}
                            </label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" />
                        </div>

                        <div class="col-lg-12 form-group">
                            <label for="content" class="control-label">
                                {{ trans('global.content') }}
                            </label>
                            <textarea name="content" class="form-control">{{ old('content') }}</textarea>
                        </div>

                        <div class="col-lg-12 form-group">
                            <label for="from_email" class="control-label">
                                Any Attachment(optional)
                            </label>
                            <input type="file" name="attachment"  class="form-control" />
                        </div>
                    </div>
                    <input type="submit" value="{{ trans('global.submit') }}" class="btn btn-success" />
                </div>
            </div>
        </form>
    </div>
</div>
@stop
