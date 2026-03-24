<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CartModel;
use  App\Models\PaymentStatusModel;
use App\Libraries\PaymentGate;
use App\Models\CusModel;
use App\Services\EmailNotificationService;

class CronContoller extends BaseController
{
    public function payment()
    {
        $post = $this->request->getPost();
        $payment = new PaymentGate();
       $cus_id = $payment->paymentSave($post);  
        
      
      
       if($cus_id == 0){
        $this->session->setFlashdata('error', 'Something went wrong'); 
          return redirect()->to("/");
       }
       session()->set('user_id', $cus_id);
     
       if($post['response_code'] == 0){
      ( 
        new CartModel())->where('cus_id', $cus_id)->delete();
       $email = (new CusModel())->find($cus_id)['email'] ?? '';
        $otpService = service('smsService');
        $otpService->order($post['phone'],$post['order_id'],$post['name']);
        if(!empty($email)){
            $this->sendMail($email,$post);
        }
        // Notify admin and vendors about new order
        $notifier = new EmailNotificationService();
        $orderDate = date('Y-m-d h:i A', strtotime($post['payment_datetime'] ?? 'now'));
        $notifier->orderPlacedAdmin($post['order_id'], $post['name'], $post['amount'], $orderDate);
        $notifier->orderPlacedVendors($post['order_id'], $post['name'], $post['amount'], $orderDate);

        $this->session->setFlashdata('success', 'Order Placed Successfully');  
       }else if($post['response_code'] == 1000){
        
        $this->session->setFlashdata('error', 'Order Placed Failed'); 
       } else{
        $this->session->setFlashdata('error', 'Your payment is pending.please wait some time.'); 
       }

        return redirect()->to("/order");
       }
        public function sendMail($emailId,$post)
{
   $body = '<div style="font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto; padding: 20px; background: #ffffff; border-radius: 10px; border: 1px solid #eee;">

    <h2 style="color: #0B61D6; margin-top: 0;">Order Confirmed</h2>

    <p style="font-size: 15px; color: #333;">
        Hi '.$post['name'].',
    </p>

    <p style="font-size: 15px; color: #333;">
        Thank you for your order! Your order has been <strong>successfully confirmed</strong>.
    </p>

    <div style="background: #f8f8f8; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
        <p style="margin: 0; font-size: 14px; color: #555;">
            <strong>Order ID:</strong> '.$post['order_id'].'<br>
            <strong>Date:</strong> '.date('Y-m-d h:i A',strtotime($post['payment_datetime'])).'<br>
            <strong>Total Amount:</strong> ₹'.$post['amount'].'
        </p>
    </div>

    <p style="font-size: 14px; color: #555;">
        We will notify you once your order is shipped.
    </p>

    <p style="font-size: 14px; color: #333;">
        Regards,<br>
        <strong>'.getenv('APP_NAME').'</strong>
    </p>

</div>';

    helper('smtp');
    $appName = getenv('APP_NAME') ?: 'AJWYN';
    if (smtp_send($emailId, 'Your order is confirmed! - ' . $appName, $body)) {
        return "Email sent successfully!";
    } else {
        return "Email sending failed";
    }
}
       public function paymentstatus(){
          $paymentModel = new PaymentGate();
         $pendingPayment = (new PaymentStatusModel())->where('q_status', 1)
         ->where('sts', 'P')
         ->groupStart()
         ->where('TIMESTAMPDIFF(MINUTE, created_at, NOW()) >', 5)
         ->orWhere('created_at', "0000-00-00 00:00:00")
         ->groupEnd()
         ->findAll();

         if(!empty($pendingPayment)){
          // Extract IDs safely (works with both objects and arrays)
          $ids = [];
          foreach ($pendingPayment as $p) {
              $ids[] = is_object($p) ? $p->id : $p['id'];
          }
          if (!empty($ids)) {
              (new PaymentStatusModel())->whereIn('id', $ids)->set(['q_status' => 2])->update();
          }
            foreach ($pendingPayment as $payment) {
              try {
                  $res = $paymentModel->paymentStatus($payment);
                  $json = json_decode($res);
                  $paymentId = is_object($payment) ? $payment->id : $payment['id'];
                  if(!empty($json->error))
                  {
                     $errorData = is_string($json->error) ? $json->error : json_encode($json->error);
                     (new PaymentStatusModel())->update($paymentId, ['sts' => 'F','q_status' => 0, "response" => $errorData, "updated_at" => date("Y-m-d H:i:s")]);
                  }else if(!empty($json->data))
                  {
                    $paymentModel->paymentSave((array)$json->data[0]);
                  }
              } catch (\Throwable $e) {
                  log_message('error', 'Payment status check failed for payment: ' . ($paymentId ?? 'unknown') . ' - ' . $e->getMessage());
              }
          }
         }
       }
       
    
}
