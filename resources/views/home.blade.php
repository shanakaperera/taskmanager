@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Task List
                    <a href="{{route('projects.index')}}" class="btn btn-success float-right ml-2">Projects</a>
                    <a href="{{route('tasks.create')}}" class="btn btn-primary float-right">New Task</a>
                </div>

                <div class="card-body">
                    <ul class="sortable list-group">
                        @forelse($tasks as $task)
                            <li class="row list-group-item list-group-item-warning">
                                <div>
                                    <span class="ordinal-position" apt-val="{{$task->id}}"
                                          opt-val="{{$task->priority}}">{{$task->name}}</span>
                                    <a href="#" class="badge badge-danger float-right" style="font-size: 15px">Delete</a>
                                    <a href="{{route('tasks.edit',['id'=>$task->id])}}" class="badge badge-primary float-right" style="font-size: 15px">Edit</a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item list-group-item-info">
                                No Tasks found yet. Create a task.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">

    $(function () {

        // Delete button action ============
        $('.badge-danger').on('click',function (evt) {

            var taskId = $(this).prev('span').attr('apt-val');
            var parent = $(this).parent().parent();

            $.confirm({
                title: 'Confirm !',
                content:'Are you sure you want to delete the task ?',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    remove: {
                        text: 'Remove',
                        btnClass: 'btn-danger',
                        action: function () {

                            var url = '{{route('tasks.destroy',':id')}}';
                            url = url.replace(':id', taskId);

                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: {_token:'{{csrf_token()}}'},
                                success: function (success) {

                                    $.confirm({
                                        title: 'Success!',
                                        content: success.message,
                                        type: 'green',
                                        typeAnimated: true,
                                        buttons: {
                                            close: function () {
                                                parent.remove();

                                                if($('ul.sortable li').length == 0){
                                                   $('ul.sortable').append('<li class="list-group-item list-group-item-info"> No Tasks found yet. Create a task.</li>');
                                                }
                                            }
                                        }
                                    });
                                },
                                error: function (error) {

                                    $.confirm({
                                        title: 'Error!',
                                        content: error.message,
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            close: function () {
                                            }
                                        }
                                    });
                                }
                            });

                        }
                    },
                    close: function () {

                    }
                }
            });

        });

        // On Task Drag and Drop action =======
        $(".sortable").sortable({
            placeholder: 'sort-placeholder',
            forcePlaceholderSize: true,
            start: function (e, ui) {
                ui.item.data('start-pos', ui.item.index() + 1);
            },
            change: function (e, ui) {
                var seq, startPos = ui.item.data('start-pos'), $index, correction;

                // if startPos < placeholder pos, we go from top to bottom
                // else startPos > placeholder pos, we go from bottom to top and we need to correct the index with +1
                //
                correction = startPos <= ui.placeholder.index() ? 0 : 1;

                ui.item.parent().find('li.row').each(function (idx, el) {
                    var $this = $(el)
                        , $index = $this.index();

                    // correction 0 means moving top to bottom, correction 1 means bottom to top
                    //
                    if (( $index + 1 >= startPos && correction === 0) || ($index + 1 <= startPos && correction === 1 )) {
                        $index = $index + correction;
                        $this.find('.ordinal-position').attr('opt-val', $index);
                    }

                });

                // handle dragged item separately
                seq = ui.item.parent().find('li.sort-placeholder').index() + correction;
                ui.item.find('.ordinal-position').attr('opt-val', seq);
            },
            update: function (e, ui) {
                if (this === ui.item.parent()[0]) {
                    var myArray = [];
                    var data = $(this)[0].children;
                    $.each(data, function (k, v) {
                        var apt = v.children[0].children[0].attributes[0].value;
                        var opt = v.children[0].children[0].attributes[1].value;
                        var obj = {apt, opt};
                        myArray.push(obj);
                    });

                    $.ajax({
                        type: 'POST',
                        url: '{{route('tasks.order')}}',
                        data: {data: JSON.stringify(myArray), _token: '{{csrf_token()}}'},
                        dataType: 'json',
                        success: function (success) {
                            console.log(success.message);
                        },
                        error: function (error) {
                            console.log('error');
                        }
                    });

                }
            }
        });
    });

</script>

@endsection
