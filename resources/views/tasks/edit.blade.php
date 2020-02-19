@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Task - {{$task->name}}</div>

                    <div class="card-body">

                        {!! Form::open(['route' => ['tasks.update',$task->id], 'method' => 'PUT']) !!}

                        <div class='form-group'>
                            {!! Form::label("name", "Task Name",['class'=>'control-label']) !!}
                            {!! Form::text("name",$task->name, ['class' => $errors->has("name") ? 'form-control is-invalid' : 'form-control']) !!}
                            {!! $errors->first("name", '<span class="help-block text-danger">:message</span>') !!}
                        </div>

                        <div class='form-group'>
                            {!! Form::label("priority", "Task Priority",['class'=>'control-label']) !!}
                            {!! Form::number("priority",$task->priority, ['class' => $errors->has("priority") ? 'form-control is-invalid' : 'form-control']) !!}
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