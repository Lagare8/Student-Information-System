@extends('layouts.adminDashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Subjects Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSubjects }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Enrolled Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $enrolledCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Enrolled Students</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="enrolledTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Enrolled Subjects</th>
                            <th>Enrollment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrolledStudents as $enrollment)
                        <tr>
                            <td>{{ $enrollment->student->student_id }}</td>
                            <td>{{ $enrollment->student->name }}</td>
                            <td>{{ $enrollment->student->course }}</td>
                            <td>{{ $enrollment->student->year_level }}</td>
                            <td>{{ $enrollment->academic_year }}</td>
                            <td>{{ $enrollment->semester }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#subjectsModal{{ $enrollment->id }}">
                                    View Subjects ({{ $enrollment->subjects->count() }})
                                </button>

                                <!-- Subjects Modal -->
                                <div class="modal fade" id="subjectsModal{{ $enrollment->id }}" tabindex="-1" role="dialog" aria-labelledby="subjectsModalLabel{{ $enrollment->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="subjectsModalLabel{{ $enrollment->id }}">
                                                    Enrolled Subjects - {{ $enrollment->student->name }}
                                                </h5>
                                                <button type="button" class="btn btn-success btn-sm ml-2" data-toggle="modal" data-target="#addSubjectModal{{ $enrollment->id }}">
                                                    <i class="fas fa-plus"></i> Add Subject
                                                </button>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Subject Code</th>
                                                                <th>Description</th>
                                                                <th>Units</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($enrollment->subjects as $subject)
                                                            <tr>
                                                                <td>{{ $subject->subject_code }}</td>
                                                                <td>{{ $subject->description }}</td>
                                                                <td>{{ $subject->units }}</td>
                                                                <td>
                                                                    <form action="{{ route('admin.enrollment.removeSubject', ['enrollment' => $enrollment->id, 'subject' => $subject->id]) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this subject?')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            <tr class="table-info">
                                                                <td colspan="2" class="text-right"><strong>Total Units:</strong></td>
                                                                <td colspan="2"><strong>{{ $enrollment->subjects->sum('units') }}</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Subject Modal -->
                                <div class="modal fade" id="addSubjectModal{{ $enrollment->id }}" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel{{ $enrollment->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addSubjectModalLabel{{ $enrollment->id }}">
                                                    Add Subjects - {{ $enrollment->student->name }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.enrollment.addSubject', $enrollment->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        @foreach($subjects as $subject)
                                                            @if(!$enrollment->subjects->contains($subject->id))
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" 
                                                                    class="custom-control-input" 
                                                                    id="subject{{ $subject->id }}{{ $enrollment->id }}" 
                                                                    name="subjects[]" 
                                                                    value="{{ $subject->id }}">
                                                                <label class="custom-control-label" for="subject{{ $subject->id }}{{ $enrollment->id }}">
                                                                    {{ $subject->subject_code }} - {{ $subject->description }} ({{ $subject->units }} units)
                                                                </label>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Add Selected Subjects</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        $('#enrolledTable').DataTable({
            order: [[6, 'desc']] // Sort by enrollment date by default
        });
    });
</script>
@endsection
