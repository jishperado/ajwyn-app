<?php
namespace App\Libraries;
use App\Models\PaymentStatusModel;
use App\Models\CusModel;
use App\Models\OrderModel;
class PaymentGate
{
   function hashCalculate($salt,$input){
	/* Columns used for hash calculation, Donot add or remove values from $hash_columns array */
	$hash_columns = ['address_line_1', 'address_line_2', 'amount', 'api_key', 'city', 'country', 'currency', 'description', 'email', 'mode', 'name', 'order_id', 'phone', 'return_url', 'state','split_enforce_strict','split_info', 'udf1', 'udf2', 
	'udf3', 'udf4', 'udf5', 'zip_code',];
	/*Sort the array before hashing*/
	sort($hash_columns);

	/*Create a | (pipe) separated string of all the $input values which are available in $hash_columns*/
	$hash_data = $salt;
	foreach ($hash_columns as $column) {
		if (isset($input[$column])) {
			if (strlen($input[$column]) > 0) {
				$hash_data .= '|' . trim($input[$column]);
			}
		}
	}
	
	$hash = strtoupper(hash("sha512", $hash_data));
	
	return $hash;
}
function saveData($post){
    $payment = new PaymentStatusModel();
	  $cus_id = session()->get('user_id');
    $payment->insert([
		'customer_id' => $cus_id,
		'amount' => $post['amount'],
		'tnx_id' => $post['order_id'],
		'sts' => 'P',
		'q_status' => 1,
		'date' => date('Y-m-d'),
		
	]);
    return $payment->insertID();
}
function paymentSave($post){	
	$payment = new PaymentStatusModel();
	$paymentDetails = $payment->find($post['udf2']);
	
	if($paymentDetails->sts == 'S'){
	return 0;
	}
	$data_val = [
		
		'sts' => $post['response_code'] == 0 ? 'S' : ($post['response_code'] == 1000 ? 'F' : 'P'),
		'q_status' => $post['response_code'] == 0 || $post['response_code'] == 1000 ? 0 : 1,
		'response'=>json_encode($post),
		
	];
	
	$payment->update($paymentDetails->id, $data_val);
	if($post['response_code'] == 0){
		
		(new OrderModel())->where('payment_id', $paymentDetails->id)->set(['status' => 'Y','ord_status' => 'P'])->update();
		
	}
	return $paymentDetails->customer_id;
   
}
public function paymentStatus($payment)
{
   
$url = "https://pgbiz.omniware.in/v2/paymentstatus";

$data = [
"api_key"   => getenv('api_key'),
"order_id"  => $payment->tnx_id
];
$data['hash'] = $this->hashCalculate(getenv('salt'), $data);
// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Convert array to URL-encoded query string
curl_setopt($ch, CURLOPT_HTTPHEADER, [
"Content-Type: application/x-www-form-urlencoded"
]);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
//echo "cURL Error: " . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Output response
return $response;

}
}