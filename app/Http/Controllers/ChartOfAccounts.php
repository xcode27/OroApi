<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\ListOfChartsOfAccounts;
use DataTables;
use App\module_auth;

class ChartOfAccounts extends Controller
{
    //
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

    public function createChartOfAccounts(Request $request){

    		//dd($request);

    	try{
		    	$validator = Validator::make($request->all(), [
		                                        'account_list' => 'required|string',
		                                        'account_no' => 'required|string',
		                                        'account_title' => 'required|string|max:50',
		                                        'account_description' => 'required|string|min:10|max:255',
		                                    ]);

		    	if($validator->fails()){
		    		return response()->json(['errors'=>$validator->errors()->all()]);
		    	}

		    	if(ListOfChartsOfAccounts::where('account_no', '=', $request->input('account_no'))->exists()){

		                 return response(['status'=>'error', 'message' => 'Chart of account title already exists']);

		            }else{

		                $chartOfAccounts = ListOfChartsOfAccounts::create([
		                            'account_list' => $request->input('account_list'),
		                            'account_no' => $request->input('account_no'),
		                            'account_title' => $request->input('account_title'),
		                            'account_description' =>  $request->input('account_description'),
		                ]);
		            
		                return response(['status'=>'success', 'message' => 'Chart of account saved']);
		            }

         }catch(\Exception $e){
	          return $e;
	      }

    }

    public function ListOfAccounts(){

    	$controls = module_auth::select('create','read','update','delete')->where('user_id',JWTAuth::user()->id)->get();

    	 $details = ListOfChartsOfAccounts::select('id','account_no',
    	 															DB::raw('(CASE WHEN account_list = "asset" THEN "Assets" 
    	 																		   WHEN account_list = "lia" THEN "Liabilities" 
    	 																		   WHEN account_list = "owner" THEN "Owners’ equity or Shareholder’s Equity"
    	 																		   WHEN account_list = "rev" THEN "Revenues"
    	 																		   WHEN account_list = "cost" THEN "Cost of goods sold"
    	 																		   WHEN account_list = "oper" THEN "Operating expenses"
    	 																		   ELSE "Other relevant accounts" END) AS accounts_list')
    	 											,'account_title','account_description')->get();
                   
         return (DataTables::of($details)
                            ->addColumn('action', function($details){
                                return ' <button class="btn btn-primary" title="Update Accounts" value="'.$details->account_no.'" id="btnUpdate" onclick="getDetails(this.value)" data-toggle="modal" data-target="#modModal" style="cursor:pointer;"><i class="fa fa-edit"></i></button> &nbsp; <button class="btn btn-danger" title="Remove Accounts" value="'.$details->id.'"  id="btnDelete" onclick="delAccounts(this.value)" style="cursor:pointer;"><i class="fa fa-trash"></i></button>';
                            })
                            ->make(true));
    
    }

     public function removeAccounts(Request $request)
     {
        //
        $user = ListOfChartsOfAccounts::findOrFail($request->input('id'));

        if($user->delete()){
                return response(['status'=>'success', 'message' => 'Accounts successfully removed']);
        }
     }
}
