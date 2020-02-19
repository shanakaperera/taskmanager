@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New Task</div>

                    <div class="card-body">

                        {!! Form::open(['route' => ['tasks.store'], 'method' => 'POST']) !!}

                            <div class='form-group'>
                                {!! Form::label("name", "Task Name",['class'=>'control-label']) !!}
                                {!! Form::text("name",null, ['class' => $errors->has("name") ? 'form-control is-invalid' : 'form-control']) !!}
                                {!! $errors->first("name", '<span class="help-block text-danger">:message</span>') !!}
                            </div>

                            <div class='form-group'>
                                {!! Form::label("priority", "Task Priority",['class'=>'control-label']) !!}
                                {!! Form::number("priority",$least_priority+1, ['class' => $errors->has("priority") ? 'form-control is-invalid' : 'form-control']) !!}
                                {!! $errors->first("priority", '<span class="help-block text-danger">:message</span>') !!}
                            </div>

                            <div class="button-group float-right mt-3">
                                <button type="submit" class="btn btn-primary btn-flat">Save</button>
                                <a class="btn btn-danger btn-flat" href="{{route('home')}}">Cancel</a>
                            </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection