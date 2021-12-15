<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    // LIST ALL USERS
    public function  index() {
        $students = Student::paginate(2);
        return response()->json([
            'students' => $students,
        ]);
    }

    // SEARCH USER BY LAST_NAME OR FIRST_NAME
    public function  search(Request $request) {
        $postData = $request->json()->all();
        $students = Student::where('first_name',$postData['first_name'])
                    ->orWhere('last_name',$postData['last_name'])
                    ->paginate(2);
        return response()->json([
            'students' => $students,
        ]);
    }


    // UPLOAD DATA THROUGH CSV FILE
    public function  upload(Request $request) {
        $file = $request->file('students');
        $studentsData = $this->fileToArray($file);
        $created = Student::insert($studentsData);
        return response()->json($studentsData);
    }

    // CONVERT CSV TO ARRAY
    function fileToArray($filename = '', $seprator = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $studentdata = array();
        if (($file = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($file, 1000, $seprator)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $studentdata[] = array_combine($header, $row);
            }
            fclose($file);
        }

        return $studentdata;
    }

}
