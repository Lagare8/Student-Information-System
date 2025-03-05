<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with overview statistics
     */
    public function dashboard()
    {
        // Get all available subjects
        $subjects = Subject::all();

        // Count total number of registered students
        $totalStudents = Student::count();

        // Count total number of subjects in the system
        $totalSubjects = $subjects->count();

        // Get all enrolled students with their subjects
        // Ordered by most recent enrollments first
        $enrolledStudents = Enrollment::with(['student', 'subjects'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Count how many students are currently enrolled
        $enrolledCount = $enrolledStudents->count();

        // Return dashboard view with all required data
        return view('admin.dashboard', compact(
            'totalStudents',
            'totalSubjects',
            'enrolledStudents',
            'enrolledCount',
            'subjects'  // Now $subjects is defined before being used in compact()
        ));
    }

    /**
     * Display list of all students and subjects
     * Used in the student management page
     */
    public function students()
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('admin.students', compact('students', 'subjects'));
    }

    /**
     * Create a new student record and corresponding user account
     * Creates both student entry and user login credentials
     * Default password is set to 'password'
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|unique:students',
            'name' => 'required',
            'email' => 'required|email|unique:students,email|unique:users,email',
            'year_level' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Create student record
                $student = Student::create([
                    'student_id' => $request->student_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'course' => 'BS Information Technology',
                    'year_level' => $request->year_level,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);

                // Create user account for the student
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make('password'), // Default password
                    'role' => 'user',
                ]);
            });

            return redirect()->route('admin.students')
                ->with('success', 'Student added successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.students')
                ->with('error', 'Error adding student: ' . $e->getMessage());
        }
    }

    /**
     * Update existing student information
     * Updates both student and user records
     * Ensures email uniqueness across both tables
     */
    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|unique:students,student_id,'.$id,
            'name' => 'required',
            'email' => 'required|email|unique:students,email,'.$id,
            'year_level' => 'required',
        ]);

        $student = Student::findOrFail($id);
        $request->merge(['course' => 'BS Information Technology']);
        $student->update($request->all());

        // Update the corresponding user account
        $user = User::where('email', $student->email)->first();
        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('admin.students')
            ->with('success', 'Student updated successfully');
    }

    /**
     * Delete a student record
     * Removes both student and associated user account
     */
    public function destroyStudent($id)
    {
        $student = Student::findOrFail($id);
        
        // Delete the corresponding user account
        $user = User::where('email', $student->email)->first();
        if ($user) {
            $user->delete();
        }
        
        $student->delete();

        return redirect()->route('admin.students')
            ->with('success', 'Student deleted successfully');
    }

    /**
     * Enroll a student in subjects for a specific semester
     * Prevents duplicate enrollment for same semester
     * Creates enrollment record and attaches selected subjects
     */
    public function enrollStudent(Request $request, $id)
    {
        $request->validate([
            'academic_year' => 'required',
            'semester' => 'required',
            'subjects' => 'required|array|min:1'
        ]);

        try {
            $student = Student::findOrFail($id);

            // Check if student is already enrolled for this academic year and semester
            $existingEnrollment = Enrollment::where('student_id', $id)
                ->where('academic_year', $request->academic_year)
                ->where('semester', $request->semester)
                ->first();

            if ($existingEnrollment) {
                return redirect()->route('admin.students')
                    ->with('error', 'Student is already enrolled for this semester');
            }

            // Create new enrollment and attach subjects in a transaction
            DB::transaction(function () use ($id, $request) {
                $enrollment = Enrollment::create([
                    'student_id' => $id,
                    'academic_year' => $request->academic_year,
                    'semester' => $request->semester
                ]);

                $enrollment->subjects()->attach($request->subjects);
            });

            return redirect()->route('admin.students')
                ->with('success', "Student {$student->name} enrolled successfully for {$request->academic_year} {$request->semester} Semester");
        } catch (\Exception $e) {
            return redirect()->route('admin.students')
                ->with('error', 'Error enrolling student: ' . $e->getMessage());
        }
    }

    /**
     * Display list of all subjects
     * Used in subject management page
     */
    public function subjects()
    {
        $subjects = Subject::all();
        return view('admin.subject', compact('subjects'));
    }

    /**
     * Create a new subject
     * Validates and stores subject information
     */
    public function storeSubject(Request $request)
    {
        $request->validate([
            'subject_code' => 'required',
            'description' => 'required',
            'units' => 'required|numeric',
            'course' => 'required'
        ]);

        Subject::create($request->all());
        return redirect()->back()->with('success', 'Subject added successfully');
    }

    /**
     * Update existing subject information
     * Validates and updates subject details
     */
    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'subject_code' => 'required',
            'description' => 'required',
            'units' => 'required|numeric',
            'course' => 'required'
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update($request->all());
        return redirect()->back()->with('success', 'Subject updated successfully');
    }

    /**
     * Delete a subject
     * Removes subject from the system
     */
    public function destroySubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return redirect()->back()->with('success', 'Subject deleted successfully');
    }

    /**
     * Display grades page
     * Shows all enrolled students with their subjects and grades
     */
    public function grades()
    {
        $enrolledStudents = Enrollment::with(['student', 'subjects'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.grades', compact('enrolledStudents'));
    }

    /**
     * Update student grades for enrolled subjects
     * Updates grades in the enrollment_subject pivot table
     */
    public function updateGrades(Request $request, Enrollment $enrollment)
    {
        $grades = $request->input('grades');
        
        foreach ($grades as $subjectId => $grade) {
            $enrollment->subjects()->updateExistingPivot($subjectId, ['grade' => $grade]);
        }

        return redirect()->back()->with('success', 'Grades updated successfully');
    }

    public function removeSubject($enrollmentId, $subjectId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->subjects()->detach($subjectId);
        return redirect()->back()->with('success', 'Subject removed successfully');
    }

    public function addSubject(Request $request, $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->subjects()->attach($request->subjects);
        return redirect()->back()->with('success', 'Subjects added successfully');
    }
}
