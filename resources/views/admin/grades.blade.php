@extends('layouts.adminDashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Grades</h1>
    </div>

    <!-- Content Row -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Enrolled Students Grades</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="gradesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Actions</th>
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
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gradesModal{{ $enrollment->id }}">
                                    View/Edit Grades
                                </button>

                                <!-- Grades Modal -->
                                <div class="modal fade" id="gradesModal{{ $enrollment->id }}" tabindex="-1" role="dialog" aria-labelledby="gradesModalLabel{{ $enrollment->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="gradesModalLabel{{ $enrollment->id }}">
                                                    Grades for {{ $enrollment->student->name }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.grades.update', $enrollment->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Subject Code</th>
                                                                    <th>Description</th>
                                                                    <th>Units</th>
                                                                    <th>Grade</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($enrollment->subjects as $subject)
                                                                <tr>
                                                                    <td>{{ $subject->subject_code }}</td>
                                                                    <td>{{ $subject->description }}</td>
                                                                    <td>{{ $subject->units }}</td>
                                                                    <td>
                                                                        <input type="number" 
                                                                               class="form-control" 
                                                                               name="grades[{{ $subject->id }}]" 
                                                                               value="{{ $subject->pivot->grade ?? '' }}"
                                                                               min="0"
                                                                               max="100"
                                                                               step="0.01">
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Grades</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
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
<script>
    $(document).ready(function() {
        $('#gradesTable').DataTable();
    });
</script>
@endsection
