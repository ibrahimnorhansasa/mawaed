<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalCenter;
use App\Http\Resources\CenterResource;
use DB;

class CenterController extends Controller
{
    public function index()
    {
       
        return CenterResource::collection(MedicalCenter::paginate(40));

    }


    public function show(MedicalCenter $center)
    {
       
        return new CenterResource($center);

    }
    
    
    
}