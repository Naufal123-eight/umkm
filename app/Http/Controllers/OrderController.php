<?php

namespace App\Http\Controllers;

use PDF;
use Helper;
use App\User;
use Notification;
use Midtrans\Snap;
use App\Models\Cart;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\StatusNotification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders=Order::orderBy('id','DESC')->paginate(10);
        return view('backend.order.index')->with('orders',$orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request,[
            'first_name'=>'string|required',
            'address1'=>'string|required',
            'coupon'=>'nullable|numeric',
            'phone'=>'numeric|required',
            'post_code'=>'string|nullable',
            'email'=>'string|required'
        ]);

        if(empty(Cart::where('user_id',auth()->user()->id)->where('order_id',null)->first())){
            toast('Cart is Empty!','error');
            return back();
        }

        $order=new Order();
        $order_data=$request->all();
        $order_data['order_number']='ORD-'.strtoupper(Str::random(10));
        $order_data['user_id']=$request->user()->id;
        $order_data['sub_total']=Helper::totalCartPrice();
        $order_data['quantity']=Helper::cartCount();

        // Menghitung total amount
        $shipping=Shipping::where('id',$request->shipping)->pluck('price')->first();
        $total_amount = Helper::totalCartPrice();
        if(session('coupon')){
            $order_data['coupon']=session('coupon')['value'];
            $total_amount -= session('coupon')['value'];
        }
        $order_data['total_amount']=$total_amount + $shipping;

        $order_data['status']="new";
        $order->fill($order_data);
        $order->save();

        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

        // Proses pembayaran dengan Midtrans

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => $order_data['order_number'],
                    'gross_amount' => $order_data['total_amount'],
                ],
                'customer_details' => [
                    'first_name' => $order_data['first_name'],
                    'email' => $order_data['email'],
                    'phone' => $order_data['phone'],
                    'address' => $order_data['address1'],
                ],
            ];
            $snapToken = Snap::getSnapToken($params);
            session()->forget('cart');
            session()->forget('coupon');
            toast('Your product successfully placed in order','success');
            Log::info('Order ID yang dikirim ke Midtrans: ' . $order->id);
            return view('frontend.pages.checkout_akhir', compact('snapToken', 'order'));
    }

    public function callback(Request $request)
    {
        // Ambil server key dari konfigurasi Midtrans
        $serverKey = config('midtrans.server_key');

        // Pastikan semua data yang dibutuhkan ada
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        // Cek apakah semua data tersedia
        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::error('Callback Midtrans gagal: Data tidak lengkap', $request->all());
            return response()->json(['message' => 'Data tidak lengkap'], 400);
        }

        // Buat hash dari data callback menggunakan server key
        $hashed = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        // Log untuk melihat hashed dan signature key
        Log::info('Hashed: ' . $hashed);
        Log::info('Signature Key: ' . $signatureKey);

        // Periksa apakah hash cocok dengan signature key dari Midtrans
        if ($hashed === $signatureKey) {
            // Validasi status transaksi
            if ($request->transaction_status === 'capture' || $request->transaction_status === 'settlement') {
                // Cari order berdasarkan ID
                $order = Order::where('order_number', $orderId)->first();

                // Jika order ditemukan, update status pembayaran
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->payment_type = $request->payment_type;
                    $order->save();

                    Log::info('Pembayaran berhasil diperbarui untuk order ID: ' . $orderId);
                    return response()->json(['message' => 'Pembayaran berhasil diperbarui'], 200);
                }
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $order=Order::find($id);

        // return $order;
        return view('backend.order.show')->with('order',$order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order=Order::find($id);
        return view('backend.order.edit')->with('order',$order);
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
        $order=Order::find($id);
        $this->validate($request,[
            'status'=>'required|in:new,process,delivered,cancel'
        ]);
        $data=$request->all();
        // return $request->status;
        if($request->status=='delivered'){
            foreach($order->cart as $cart){
                $product=$cart->product;
                // return $product;
                $product->stock -=$cart->quantity;
                $product->save();
            }
        }
        $status=$order->fill($data)->save();
        if($status){
            toast('Successfully updated order','success');
        }
        else{
            toast('Error while updating order','error');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                toast('Order Successfully deleted','success');
            }
            else{
                toast('Order can not deleted','error');
            }
            return redirect()->route('order.index');
        }
        else{
            toast('Order can not found','error');
            return redirect()->back();
        }
    }

    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request){
        // return $request->all();
        $order=Order::where('user_id',auth()->user()->id)->where('order_number',$request->order_number)->first();
        if($order){
            if($order->status=="new"){
            toast('Your order has been placed. please wait.','success');
            return redirect()->route('home');

            }
            elseif($order->status=="process"){
                toast('Your order is under processing please wait.','success');
                return redirect()->route('home');

            }
            elseif($order->status=="delivered"){
                toast('Your order is successfully delivered.','success');
                return redirect()->route('home');

            }
            else{
                toast('Your order canceled. please try again','error');
                return redirect()->route('home');

            }
        }
        else{
            toast('Invalid order numer please try again','error');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request){
        $order=Order::getAllOrder($request->id);
        // return $order;
        $file_name=$order->order_number.'-'.$order->first_name.'.pdf';
        // return $file_name;
        $pdf=PDF::loadview('backend.order.pdf',compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request){
        $year=\Carbon\Carbon::now()->year;
        // dd($year);
        $items=Order::with(['cart_info'])->whereYear('created_at',$year)->where('status','delivered')->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
            // dd($items);
        $result=[];
        foreach($items as $month=>$item_collections){
            foreach($item_collections as $item){
                $amount=$item->cart_info->sum('amount');
                // dd($amount);
                $m=intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount :$result[$m]=$amount;
            }
        }
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
}
