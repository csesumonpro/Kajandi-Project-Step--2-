<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Product;
use DB;
use App\SellerProduct;
class FSortByController extends Controller{
    public function product_details($id){
        $product_by_id = SellerProduct::find($id);
        return view('frontend.product.product_single')->with(compact('product_by_id'));
    }
    public function shop_content(){
        $all_products = SellerProduct::where('pro_status',1)->orderBy('id','desc')->paginate(12);
        return view('frontend.product.shop')
            ->with(compact('all_products'));
    }
    public function product_by_category($id){
        $all_products = SellerProduct::where('pro_cat_id',$id)->where('pro_status',1)->paginate(12);
        return view('frontend.product.shop')->with(compact('all_products'));
    }
    public function product_by_sub_category($id){
        $all_products = SellerProduct::where('sub_cat_id',$id)->paginate(12);
        return view('frontend.product.shop')->with(compact('all_products'));
    }
    public function product_by_manufacturer($id){
        $all_products = SellerProduct::where('manufacturer_id',$id)->paginate(12);
        return view('frontend.product.shop')->with(compact('all_products'));
    }
    public function product_by_model($id){
        $all_products = SellerProduct::where('model_id',$id)->paginate(12);
        return view('frontend.product.shop')->with(compact('all_products'));
    }
    public function search(Request $request){
        $search = $request->search;
        $all_products = DB::table('products')
            ->where('products.name', 'LIKE', "%$search%")
            ->orWhere('products.description', 'LIKE', "%$search%")
            ->orWhere('products.price', 'LIKE', "%$search%")
            ->orWhere('products.hot', 'LIKE', "%$search%")
            ->paginate(12);
        return view('frontend.product.shop')
            ->with(compact('all_products'));
    }
    public function advance_search(Request $request){
        $search = $request->keyword;
        $cat = $request->category;
        if($search!=NULL && $cat=='All Departmens') {
            $all_products = DB::table('products')
                ->where('products.name', 'LIKE', "%$search%")
                ->orWhere('products.description', 'LIKE', "%$search%")
                ->orWhere('products.price', 'LIKE', "%$search%")
                ->orWhere('products.hot', 'LIKE', "%$search%")
                ->paginate(12);
                return view('frontend.product.shop')
                    ->with(compact('all_products'));
            }
        elseif($cat!=NULL && $search!=NULL){
            $count = SellerProduct::where('cat_id',$cat)->count();
            if($count=='0'){
                $all_products = DB::table('products')
                    ->where('products.sub_cat_id', $cat)
                    ->where('products.name', 'LIKE', "%$search%")
                    ->orWhere('products.description', 'LIKE', "%$search%")
                    ->orWhere('products.price', 'LIKE', "%$search%")
                    ->orWhere('products.hot', 'LIKE', "%$search%")
                    ->paginate(12);
                return view('frontend.product.shop')->with(compact('all_products'));
            }
        elseif($count!='0'){
            $all_products = DB::table('products')
                ->where('products.cat_id', $cat)
                ->where('products.name', 'LIKE', "%$search%")
                ->orWhere('products.description', 'LIKE', "%$search%")
                ->orWhere('products.price', 'LIKE', "%$search%")
                ->orWhere('products.hot', 'LIKE', "%$search%")
                ->paginate(12);
            return view('frontend.product.shop')->with(compact('all_products'));
            }

        }elseif($search==NULL && $cat!=NULL){
            $all_products = SellerProduct::where('cat_id',$cat)->count();
            if($all_products=='0'){
                $all_products = SellerProduct::where('sub_cat_id',$cat)->paginate(12);
            }elseif($all_products!='0'){
                $all_products = SellerProduct::where('cat_id',$cat)->paginate(12);
            }
            return view('frontend.product.shop')->with(compact('all_products'));
        }

    }
    public function product_sorting(Request $request){
        if($request->product_sort=='new_est'){
            $all_products = SellerProduct::orderBy('id','desc')->paginate(12);
            return view('frontend.product.shop')
                ->with(compact('all_products'));
        }elseif($request->product_sort=='best_rated'){
          echo "BEST Rated Comming ....";
        }elseif($request->product_sort=='low_price'){
            $all_products = SellerProduct::orderBy('price','asc')->paginate(12);
            return view('frontend.product.shop')
                ->with(compact('all_products'));
        }elseif($request->product_sort=='high_price'){
            $all_products = SellerProduct::orderBy('price','desc')->paginate(12);
            return view('frontend.product.shop')
                ->with(compact('all_products'));
        }elseif($request->product_sort=='a_to_z'){
            $all_products = SellerProduct::orderBy('name','asc')->paginate(12);
            return view('frontend.product.shop')
                ->with(compact('all_products'));
        }elseif($request->product_sort=='z_to_a'){
            $all_products = SellerProduct::orderBy('name','desc')->paginate(12);
            return view('frontend.product.shop')
                ->with(compact('all_products'));
        }
    }
    public function product_sorting_item(Request $request){
        if($request->product_item=='nine_item'){
            $all_products = SellerProduct::take('9')->orderBY('id','desc')->get();
            return view('frontend.product.item_page')
                ->with(compact('all_products'));
        }elseif($request->product_item=='twelve_item'){
            $all_products = SellerProduct::take('12')->orderBY('id','desc')->get();
            return view('frontend.product.item_page')
                ->with(compact('all_products'));
        }elseif($request->product_item=='eighteen_item'){
            $all_products = SellerProduct::take('18')->orderBY('id','desc')->get();
            return view('frontend.product.item_page')
                ->with(compact('all_products'));
        }elseif($request->product_item=='all_item'){
            $all_products = SellerProduct::all();
            return view('frontend.product.item_page')
                ->with(compact('all_products'));
        }
    }
    public function product_by_cat(Request $request){
        $cat = $request->pro_by_cat;
        $all_products = SellerProduct::where('cat_id',$cat)->count();
        if($all_products=='0'){
            $all_products = SellerProduct::where('sub_cat_id',$cat)->paginate(12);
        }elseif($all_products!='0'){
            $all_products = SellerProduct::where('cat_id',$cat)->paginate(12);
        }
        return view('frontend.product.shop')->with(compact('all_products'));
    }
    public function error_page(){
        return view('frontend.page.error_page');
    }

}
