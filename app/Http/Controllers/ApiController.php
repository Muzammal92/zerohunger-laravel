<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\register_user;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function registeruser(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:register_users,email',
            'number' => 'required|unique:register_users,number',
            'password' => 'required',

        ]);
        $errors = $validator->errors();
       if($validator->fails()) {
                
        return response()->json([
             'message' => 'Validation Error!',
             'Errors' => $errors
         ]);
       }
       $register=new register_user;
        $register->name=$request->name;
        $register->email=$request->email;
        $register->number=$request->number;
        $register->city=$request->city;
        $register->address=$request->address;
        $register->license_number=$request->license_number;
        
        $register->type=$request->type;
        $register->password= Hash::make($request->password);
       
        $register->save();
        return response()->json([
            'Message'=>'Registered successfully.',
            'Self Registeration:' => $register
        ]);  
        
}




    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginapi(Request $request)
    {
        $login =register_user::where('email', $request->email)->orWhere('number', $request->email)->first();
        if(!empty($login)){
        
            if(Hash::check($request->password, $login->password)){
                return response()->json([
                    'Message'=> 'Data found Successfully',
                    'login'=>$login
                ]);
            }
            else{
                return response()->json([
                    'Message'=> 'No data found'
                ]);
            }
        
        }
        else{
            return response()->json([
                'Message'=> 'No email exist',]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkemail($email)
    
    {
        $check = register_user::where('email', $email)->first();
        if(!empty($check)){
            return response()->json([
                'Message'=> 'Email found successfully',]);
        }else{
            return response()->json([
                'Message'=> 'No email exist',]);

        }
    
    }
    
    public function resetpassword(Request $request){
        
        
        register_user::where('email', $request->email)
       ->update([
           'password' => Hash::make($request->password)
        ]);
         return response()->json([
                'Message'=> 'Password updated successfully',]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
