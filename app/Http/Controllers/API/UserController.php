<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cart;
use App\Models\Otp;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use DB; 
use App\Http\Requests\API\userRegisterRequest;
use App\Http\Requests\API\userUpdateRequest;
use App\Http\Requests\API\userLoginRequest;
use App\Http\Resources\userLoginResource;
use App\Http\Requests\API\changePasswordRequest;
use App\Http\Requests\API\forgotPasswordRequest;
use App\Http\Requests\API\resetPasswordRequest;
use Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\emailVerificationEmail;
use Illuminate\Support\Facades\Password;


class UserController extends Controller
{
  
    public function register(userRegisterRequest $request)
    {

         $user = User::create([
            'firstname'     =>  $request->firstname,
            'lastname'      =>  $request->lastname,
            'phone'         =>  $request->phone,
            'email'         =>  $request->email,
            'address'       =>  $request->address,
            'password'      => Hash::make($request->password),
        ]);


        if(isset($user->id)){
 
            return response()->json([
                'success'   => true,
                'data'      => new UserLoginResource($user),
             ],201);


        }else{

            return response()->json([
                'success'   => false,
                'message'   => 'please try again later', 
            ],502);

        }

    
  
    }



    public function getOtpRegister(Request $request){

        $otp=Otp::where('phone',$request->phone)->where('process',0)->first();

        
        return response()->json([

            'otp'   => $otp->code??0,
    

        ]);
    }

    //verfiied code
    public function verifyRegister(Request $request)
    {
             $codeVerfied = Otp::where('phone',$request->phone)

             ->where('code',$request->code)

             ->where('process',0)->first();

             if(isset($codeVerfied)){

                 $user = User::where('phone',$request->phone)->first();

                 $user->update([


                     'phone_verified_at'=>now(),

                 ]);

                 $codeVerfied->delete();

                 return response()->json([
                    'success'   => true,
                    'message'   => 'user account has been activated successfully',
                     'data'      =>  new userLoginResource($user)
                ],200);
                 
             }else{

                return response()->json([
                    'success'   => false,
                    'message'   => 'the code not correct',
                ],404);
            }

             

    }


  //login
    public function login(userLoginRequest $request)
    {
  
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

 
            return response()->json([
      
                'data'      =>  new userLoginResource(Auth::user())],200);
        }else{ 
            return  response()->json([
             
                'message'=>'please check your data and try again'],401);
        } 
    }




    //update

    public function update(userUpdateRequest $request)
    {
        $user = auth('api')->user();
      
        $user->update([
            'firstname'     =>  $request->firstname,
            'lastname'      =>  $request->lastname,
            'phone'         => $request->phone,
            'email'        => $request->email,
            'address'       =>$request->address,
            'password'      => Hash::make($request->password),
         
        ]);
        
        
        return response()->json([
        
            'success'   => true,
            'data'      =>  new userLoginResource($user)
        ],200); 
       }
    

    //show user profile

    public function show()
    {
     
        $user = Auth::user();
        
        return response()->json([
         
            'success'   => true,
            'data'      =>  new userLoginResource($user)
        ],200); 
    }
    
    public function changePassword(changePasswordRequest $request){

       //Change Password

        if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {

            return response()->json([
         
                'success'   => true,
                'message'   =>  'Check your old password'
            ],400); 
        }else{
         Auth::user()->update([

          'password' => Hash::make($request->new_password),
          
          
        ]);
       
        return  response()->json(['message'=>'Password changed successfully']);
    }

    } 

    public function forgot(forgotPasswordRequest $request) {

        $user = User::where('phone', $request->phone)->first();

        if(isset($user)){

            $this->sendOTP($user);

            return response()->json([
                'success'   => true,
                'message'   => 'OTP message sent successfully', 
            ],200);

        }else{

            
            return response()->json([
                'success'   => false,
                'message'   => 'this account not register before', 
            ],404);
        }
    }



    public function reset(resetPasswordRequest $request)
    {       

        $user = User::where('phone', $request->phone)->first();

        $user->update([

            'password' => Hash::make($request->new_password),
            
        ]);


        Otp::where('phone', $user->phone)->delete();

         
        return  response()->json([
                'success'   => true,
                'message'=>'Password set successfully'
            ], 200);
    }



    //verify reset


    public function verifyResetPassword(Request $request)
    {
             $codeVerfied = Otp::where('phone',$request->phone)

             ->where('code',$request->code)

             ->where('process',0)->first();

             if(isset($codeVerfied)){

                 return response()->json([
                    'success'   => true,
                    'message'   => 'the code is correct',
                ],200);
                 
             }else{

                return response()->json([
                    'success'   => false,
                    'message'   => 'the code not correct',
                ],404);
            }

             

    }

    //logout 
    public function signOut()
    {
        auth()->user()->tokens()->delete();

        return  response()->json([
     
            'message'=>'you are logout'
        ],200);

    }



    public function sendOtpAgain(Request $request){

        $user = User::where('phone', $request->phone)->first();

        if(isset($user)){ 

            $codeSended = Otp::where('phone',$request->phone)

                ->where('process',0)->first();

                //return $request->phone;
                if(isset($codeSended)){

                    $codeSended->delete();
                    
                }
                
                $this->sendOTP($user);

                return response()->json([
                    'success'   => true,
                    'message'   => 'OTP message sent successfully', 
                ],200);

        }else{

            
            return response()->json([
                'success'   => false,
                'message'   => 'this account not register before', 
            ],404);
        }
    }
 


    public function sendOTP(User $user)
    {
        
        $otpCode = rand(1000, 9999);

            
        $otp = Otp::updateOrCreate([
            'phone'     => $user->phone,
            'code'      => $otpCode,
            'process'   =>  0,
        ]);

        //send sms 
      

    
        

    }



   
   
  
}