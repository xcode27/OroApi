<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StoreOro;
use App\Products;
use App\orderprodhead;
use App\orderProdData;
use App\custtranhead;
use App\custtrandata;
use App\Orouser;
use App\ActualInventory;
use App\ActualInventoryDetails;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, token, X-Auth-Token, Origin, Authorization');

class OroStore extends Controller
{
    //

    public function getStore(Request $request){
        //done
            $store = StoreOro::select('CONTROLNO','CONTACT_CODE','CONTACT_NAME')->where('SPVR_USER_SYS_CODE',$request->input('sup'))->orderBy('CONTACT_NAME','ASC')->get();
             return \Response::json($store);

           
    }

    public function allgetStore(){
            //done
        // $client = new Client();
           //     $response = $client->request('POST',
             //       'http://192.168.1.55:805/api_cod/index.php?type=allstore'
              //  );
            //$store = json_decode($response->getBody());

           //  return \Response::json($store);
           
    }


    public function getProducts(){

    	$store = Products::select('PROD_SYS_CODE','PROD_DESC','PROD_COLOR_CODE')->where('PROD_TYPE', 'F')->get();

    	return \Response::json($store);

    }

    public function getAllProducts(){
        //done
        $store = Products::select('PROD_SYS_CODE','PROD_DESC','PROD_COLOR_CODE')->get();

        return \Response::json($store);

    }

    public function getDrSr(Request $request){
        //done
    	$data = custtranhead::select('TRAN_NO','DOC_NO','TRAN_DATE')->where('DOC_NO',$request->input('dr'))->orderBy('DOC_NO', 'desc')->get();

    	return \Response::json($data);
    }

    public function displayrawData(Request $request){

        $details = custtrandata::select('HEAD_TRAN_NO','PROD_SYS_CODE','PROD_DESC','QTY')->where('HEAD_TRAN_NO', $request->input('tranno'))->get();
                   
         return \Response::json($details);
    }

    public function getSupervisor(){
        //done
        $details = Orouser::select('USER_SYS_CODE','USER_NAME')->where('CATEGORY','S')->orderBy('USER_NAME','ASC')->get();
                   
         return \Response::json($details);
    }

    public function saveAdjustmentEntry(Request $request){

       

        $date_create = date('Y-m-d H:i:s');

        try{

             $validator = Validator::make($request->all(), [
                                        'docno' =>'required',
                                        'warehouse' =>'required',
                                        'entrydate' => 'required',
                                      
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        
                        $act_inv = ActualInventory::select('id')->where('doc_no', $request->input('docno'))->first();
                         if(count($act_inv) > 0){
                                    
                                return response(['status'=>'error', 'message' => 'Record Already exists.']);

                            }else{

                                $act = ActualInventory::create([
                                            'doc_no' => $request->input('docno'),
                                            'inv_date' => $request->input('entrydate'),
                                            'remarks' => $request->input('remarks'),
                                            'warehouse' => $request->input('warehouse'),
                                            'created_by' => $request->input('user'),
                                            'date_created' =>  $date_create,    
                                ]);
                                    
                                return response(['status'=>'success', 'message' => 'Inventory main detials saved. Please add products for inventory entry.']);
                                   
                            }

        }catch(\Exception $e){
                return $e;
        }
    }

    public function addProductToList(Request $request){

         try{

             $validator = Validator::make($request->all(), [
                                        'docno' =>'required',
                                        'product' => 'required',
                                        'qty' => 'required',
                                      
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        
                        $act_inv = ActualInventory::select('id')->where('doc_no', $request->input('docno'))->first();
                        
                        $checking = ActualInventoryDetails::Select('head_id')->where('head_id', $act_inv->id)
                                                                             ->where('prod_sys_codes', $request->input('product'))->first();
                        if(count($checking) > 0){

                                return response(['status'=>'error', 'message' => 'Item already recorded']);

                        }else{
                        $act = ActualInventoryDetails::create([
                                    'head_id' => $act_inv->id,
                                    'prod_sys_codes' => $request->input('product'),
                                    'product_desc' => $request->input('product_name'),
                                    'qty' => $request->input('qty'),
                                        
                        ]);
                            
                             return response(['status'=>'success', 'message' => 'Inventory successfully saved. Thank you.']);
                        }     
                            

        }catch(\Exception $e){
                return $e;
        }

    }

    public function displayAdjusment(){

        $data = ActualInventory::select('id','doc_no','remarks','inv_date','warehouse')->get();

        return (DataTables::of($data)
                                ->addColumn('action', function($data){
                                    $param = $data->id .'/'.$data->doc_no.'/'.$data->remarks.'/'.$data->inv_date.'/'.$data->warehouse;
                                    return ' <button class="btn btn-primary"   id="'.$param.'" onclick="getDetails(this.id)"   style="cursor:pointer;"><i class="fa fa-edit"></i></button>';
                                })
                                ->make(true));
    }

    public function displayList(Request $request){

        $act_inv = ActualInventory::select('id')->where('doc_no', $request->input('docno'))->first();
        $data = ActualInventoryDetails::select('id','prod_sys_codes','product_desc','qty')->where('head_id',$act_inv->id)->get();

        return (DataTables::of($data)
                                 ->addColumn('action', function($data){
                                    $param = $data->id .'/'.$data->prod_sys_codes.'/'.$data->qty;
                                    return ' <button class="btn btn-primary"   id="'.$param.'" onclick="getListinfo(this.id)"   style="cursor:pointer;"><i class="fa fa-edit"></i></button> 
                                    &nbsp;<button class="btn btn-danger"   id="'.$data->id.'" onclick="deleteProd(this.id)"   style="cursor:pointer;"><i class="fa fa-trash"></i></button>';
                                })->make(true));
    }

    public function deleteEntry(Request $request){
       
        $getId = ActualInventory::select('id')->where('doc_no', $request->input('docno'))->first();

        if(count($getId) > 0){

            $details = ActualInventoryDetails::where('head_id', $getId->id)->delete();

            $inv = ActualInventory::where('id', $getId->id)->delete();
            return response(['status'=>'success', 'message' => 'Inventory entry successfully removed']);
        }else{
            return response(['status'=>'error', 'message' => 'No record to be remove']);
        }
        
    }

    public function updateProductToList(Request $request){
       
          try{

                        
                        $act = ActualInventoryDetails::where('id', $request->input('prodid'))->first();
                        
                        $act->prod_sys_codes = $request->input('product');
                        $act->product_desc = $request->input('product_name');
                        $act->qty = $request->input('qty');

                        $act->save();

                        return response(['status'=>'success', 'message' => 'Product list item successfully update. Thank you.']);
                           
                            

        }catch(\Exception $e){
                return $e;
        }
        
    }

     public function deleteProductEntry(Request $request){
     
        try{

            $details = ActualInventoryDetails::where('id', $request->input('id'))->delete();

            return response(['status'=>'success', 'message' => 'Product entry successfully removed']);
       
        }catch(\Exception $e){
                return $e;
        }
        
    }

    public function updateAdjustmentEntry(Request $request){

       

        $date_create = date('Y-m-d H:i:s');

        try{

             $validator = Validator::make($request->all(), [
                                        'docno' =>'required',
                                        'entrydate' => 'required',
                                        'warehouse' => 'required',
                                      
                                    ]);

                        if($validator->fails()){
                                return response()->json(['status'=>'error','message'=>$validator->errors()->all()]);
                        }

                        
                        $lol = ActualInventory::where('id', $request->input('recid'))->first();
                     
                        //return $act_inv;
                       
                             $lol->doc_no = $request->input('docno');
                             $lol->inv_date = $request->input('entrydate');
                             $lol->remarks = $request->input('remarks');
                             $lol->warehouse = $request->input('warehouse');
                             $lol->save();
                       
                                    
                        return response(['status'=>'success', 'message' => 'Inventory main detials saved. Please add products for inventory entry.']);
                                   
                            

        }catch(\Exception $e){
                return $e;
        }
    }

     public function getProdGroup(){
        //done
        $details = orderprodhead::select('tran_sys_code','tran_name')->get();
                   
         return \Response::json($details);
    }

     public function loadProductFromGroup(Request $request){

        try {
       $details = DB::connection('mysql2')->table('order_prod_data as t1')
                        ->select("T1.sortorder","t1.tran_no","t1.tran_sys_code","t2.PROD_SYS_CODE","t2.PROD_DESC")             
                        ->join("mnt_products AS t2", "t1.PROD_SYS_CODE", "=", "t2.PROD_SYS_CODE")
                        ->where("t1.tran_sys_code", "=",$request->input('trancode'))
                        ->orderby('sortorder', 'asc')->get();
           
           return \Response::json($details);
         }catch(\Exception $e){
                return $e;
        }
    }
}
