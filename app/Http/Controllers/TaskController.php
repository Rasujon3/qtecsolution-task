<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $tasks = Task::select('*')->latest();

                return DataTables::of($tasks)
                    ->addIndexColumn()

                    ->addColumn('title', function ($row) {
                        return $row->title ?? '';
                    })

                    ->addColumn('description', function ($row) {
                        $desc = $row->description ?? '';
                        return strlen($desc) > 50
                            ? substr($desc, 0, 50) . '...'
                            : $desc;
                    })

                    ->addColumn('priority', function ($row) {
                        $colors = [
                            'low'    => 'success',
                            'medium' => 'warning',
                            'high'   => 'danger',
                        ];
                        $color = $colors[$row->priority] ?? 'secondary';
                        return '<span class="badge badge-' . $color . '">'
                            . ucfirst($row->priority ?? '') . '</span>';
                    })

                    ->addColumn('status', function ($row) {
                        $colors = [
                            'pending'     => 'warning',
                            'in_progress' => 'info',
                            'completed'   => 'success',
                        ];
                        $labels = [
                            'pending'     => 'Pending',
                            'in_progress' => 'In Progress',
                            'completed'   => 'Completed',
                        ];
                        $color = $colors[$row->status] ?? 'secondary';
                        $label = $labels[$row->status] ?? ucfirst($row->status);

                        return '<div class="dropdown">
                            <button class="btn btn-' . $color . ' btn-sm dropdown-toggle"
                                type="button" data-toggle="dropdown">
                                ' . $label . '
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item status-change" href="#"
                                    data-id="' . $row->id . '" data-status="pending">Pending</a>
                                <a class="dropdown-item status-change" href="#"
                                    data-id="' . $row->id . '" data-status="in_progress">In Progress</a>
                                <a class="dropdown-item status-change" href="#"
                                    data-id="' . $row->id . '" data-status="completed">Completed</a>
                            </div>
                        </div>';
                    })

                    ->addColumn('due_date', function ($row) {
                        if (!$row->due_date) return '<span class="text-muted">—</span>';
                        $date = \Carbon\Carbon::parse($row->due_date);
                        $isOverdue = $date->isPast() && $row->status !== 'completed';
                        return '<span class="' . ($isOverdue ? 'text-danger font-weight-bold' : '') . '">'
                            . $date->format('d M, Y') . '</span>';
                    })

                    ->addColumn('action', function ($row) {
                        $btn = '';
                        $btn .= '<a href="' . route('tasks.show', $row->id) . '"
                                    class="btn btn-primary btn-sm" title="Edit">
                                    <i class="fa fa-edit"></i>
                                 </a>&nbsp;';
                        $btn .= '<a href="#"
                                    class="btn btn-danger btn-sm delete-data"
                                    data-id="' . $row->id . '" title="Delete">
                                    <i class="fa fa-trash"></i>
                                 </a>';
                        return $btn;
                    })

                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function ($q) use ($searchValue) {
                                $q->where('title', 'like', "%{$searchValue}%")
                                    ->orWhere('status', 'like', "%{$searchValue}%")
                                    ->orWhere('priority', 'like', "%{$searchValue}%")
                                    ->orWhere('description', 'like', "%{$searchValue}%");
                            });
                        }
                    })

                    ->rawColumns(['title', 'description', 'priority', 'status', 'due_date', 'action'])
                    ->make(true);
            }

            return view('admin.tasks.index');

        } catch (Exception $e) {
            Log::error('Error fetching tasks: ', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong!!!',
            ], 500);
        }
    }

    public function create()
    {
        return view('admin.tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
        DB::beginTransaction();
        try {
            $task              = new Task();
            $task->title       = $request->title;
            $task->description = $request->description;
            $task->status      = $request->status;
            $task->priority    = $request->priority;
            $task->due_date    = $request->due_date;
            $task->save();

            DB::commit();

            $notification = [
                'message'    => 'Task has been added successfully!',
                'alert-type' => 'success',
            ];

            return redirect()->route('tasks.index')->with($notification);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error storing task: ', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            $notification = [
                'message'    => 'Something went wrong!!!',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification);
        }
    }

    public function show(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        DB::beginTransaction();
        try {
            $task->title       = $request->title;
            $task->description = $request->description;
            $task->status      = $request->status;
            $task->priority    = $request->priority;
            $task->due_date    = $request->due_date;
            $task->save();

            DB::commit();

            $notification = [
                'message'    => 'Task has been updated successfully!',
                'alert-type' => 'success',
            ];

            return redirect()->route('tasks.index')->with($notification);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating task: ', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            $notification = [
                'message'    => 'Something went wrong!!!',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Task has been deleted successfully!',
            ], 200);

        } catch (Exception $e) {
            Log::error('Error deleting task: ', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong!!!',
            ], 500);
        }
    }

    public function taskStatusUpdate(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:tasks,id',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        DB::beginTransaction();
        try {
            $task         = Task::findOrFail($request->id);
            $task->status = $request->status;
            $task->save();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Task status updated successfully!',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating task status: ', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong!!!',
            ]);
        }
    }
}
