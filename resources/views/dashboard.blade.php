@extends('layouts.userDashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Academic Records</h1>
    </div>

    @if(auth()->user()->student)
        <!-- Content Row - Student Information Cards -->
        <div class="row">
            <!-- Student Info Card - Displays basic student details -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Student Information</div>
                                <!-- Display student's personal information -->
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ auth()->user()->name }}</div>
                                <div class="text-sm text-gray-600">Student ID: {{ auth()->user()->student->student_id }}</div>
                                <div class="text-sm text-gray-600">Course: {{ auth()->user()->student->course }}</div>
                                <div class="text-sm text-gray-600">Year Level: {{ auth()->user()->student->year_level }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GPA Card - Calculates and displays student's overall GPA -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Grade Point Average (GPA)</div>
                                @php
                                    // Calculate GPA by getting all grades from enrollments
                                    $grades = auth()->user()->student->enrollments->flatMap->subjects
                                        ->whereNotNull('pivot.grade');
                                    // Calculate average if grades exist, otherwise show N/A
                                    $gpa = $grades->count() > 0 
                                        ? number_format($grades->avg('pivot.grade'), 2) 
                                        : 'N/A';
                                @endphp
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $gpa }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Records Table - Displays all enrolled subjects and grades -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">My Subjects and Grades</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <!-- Table Headers -->
                                <thead>
                                    <tr>
                                        <th>Academic Year</th>
                                        <th>Semester</th>
                                        <th>Subject Code</th>
                                        <th>Description</th>
                                        <th>Units</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <!-- Table Body - Loops through all enrollments and their subjects -->
                                <tbody>
                                    @foreach(auth()->user()->student->enrollments as $enrollment)
                                        @foreach($enrollment->subjects as $subject)
                                        <tr>
                                            <td>{{ $enrollment->academic_year }}</td>
                                            <td>{{ $enrollment->semester }}</td>
                                            <td>{{ $subject->subject_code }}</td>
                                            <td>{{ $subject->description }}</td>
                                            <td>{{ $subject->units }}</td>
                                            <td>
                                                @if($subject->pivot->grade)
                                                    <!-- Display grade with color coding (green for pass, red for fail) -->
                                                    <span class="font-weight-bold 
                                                        {{ $subject->pivot->grade >= 75 ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($subject->pivot->grade, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not yet graded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                                <!-- Table Footer - Displays total units taken -->
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="4" class="text-right font-weight-bold">Total Units:</td>
                                        <td class="font-weight-bold">
                                            {{ auth()->user()->student->enrollments->flatMap->subjects->sum('units') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Error Message - Displayed when no student record is found -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5 class="text-gray-800">No Student Record Found</h5>
                            <p class="text-gray-600">Your user account is not linked to any student record. Please contact the administrator for assistance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- DataTables Initialization Script -->
@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables with sorting configuration
    $('.table').DataTable({
        order: [[0, 'desc'], [1, 'desc']], // Sort by academic year and semester in descending order
        pageLength: 10, // Show 10 entries per page
    });
});
</script>
@endpush
@endsection

