<?php

namespace App\Http\Controllers;

use App\ContactSupplier;
use Illuminate\Http\Request;
use App\Order;
use Auth;
use Mail;

class SellerOrderController extends Controller
{
    public function VendorOrderList()
    {
      $ordered = Order::
              join('order_details', 'order_details.id', '=', 'orders.id')
            ->join('users','users.id', '=', 'cus_id')
            ->join('seller_products', 'seller_products.id', '=', 'order_details.product_id')
            ->join('sellers', 'sellers.id', '=', 'seller_products.seller_id')
            ->select(
                      'orders.*',
                      'users.name as cust_name',
                      'seller_products.pro_name as pros_name',
                      'seller_products.pro_name',
                      'seller_products.id as sp_id',
                      'seller_products.pro_image',
                      'sellers.id as s_id',
                      'order_details.product_qty',
                      'order_details.id as od_id'

                    )
            ->where('sellers.id', Auth::id())
            ->get();
      return view('seller.order.order_list',compact('ordered'));
    }
    public function VendorMessageList(){
        $all_message = ContactSupplier::where('seller_id',Auth::user()->id)->orderBy('created_at','desc')->get();
        return view('seller.message.message_list',compact('all_message'));
    }
    public function VendorMessageView($id){
        $msg  = ContactSupplier::find($id);
        return view('seller.message.view_message',compact('msg'));
    }
    public function VendorMessageReplay(Request $request){
        $data =[
            'email'=>$request->email,
            'content'=>$request->message,
            'subject'=>$request->subject
        ];

        Mail::send('seller.message.message_template',$data,function ($variable) use ($data){
            $variable->to($data['email']);
            $variable->subject($data['subject']);
            $variable->from('r25n.office@gmail.com');
        });
        return back()->with('message_success', 'Email Sent Succesfully........');
    }
    public function VendorMessageDelete($id){
        $msg = ContactSupplier::find($id);
        $msg->delete();
        return back()->with('message_success','Message Deleted Successfully');
    }
}
