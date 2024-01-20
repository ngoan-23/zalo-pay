<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function getForm()
    {
        return view('form');
    }

    public function payment(Request $request)
    {
        $config = [
            "appid" => 2554,
            "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
            'reqtime' => Carbon::now()->timestamp * 1000,
            "endpoint" => "https://sandbox.zalopay.com.vn/v001/tpe/createorder",
        ];
        $items = [];

        $embeddata = [
            "promotioninfo" => "",
            "merchantinfo" => "embeddata123",
            "preferred_payment_method" => "",
            "redirecturl" => "https://demo-zalo-pay.dev.com/zalo/payment/callback",
        ];
        $order = [
            "appid" => 2554,
            "apptime" => Carbon::now()->timestamp * 1000, // milliseconds
            "apptransid" => date("ymd") . "_" . uniqid(), // mã giao dịch có định dạng yyMMdd_xxxx
            "appuser" => "demo",
            "item" => json_encode($items, JSON_UNESCAPED_UNICODE),
            "embeddata" => json_encode($embeddata, JSON_UNESCAPED_UNICODE), 
            "bankcode" => "",
            "description" => $request->description,
            "amount" =>  $request->amount,
        ];

        $hmacInput = $order["appid"]."|".$order["apptransid"]."|".$order["appuser"]."|".$order["amount"]
            ."|".$order["apptime"]."|".$order["embeddata"]."|".$order["item"];

        // Tính toán giá trị mac
        $mac = hash_hmac('sha256', $hmacInput, $config["key1"]);
        $order["mac"] = $mac;
        
        $queryString = http_build_query($order);
        $url = $config["endpoint"] . "?" . $queryString;

        $response = Http::post($url);

        // Lấy dữ liệu JSON từ phản hồi
        $data = $response->json();

        // Lấy giá trị orderurl từ dữ liệu
        $orderUrl = $data['orderurl'];
        // redirect orderUrl
        return redirect()->away($orderUrl);
    }

    public function callback(Request $request) {

        //status == 1 => trạng thái thanh toán thành công tiến hành xử lý đơn hàng
        $key2 = "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf";
        $data = $request->all();

        $checksumData = $data["appid"] ."|". $data["apptransid"] ."|". $data["pmcid"] ."|". $data["bankcode"] ."|". $data["amount"] ."|". $data["discountamount"] ."|". $data["status"];
        $checksum = hash_hmac("sha256", $checksumData, $key2);
        
        if (strcmp($data["checksum"], $checksum) != 0) {
            return response()->json(['message' => 'Bad Request'], 400);
        } else {
            // kiểm tra xem đã nhận được callback hay chưa, nếu chưa thì tiến hành gọi API truy vấn trạng thái thanh toán của đơn hàng để lấy kết quả cuối cùng
            return view('form');
        }
    }
}