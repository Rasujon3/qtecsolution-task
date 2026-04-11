@extends('admin_master')
@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Add Task</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('tasks.index') }}">All Tasks</a>
                            </li>
                            <li class="breadcrumb-item active">Add Task</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Task</h3>
                </div>

                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
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
                                        value="{{ old('title') }}"
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
                                        <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>High</option>
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
                                        <option value="pending"     {{ old('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed"   {{ old('status') === 'completed'   ? 'selected' : '' }}>Completed</option>
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
                                        value="{{ old('due_date') }}"
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
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Task
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
        // select2 initialize (যদি admin_master এ select2 already load থাকে)
        $(document).ready(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                placeholder: 'Select One',
                allowClear: true
            });
        });
    </script>
@endpush
