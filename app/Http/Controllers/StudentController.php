<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\Models\Teacher;
use Hash;
use Session;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all() ;
        
        return view('student',['students'=>$students,'layout'=>'index']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $students = Student::all() ;
      return view('student',['students'=>$students,'layout'=>'create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student = new Student() ;
        $student->cne = $request->input('cne') ;
        $student->firstName = $request->input('firstName') ;
        $student->secondName = $request->input('secondName') ;
        $student->age = $request->input('age') ;
        $student->speciality = $request->input('speciality') ;
        $student->save() ;
        return redirect('/') ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id);
        $students = Student::all() ;
        return view('student',['students'=>$students,'student'=>$student,'layout'=>'show']);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $student = Student::find($id);
      $students = Student::all() ;
      return view('student',['students'=>$students,'student'=>$student,'layout'=>'edit']);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $student = Student::find($id);
      $student->cne = $request->input('cne') ;
      $student->firstName = $request->input('firstName') ;
      $student->secondName = $request->input('secondName') ;
      $student->age = $request->input('age') ;
      $student->speciality = $request->input('speciality') ;
      $student->save() ;
      return redirect('/') ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $student = Student::find($id);
      $student->delete() ;
      return redirect('/') ;
    }

   

  public function registerUser(Request $req)
  {
    $req->validate(['username'=>'required', 'email'=>'required|unique:users', 'password'=>'required|min:6','cpassword'=>'required|same:password']);
    $user = new Teacher();
    $user->name= $req->username;
    $user->email=$req->email;
    $user->password=Hash::make($req->password);
    $res=$user->save();
    if($res)
    {
    return back()->with('Success','You have registered successfully');
    }
    else
    {
        return back()->with('fail','Something wrong');
    }
}

 public function loginUser(Request $req)
    {
        
        $user = Teacher::where('email','=',$req->email)->first();
        if($user)
        {
            if(Hash::check($req->password,$user->password))
            {
             $req->session()->put('loginId',$user->id);
             return redirect('index');
            } 
        else{
            return back()->with('fail',"Password Doesn't match") ;      
             }
        }
        else
        {
              return back()->with('fail','This email is not registered');
        }
    }

    public function logout()
  {
    if(Session::has('loginId'))
    {
        Session::pull('loginId');
        return redirect('/');
    }
  }
}
