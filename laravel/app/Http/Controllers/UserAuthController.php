<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use Carbon\Carbon;
use App\Models\PasswordResetToken;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use App\Models\Consent;

class UserAuthController extends Controller
{
    public function generateOTP(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
        ]);    
        $email = $request->username.'@aeriesys.com';
        $user = User::where('email', $email)->first();
        if($user)
        {
            $otp = rand(100000, 999999);
            Otp::create([
                'user_id' => $user->user_id,
                'otp' => Hash::make($otp),
                'created_at'=>Carbon::now()
            ]);
            Mail::to('savita@aeriesys.com')->send(new OTPMail($otp));
            return response()->json([
                'message' => 'Generated OTP has been sent to registered email',                    
            ]);
        }      
        else 
        {
            return response()->json([
                'message' => 'These credentials do not match our records',
            ], 422);
        }      
    }
    
    public function verifyOTP(Request $request)
    {
        $data = $request->validate([
            'username' => 'required', 
            'otp' => 'required|numeric',
        ]);
        $email = $request->username.'@aeriesys.com';
        $user = User::where('email', $email)->first();
        if (!$user) 
        {
            return response()->json(['message' => 'User not found'], 404);
        }
        $latestOTP = Otp::where('user_id', $user->user_id)->latest()->first()->otp;
        if (Hash::check($request->otp, $latestOTP)) 
        {            
            return response()->json(['message' => 'OTP verification is successfull'], 200);
        } 
        else 
        {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'mobile_no' => 'required|regex:/^\+?[1-9]\d{9}$/',
            'email' => 'required|email|max:100|unique:users,email,'.$request->user_id.',user_id',
            'address' => 'sometimes|nullable',
        ];

        if ($request->hasFile('avatar')) {
            $rules['avatar'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['avatar'] = 'nullable';
        }
        $data = $request->validate($rules);

        $user = User::where('user_id', Auth::user()->user_id)->first();
        if ($request->hasFile('avatar')) {
            $avatar = time().'.'.$request->file('avatar')->getClientOriginalExtension();
            $request->avatar->move(public_path('storage/users'), $avatar);
            $data['avatar'] = $avatar;
        } 
        else {
            $data['avatar'] = $user->avatar;
        }

        $user->update($data);
        return new UserResource($user);
    }

    public function updateUserProfile(Request $request)
    { 
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|max:100', 
            'mobile_no' => 'required|regex:/[0-9]{10}/|digits:10',
            'address' => 'sometimes|nullable',
            'avatar' => 'sometimes|nullable|string', 
        ]);

        $avatarName = null; 

        if(strpos($request->avatar, 'base64') !== false) 
        {
            $image_parts = explode(";base64,", $request->avatar);
            $image_base64 = base64_decode($image_parts[1]);
            $image_type_aux = explode("image/", $image_parts[0]);
            $url = date('Ymdhis').".".$image_type_aux[1];
            $path = public_path().'/storage/users/'.$url;
            file_put_contents($path, $image_base64);
            $data['avatar'] = $url;
        } 
        $users = Auth::User();
        $user =  User::where('user_id', $users->user_id)->first();
        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user)
        ], 200);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100|regex:/^[a-z0-9]+([._][a-z0-9]+)*@[a-z0-9]+([.-][a-z0-9]+)*\.[a-z]{2,}$/',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) 
        {
            $token = $user->createToken('token')->plainTextToken;
            $consent = Consent::where('user_id' , $user->user_id)->where('consent', true)->first();

            $response = [
                'user' => new UserResource($user),
                'token' => $token,
                'consent' => $consent
            ];
            return response()->json($response, 200);
        }
        else
        {
            return response([
                'errors' => [
                    'email' => ['These credentials do not match.'],
                ],
                'message' => 'These credentials do not match.' 
            ], 422);
        }
    }

    public function addConsent(Request $request)
    {
        $data = $request->validate([
            'consent' => 'required|boolean|accepted',
            'user_id' => 'required|exists:users,user_id'
        ]);

        $consent = Consent::updateOrCreate($data);

        return response()->json(['consent' => $consent]);
    }

    public function deleteConsent(Request $request)
    {
        $consent = Consent::where('consent_id', $request->consent_id)->first();
        if ($consent) {
            $consent->update([
                'consent' => false
            ]);
            return $consent;
        }
        return false;
    }

    
    public function logout()
    {
        $user = Auth::User();
        if($user)
        {
            $user->tokens()->delete();
            return response([
                'message'=>'Logged out Successfully'
            ],200);
        }
        else{
            return response([
                'error'=>'Unable to Log Out. Please try again later!'
            ],404);
        }
    }
    
    public function me()
    {   
        return new UserResource(Auth::User());
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);
        $user = User::where('user_id', Auth::User()->user_id)->first();
        if (!(Hash::check($request->current_password, $user->password)))
        {
            return response()->json([
                'errors' => [
                    'current_password' => ['Your current password does not matches with the password you provided. Please try again'],
                ]
            ], 422);
        }
        else if(strcmp($request->new_password, $user->name) == 0)
        {
            return response()->json([
                'errors' => [
                    'new_password' => ['New Password cannot be same as username'],
                ]
            ], 422);
        }
        else if(strcmp($request->current_password, $request->new_password) == 0)
        {
            return response()->json([
                'errors' => [
                    'new_password' => ['New Password cannot be same as your current password. Please choose a different password.'],
                ]
            ], 422);
        }
        else if(strcmp($request->new_password, $request->confirm_password) != 0)
        {
            return response()->json([
                'errors' => [
                    'confirm_password' => ['The password confirmation does not match'],
                ]
            ], 422);
        }
        else
        {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            return response()->json([
                'message' => ['Password Updated Successfully'],
            ], 200);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'This email does not exist in our system'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $otp = mt_rand(100000, 999999);
        $passwordResetToken = PasswordResetToken::firstOrNew(['email' => $request->email]);

        if ($passwordResetToken->exists) 
        {
            $passwordResetToken->update([
                'otp' => Hash::make($otp),
                'token' => Str::random(60),
            ]);
        } 
        else 
        {
            $passwordResetToken->otp = Hash::make($otp);
            $passwordResetToken->token = Str::random(60);
            $passwordResetToken->save();
        }

        $mailContent = "Hello {$user->name},\n\n".
               "You have requested to reset your password. Please use the following OTP to proceed:\n".
               "OTP: $otp\n".
               "If you did not request this, please ignore this email.\n\n".
               "Thank you,\n";

        Mail::raw($mailContent, function ($message) use ($user) {
            $message->to($user->email)->subject('Reset Password');
        });

        return response()->json([
            'success' => 'Please check your email for the OTP'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        $email = $request->email;
        $otp = $request->otp;

        $passwordResetToken = PasswordResetToken::where('email', $email)->latest()->first();

        if (!$passwordResetToken) {
            return response()->json(['error' => 'Invalid or expired token'], 422);
        }

        if ($passwordResetToken->updated_at->diffInMinutes(now()) > 5) {
            return response()->json(['error' => 'Token has expired'], 422);
        }

        if (Hash::check($otp, $passwordResetToken->otp)) 
        {
            $user = User::where('email', $email)->first();
            if ($user) 
            {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
                $newOtp = mt_rand(100000, 999999); 
                $passwordResetToken->update([
                    'otp' => Hash::make($newOtp), 
                    'token' => Str::random(60),
                ]);
                $token = $user->createToken('password_reset')->plainTextToken;
                return response()->json(['message' => 'Password reset Successful']);
            } 
            else 
            {
                return response()->json(['error' => 'User not found.'], 422);
            }
        } 
        else 
        {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }
    }
}  