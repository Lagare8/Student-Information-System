@extends('layouts.adminDashboard')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Students Management</h1>
        <button class="d-none d-sm-inline-block btn btn-primary shadow-sm" data-toggle="modal" data-target="#addStudentModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add Student
        </button>
    </div>
    
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Year Level</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th width="160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->course }}</td>
                            <td>{{ $student->year_level }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>{{ $student->address }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#enrollStudentModal{{ $student->id }}" title="Enroll">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editStudentModal{{ $student->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Enroll Student Modal -->
                        <div class="modal fade" id="enrollStudentModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="enrollStudentModalLabel{{ $student->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="enrollStudentModalLabel{{ $student->id }}">Enroll Student: {{ $student->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.students.enroll', $student->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="academic_year">Academic Year</label>
                                                <select class="form-control" name="academic_year" required>
                                                    <option value="">Select Academic Year</option>
                                                    <option value="2023-2024">2023-2024</option>
                                                    <option value="2024-2025">2024-2025</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="semester">Semester</label>
                                                <select class="form-control" name="semester" required>
                                                    <option value="">Select Semester</option>
                                                    <option value="First">First Semester</option>
                                                    <option value="Second">Second Semester</option>
                                                    <option value="Summer">Summer</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Select Subjects</label>
                                                @foreach($subjects as $subject)
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="subject{{ $subject->id }}{{ $student->id }}" 
                                                           name="subjects[]" 
                                                           value="{{ $subject->id }}">
                                                    <label class="custom-control-label" for="subject{{ $subject->id }}{{ $student->id }}">
                                                        {{ $subject->subject_code }} - {{ $subject->description }} ({{ $subject->units }} units)
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Enroll Student</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Student Modal -->
                        <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel{{ $student->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editStudentModalLabel{{ $student->id }}">Edit Student</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!-- update-->
                                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="student_id">Student ID</label>
                                                <input type="text" class="form-control" name="student_id" value="{{ $student->student_id }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $student->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email" value="{{ $student->email }}" required>
                                            </div>
                                            <input type="hidden" name="course" value="BS Information Technology">
                                            <div class="form-group">
                                                <label for="year_level">Year Level</label>
                                                <select class="form-control" name="year_level" required>
                                                    <option value="1st Year" {{ $student->year_level == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                                    <option value="2nd Year" {{ $student->year_level == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                                    <option value="3rd Year" {{ $student->year_level == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                                    <option value="4th Year" {{ $student->year_level == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" class="form-control" name="phone" value="{{ $student->phone }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea class="form-control" name="address" rows="3">{{ $student->address }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Student</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id">Student ID</label>
                                <input type="text" class="form-control" name="student_id" required placeholder="e.g., 2201100006">
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" required placeholder="Full Name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required placeholder="email@example.com">
                            </div>
                            <div class="form-group">
                                <label for="year_level">Year Level</label>
                                <select class="form-control" name="year_level" required>
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Complete Address"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="course" value="BS Information Technology">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@endsection

@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#dataTable').DataTable();

    // Initialize Select2 for multiple subject selection
    $('.select2').select2({
        placeholder: 'Select subjects',
        width: '100%',
        allowClear: true
    });
});
</script>

<style>
.select2-container--default .select2-selection--multiple {
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    padding: 0.375rem;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.modal-lg {
    max-width: 800px;
}

.custom-checkbox {
    margin-bottom: 0.5rem;
}

.card-header .btn-link {
    color: #4e73df;
    text-decoration: none;
    font-weight: 500;
}

.card-header .btn-link:hover {
    color: #2e59d9;
    text-decoration: none;
}

.accordion .card {
    margin-bottom: 0.5rem;
    border: 1px solid rgba(0,0,0,.125);
}

.accordion .card-header {
    padding: 0.5rem 1rem;
    background-color: #f8f9fc;
}

.accordion .card-body {
    max-height: 250px;
    overflow-y: auto;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: #28a745;
    border-color: #28a745;
}
</style>
@endsection
