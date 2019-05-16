<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\modules;
use App\module_auth;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\MappedMenus as MappedMenusResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DataTables;
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, token, X-Auth-Token, Origin, Authorization');


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::find(JWTAuth::user()->id)->AuthModules; //get all users;
        //$users = User::find(2)->AuthModules; //get all mapped modules corresponding with user id;
        return  UserResource::collection($users);
    }


    public function displayMappedMenu(){
        $mapped_modules = module_auth::all();
        return  MappedMenusResource::collection($mapped_modules);
    }

    public function authenticate(Request $request)
    {

        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = JWTAuth::user();

        return  response()->json(compact('user','token'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $module = $request->input('module');
        switch ($module) {
             case 'user':

                        $validator = Validator::make($request->all(), [
                                        'name' => 'required|string|max:255',
                                        'email' => 'required|string|email|max:255|unique:users',
                                        'username' => 'required|string|max:255',
                                        'password' => 'required|string|min:6',
                                        'usergroup' => 'required|integer',
                                    ]);

                        if($validator->fails()){
                                return response()->json(['errors'=>$validator->errors()->all()]);
                        }


                            if(User::where('username', '=', $request->input('username'))->exists()){
                                return response(['status'=>'error', 'message' => 'User already exists']);
                            }else{

                                $user = User::create([
                                            'name' => $request->input('name'),
                                            'email' => $request->input('email'),
                                            'username' => $request->input('username'),
                                            'password' =>  bcrypt($request->input('password')),
                                            'usergroup' => $request->input('usergroup'),
                                            
                                ]);
                            
                                $token = JWTAuth::fromUser($user);

                                //return response(['status'=>'success', 'message' => 'User saved']);
                                return response()->json(compact('user','token'),201);
                            }
                 break;

            case 'menus':
                        $module = new modules();

                        if(modules::where('module_description', '=', $request->input('module_name'))->exists()){
                            return response(['status'=>'error', 'message' => 'Module already exists']);
                        }else{

                           // $mod = 'localhost:82/DashboardTemplate/public/'.$request->input('module_url').'';

                            $module->module_description = $request->input('module_name');
                            $module->module_url = $request->input('module_url');
                            $module->system_use = $request->input('sys');
                            if($request->input('parentmenu') == ''){
                              $parent = 0;
                            }else{
                              $parent = $request->input('parentmenu');
                            }

                            $module->parent_menu = $parent;
                            $module->save();

                            return response(['status'=>'success', 'message' => 'Module saved']);
                        }

                break;

            case 'menu_auth_mapping':
                       
                       //$user_id = JWTAuth::user()->id;
                      // $usergroup = JWTAuth::user()->usergroup;

                        $module_mapping = new module_auth();
                        $isExists = module_auth::where('module_id', '=',$request->input('module_id'))
                                                ->where('user_id', '=', $request->input('user'))
                                                ->get();
                        if(count($isExists) > 0){
                            return response(['status'=>'error', 'message' => 'Module already mapped']);
                        }else{
                           // $module_mapping->user_id = $user_id ; //should be auth id

                          //return $request->input('add');
                            $module_mapping->module_id = $request->input('moduleid'); ; //dropdown
                            $module_mapping->create = $request->input('add');
                            $module_mapping->read = $request->input('read');
                            $module_mapping->update = $request->input('update');
                            $module_mapping->delete = $request->input('delete');
                            $module_mapping->user_id = $request->input('user');
                            $module_mapping->save();

                            return response(['status'=>'success', 'message' => 'Module successfully mapped']);
                        }

                break;

                case 'edit_menu_auth_mapping':
                       
                        $module_mapping = module_auth::where('id', '=',$request->input('moduleid'))->first();
                      
                           // $module_mapping->module_id = $request->input('moduleid'); ; //dropdown
                            $module_mapping->create = $request->input('add');
                            $module_mapping->read = $request->input('read');
                            $module_mapping->update = $request->input('update');
                            $module_mapping->delete = $request->input('delete');
                           // $module_mapping->user_id = $request->input('user');
                            $module_mapping->save();

                            return response(['status'=>'success', 'message' => 'Module successfully updated']);
                        

                break;

                case 'update_credential':
                       
                              $usercredentials = User::where('username', '=',$request->input('oldun'))->first();
                              if($usercredentials){

                                if(Hash::check($request->input('oldpw'), $usercredentials->password))
                                {
                                      
                                      $usercredentials->username = $request->input('newun');
                                      $usercredentials->password = bcrypt($request->input('newpw'));

                                      $usercredentials->save();

                                      return response(['status'=>'success', 'message' => 'Credential successfully updated']);

                                    }else{
                                          return response(['status'=>'error', 'message' => 'Invalid password']);
                                    }
                                }else{
                                          return response(['status'=>'error', 'message' => 'Invalid username']);
                                }
                        

                break;

         } 
        

    }

    public function getAuthenticatedUser()
    {
            try {

                    if (! $user = JWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            return response()->json(compact('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $test = $request->input('module').'-'.$id;
        return response($test);
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
        $user = User::findOrFail($id);

        if($user->delete()){
                return response(['status'=>'success', 'message' => 'User removed']);
        }
    }

    public function open() 
    {
        $data = "This data is open and can be accessed without the client being authenticated";
        return response()->json(compact('data'),200);

    }

    public function getMenus() 
    {
       $menus = modules::select('id','module_description')->get();
        return \Response::json($menus);
    }

    public function getUser() 
    {
       $user = User::select('id','name')->get();
        return \Response::json($user);
    }

    public function getUserMenu(Request $request) 
    {
     
      //dd($request->all());
       $user = DB::table('module_auths')
                                ->where('module_auths.user_id','=',JWTAuth::user()->id)
                                ->where('modules.system_use','=',$request->input('system_use'))
                                ->where('modules.parent_menu',$request->input('module_id'))
                                ->join('users', 'users.id','module_auths.user_id')
                                 ->join('modules', 'modules.id','module_auths.module_id')
                                ->Select("modules.id","modules.module_description","modules.module_url","users.usergroup",'modules.parent_menu')
                                ->get();
        return \Response::json($user);
    }

    public function getParentMenu(Request $request){

      $user = DB::table('module_auths')->where('module_auths.user_id','=',JWTAuth::user()->id)
                                ->where('modules.system_use','=',$request->input('system_use'))
                                ->where('modules.parent_menu',0)
                                ->join('users', 'users.id','module_auths.user_id')
                                 ->join('modules', 'modules.id','module_auths.module_id')
                                ->Select("modules.id","modules.module_description","modules.module_url","users.usergroup")
                                ->get();
        return \Response::json($user);

    }

    public function checkUserControl(){
       $controls = module_auth::select('module_id','create','read','update','delete')->where('user_id',JWTAuth::user()->id)->get();
       return \Response::json($controls);
    }

    public function getUserAccess(){
      
        try{

             $details = DB::table('module_auths')
                                ->join('users', 'users.id','module_auths.user_id')
                                 ->join('modules', 'modules.id','module_auths.module_id')
                                ->Select('users.id as userId','module_auths.id','module_auths.module_id','modules.module_description','users.name','modules.module_url',
                                          DB::raw("(CASE WHEN module_auths.create = '0' THEN 'NO' ELSE 'YES' END) AS CreateMenu"),
                                          DB::raw("(CASE WHEN module_auths.read = '0' THEN 'NO' ELSE 'YES' END) AS ReadMenu"),
                                          DB::raw("(CASE WHEN module_auths.update = '0' THEN 'NO' ELSE 'YES' END) AS UpdateMenu"),
                                          DB::raw("(CASE WHEN module_auths.delete = '0' THEN 'NO' ELSE 'YES' END) AS DeleteMenu")
                                        )
                                ->get();

               return (DataTables::of($details)
                                  ->addColumn('action', function($details){
                                    $data = $details->userId.'@'.$details->id.'@'.$details->CreateMenu.'@'.$details->ReadMenu.'@'.$details->UpdateMenu.'@'.$details->DeleteMenu.'@'.$details->module_id;
                                      return ' <button class="btn btn-primary" title='.$details->id.'  id='.$data.' onclick="getDetails(this.id)" data-toggle="modal" data-target="#modModal" style="cursor:pointer;"><i class="fa fa-edit"></i></button> &nbsp; <button class="btn btn-danger" title="Remove Menu"   id='.$details->id.' onclick="removeMenuMapped(this.id)" style="cursor:pointer;"><i class="fa fa-trash"></i></button>';
                                  })
                                  ->make(true));

        }catch(\Exceptions $e){
          return $e;
        }
    }

    public function deleteUserMapped(Request $request)
    {
        //
        $menus = module_auth::findOrFail($request->input('id'));

        if($menus->delete()){
                return response(['status'=>'success', 'message' => 'Menu mapped successfully removed']);
        }
    }
}
