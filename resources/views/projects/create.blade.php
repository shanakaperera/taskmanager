@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New Project</div>

                    <div class="card-body">

                        {!! Form::open(['route' => ['projects.store'], 'method' => 'POST']) !!}

                        <div class='form-group'>
                            {!! Form::label("name", "Project Name",['class'=>'control-label']) !!}
                            {!! Form::text("name",null, ['class' => $errors->has("name") ? 'form-control is-invalid' : 'form-control']) !!}
                            {!! $errors->first("name", '<span class="help-block text-danger">:message</span>') !!}
                        </div>

                        <div class='form-group'>
                            {!! Form::label("tasks", "Tasks",['class'=>'control-label']) !!}
                            {!! Form::select("tasks[]",$tasks,null, ['class' => 'form-control','multiple'=>'multiple', 'id' => 'tasks']) !!}
                            {!! $errors->first("tasks", '<span class="help-block text-danger">:message</span>') !!}
                        </div>

                        <div class="button-group float-right mt-3">
                            <button type="submit" class="btn btn-primary btn-flat">Save</button>
                            <a class="btn btn-danger btn-flat" href="{{route('projects.index')}}">Cancel</a>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

<script type="text/javascript">

    $(document).ready(function() {
        $('#tasks').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
    });

</script>

@endsection