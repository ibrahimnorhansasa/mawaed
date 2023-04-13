<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Http\Resources\DoctorResource;
use DB;

class DoctorController extends Controller
{
    public function index()
    {
       
        return DoctorResource::collection(Doctor::paginate(40));

    }


    public function show(Doctor $doctor)
    {
       
        return new DoctorResource($doctor);

    }
    
    
    
}