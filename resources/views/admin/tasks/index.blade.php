@extends('admin_master')
@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">All Tasks</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">All Tasks</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Task List</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">
                        <i class="fa fa-plus"></i> Add New Task
                    </a>

                    <div class="table-responsive">
                        <table id="table-data" class="table table-bordered table-striped data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            var table = $('#table-data').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                ordering: false,
                responsive: true,
                stateSave: true,
                ajax: {
                    url: "{{ url('/tasks') }}",
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title',       name: 'title' },
                    { data: 'description', name: 'description' },
                    { data: 'priority',    name: 'priority' },
                    { data: 'status',      name: 'status' },
                    { data: 'due_date',    name: 'due_date' },
                    { data: 'action',      name: 'action', orderable: false, searchable: false },
                ]
            });

            // Delete task
            $(document).on('click', '.delete-data', function (e) {
                e.preventDefault();
                var data_id = $(this).data('id');

                if (confirm('Are you sure you want to delete this task?')) {
                    $.ajax({
                        url: "{{ url('/tasks') }}/" + data_id,
                        type: "DELETE",
                        dataType: "json",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (data) {
                            if (data.status) {
                                toastr.success(data.message);
                                $('.data-table').DataTable().ajax.reload(null, false);
                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function () {
                            toastr.error('Something went wrong!');
                        }
                    });
                }
            });

            // Status change via dropdown
            $(document).on('click', '.status-change', function (e) {
                e.preventDefault();
                var id     = $(this).data('id');
                var status = $(this).data('status');

                $.ajax({
                    url: "{{ url('/task-status-update') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id:     id,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if (data.status) {
                            toastr.success(data.message);
                            $('.data-table').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong!');
                    }
                });
            });

        });
    </script>
@endpush
