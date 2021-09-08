<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;


//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class UserController extends Controller {

    public function __construct() {
        $this->middleware(['auth']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index() {
    //Get all users and pass it to the view
        $users = User::whereHas(
            'roles', function($q){
                $q->where('id','!=' ,6);
            }
        )->get();
        return view('admin.users.index')->with('users', $users);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
    //Get all roles and pass it to the view
        $roles = Role::get();
        return view('admin.users.create', ['roles'=>$roles]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
    //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);

        $user = User::create($request->only('email', 'name', 'password')); //Retrieving only the email and password data

        $roles = $request['roles']; //Retrieving the roles field
    //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();            
            $user->assignRole($role_r); //Assigning role to user
            }
        }        
    //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully added.');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id) {
        return redirect('users'); 
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles

        return view('admin.users.edit', compact('user', 'roles')); //pass user and roles data to view

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id) {
       $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields    
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|unique:users,email,'.$id,
           
        ]);
        
        $input = $request->only(['name', 'email','password']); //Retreive the name, email and password fields
        $roles = $request['roles']; //Retreive all roles
         //$user->fill($input)->save();
         //dd($request->password);
        if ($request->password == null) {
           
            User::where('id','=',$id)->update([
                'email' => $request->email,
                'name' => $request->name,

            ]);
        }else{
            //dd('true');
            //dd($request->password);
            User::where('id','=',$id)->update([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name,

            ]);
        }

        if (isset($roles)) {        
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles          
        }        
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id) {
    //Find a user with a given id and delete
        $user = User::findOrFail($id); 
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }
    public function userEmailCheck(Request $request)
    {
        $email = $request->email; // This will get all the request data.
        $userCount = User::where('email', $email);
        if ($userCount->count()) {
            return \Response::json(array('msg' => 'true'));
        } else {
            return \Response::json(array('msg' => 'false'));
        }
    }
    public function editEmailCheck(Request $request)
    {
        //dd($request->all());
        $email = $request->email; 
        $id = $request->id;
        $userCount = User::where('id','!=',$id)->where('email', $email);
        if ($userCount->count()) {
            return \Response::json(array('msg' => 'true'));
        } else {
            return \Response::json(array('msg' => 'false'));
        }
    }
    public function updatestatus($id)
    {
        $is_active = User::where('id','=',$id)->get();
       //dd($is_active);
       if($is_active[0]->is_activate == 0){
           $update = User::where('id','=',$id)->update([
                'is_activate' => '1'
           ]);
           if ($update) {
               return 1;
           }
       } 
       if ($is_active[0]->is_activate == 1) {
        $update = User::where('id','=',$id)->update([
            'is_activate' => '0'
       ]);
       if ($update) {
           return 0;
       }
    }
    }
}