@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Task</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/tasks')}}">All Task</a></li>
                        <li class="breadcrumb-item active">Edit Task</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Edit Task</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('tasks.update',$task->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">
                        {{-- Task Title --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">
                                    Task Title <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Enter task title"
                                    required
                                    value="{{ old('title', $task->title) }}"
                                >
                                @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Priority --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority">
                                    Priority <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="priority"
                                    id="priority"
                                    class="form-control select2bs4 @error('priority') is-invalid @enderror"
                                    required
                                >
                                    <option value="" disabled selected>Select Priority</option>
                                    <option value="low" @if($task->priority === 'low') selected @endif    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                                    <option value="medium" @if($task->priority === 'medium') selected @endif {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" @if($task->priority === 'high') selected @endif   {{ old('priority') === 'high'   ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select
                                    name="status"
                                    id="status"
                                    class="form-control select2bs4 @error('status') is-invalid @enderror"
                                    required
                                >
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="pending" @if($task->status === 'pending') selected @endif     {{ old('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" @if($task->status === 'in_progress') selected @endif {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" @if($task->status === 'completed') selected @endif   {{ old('status') === 'completed'   ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input
                                    type="date"
                                    name="due_date"
                                    id="due_date"
                                    class="form-control @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date', $task->due_date) }}"
                                    min="{{ date('Y-m-d') }}"
                                >
                                @error('due_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Enter task description (optional)"
                                >{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>

@endsection

@push('scripts')


  <script>

  </script>

@endpush
