@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Project List
                        <a href="{{route('home')}}" class="btn btn-success float-right ml-2">Tasks</a>
                        <a href="{{route('projects.create')}}" class="btn btn-primary float-right">New Project</a>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class='form-group'>
                                    {!! Form::label("projects", "Projects",['class'=>'control-label']) !!}
                                    {!! Form::select("projects",$projects,null, ['class' => 'form-control']) !!}
                                </div>
                                @if(!$projects->isEmpty())
                                    <div class="button-group float-right mt-3">
                                        <a id="pro_edit_btn" class="btn btn-primary btn-flat" href="#">Edit</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <h4>Tasks Related to Selected Project</h4>

                        @if($projects->isEmpty())
                            <table class="table table-bordered">
                                <tbody>
                                    <tr><td>No any projects available yet. Go create a project.</td></tr>
                                </tbody>
                            </table>
                        @else
                            <div id="grid_space"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script type="text/javascript">

        $(document).ready(function () {
            $('#projects').trigger('change');
        });

        $('#projects').on('change', function () {

            var url = '{{route('projects.tasks',':id')}}';
            url = url.replace(':id', $(this).val());

            setupProjectEditUrl($(this).val());

            $.ajax({
                url: url,
                type: 'GET',
                dataType: "JSON",
                success: function (success) {

                    buildTaskTable(success);

                },
                error: function (error) {

                    console.log(error);
                }
            });

        });

        function setupProjectEditUrl(id) {

            var url = '{{route('projects.edit',':id')}}';
            url = url.replace(':id', id);

            $('#pro_edit_btn').attr('href',url);

        }

        function buildTaskTable(data) {

            var table = '<table class="table table-bordered"><tbody> ';

            if (jQuery.isEmptyObject(data)) {

                table += '<tr> <td>No any task assigned to this project.</td></tr>';
            }

            $.each(data, function (k, v) {

                table += '<tr> <td>' + v.name + '</td></tr>';

            });

            table += '</tbody></table>';

            $('#grid_space').empty();
            $('#grid_space').append(table);
        }

    </script>

@endsection