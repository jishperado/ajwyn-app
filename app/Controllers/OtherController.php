<?php

namespace App\Controllers;

use App\Controllers\BaseStaff;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\OtherModel;
use App\Models\AddressModel;
use App\Models\OrderModel;
use App\Models\MediaModel;
use CodeIgniter\Config\Factories;
use  App\Models\PaymentStatusModel;
use App\Libraries\PaymentGate;
use App\Services\EmailNotificationService;
use App\Models\CusModel;

class OtherController extends BaseStaff
{

    /**
     * Helper: check if current user is a vendor
     */
    private function isVendor(): bool
    {
        return ($this->data['user_role'] ?? 'admin') === 'vendor';
    }

    /**
     * Helper: apply vendor filter to order query
     */
    private function applyVendorFilter($query)
    {
        if ($this->isVendor()) {
            $query = $query->where('pc.vendor_id', $this->user_id);
        }
        return $query;
    }

    public function policy($id)  {
         $data['main'] = 17;

        // Vendors cannot access policy management
        if ($this->isVendor()) {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $other = Factories::models('OtherModel');
        if($id == 1){
              $data['sub'] = 18;
        }elseif($id == 2){
              $data['sub'] = 20;
        }elseif($id == 3){
               $data['sub'] = 19;
        }
 if ($_POST) {
    $content = $this->request->getPost('content');

    $updated = $other->update($id, ['content' => $content]);

    if ($updated) {
        $this->session->setFlashdata('success', 'Successfully updated.');
    } else {
        $this->session->setFlashdata('error', 'Update failed. Please try again.');
    }

    return redirect()->back();
}


        $this->data['result'] = $other->where('id', $id)->first();
         return view('other/policy',$this->data + $data );

    }

public function purchased_products() {
    $data['main'] = 21;
    $data['sub'] = 22;

    $query = model('OrderModel')->get_data()->where('ord_status', 'P');
    $query = $this->applyVendorFilter($query);
    $orders = $query->orderby('ord_date', 'DESC')->findAll();

    $groupedOrders = [];
    foreach ($orders as $item) {
        $oid = $item->order_id;

        if (!isset($groupedOrders[$oid])) {
            $groupedOrders[$oid] = [
                'order_id' => $oid,
                'created_date' => date('Y-m-d', strtotime($item->ord_date)),
                'customer_name' => $item->name,
                'address' => $item->address,
                'city' => $item->city,
                'pincode' => $item->pincode,
                'landmark' => $item->landmark,
                'items' => [],
            ];
        }

        $groupedOrders[$oid]['items'][] = $item;
    }

    $data['groupedOrders'] = $groupedOrders;

    return view('other/purchased_product', $data + $this->data);
}

public function ship($order_id)
{
    // Vendor security: check if this order belongs to their product
    if ($this->isVendor()) {
        $check = model('OrderModel')->get_data_by()
            ->join('product p2', 'product_veriant.product_id = p2.id')
            ->where('tbl_order.order_id', $order_id)
            ->where('p2.vendor_id', $this->user_id)
            ->findAll();
        if (empty($check)) {
            return redirect()->back()->with('error', 'Access denied!');
        }
    }

    $tracking_id = $this->request->getPost('tracking_id') ?: null;
    $tracking_url = $this->request->getPost('tracking_url') ?: null;
    $data = [
        'track_id' => $tracking_id,
        'track_url' => $tracking_url,
        'ord_status' => 'S'
    ];
    $orderModel = model('OrderModel');
    $orderModel->where('order_id', $order_id)->set($data)->update();

    // Send email notifications for shipped order
    $notifier = new EmailNotificationService();
    $notifier->orderShippedAdmin($order_id, $tracking_id);
    $notifier->orderShippedVendors($order_id, $tracking_id);

    // Get customer details and send shipped email
    $orderItems = $orderModel->get_data_by()
        ->join('tbl_cus', 'tbl_order.cus_id = tbl_cus.id')
        ->where('tbl_order.order_id', $order_id)
        ->findAll();
    if (!empty($orderItems)) {
        $cusEmail = $orderItems[0]->email ?? '';
        $cusName = $orderItems[0]->name ?? 'Customer';
        if (!empty($cusEmail)) {
            $notifier->orderShippedCustomer($cusEmail, $cusName, $order_id, $tracking_id, $tracking_url);
        }
    }

    return redirect()->back()->with('success', 'Order #'.$order_id.' marked as shipped.');
}
public function shipped_products()
{
    $data['main'] = 21;
    $data['sub'] = 25;

    $query = model('OrderModel')->get_data()->where('ord_status', 'S');
    $query = $this->applyVendorFilter($query);
    $orders = $query->orderby('ord_date', 'DESC')->findAll();

    $groupedOrders = [];
    foreach ($orders as $item) {
        $oid = $item->order_id;

        if (!isset($groupedOrders[$oid])) {
            $groupedOrders[$oid] = [
                'order_id' => $oid,
                'customer_name' => $item->name,
                 'created_date' => date('Y-m-d', strtotime($item->ord_date)),
                'address' => $item->address,
                'city' => $item->city,
                'pincode' => $item->pincode,
                'landmark' => $item->landmark,
                'track_id' => $item->track_id ?? null,
                'track_url' => $item->track_url ?? null,
                'items' => [],
            ];
        }

        $groupedOrders[$oid]['items'][] = $item;
    }

    $data['groupedOrders'] = $groupedOrders;

    return view('other/shipped_products', $data + $this->data);
}

public function deliver($order_id)
{
    // Vendor security: check if this order belongs to their product
    if ($this->isVendor()) {
        $check = model('OrderModel')->get_data_by()
            ->join('product p2', 'product_veriant.product_id = p2.id')
            ->where('tbl_order.order_id', $order_id)
            ->where('p2.vendor_id', $this->user_id)
            ->findAll();
        if (empty($check)) {
            return redirect()->back()->with('error', 'Access denied!');
        }
    }

    $orderModel = model('OrderModel');
    $orderModel->where('order_id', $order_id)->set(['ord_status' => 'D'])->update();

    // Send email notifications for delivered order
    $notifier = new EmailNotificationService();
    $notifier->orderDeliveredAdmin($order_id);
    $notifier->orderDeliveredVendors($order_id);

    // Get customer details and send delivered email
    $orderItems = $orderModel->get_data_by()
        ->join('tbl_cus', 'tbl_order.cus_id = tbl_cus.id')
        ->where('tbl_order.order_id', $order_id)
        ->findAll();
    if (!empty($orderItems)) {
        $cusEmail = $orderItems[0]->email ?? '';
        $cusName = $orderItems[0]->name ?? 'Customer';
        if (!empty($cusEmail)) {
            $notifier->orderDeliveredCustomer($cusEmail, $cusName, $order_id);
        }
    }

    return redirect()->back()->with('success', 'Order #'.$order_id.' marked as delivered.');
}

public function delivered_products()
{
    $data['main'] = 21;
    $data['sub'] = 23;

    $query = model('OrderModel')->get_data()->where('ord_status', 'D');
    $query = $this->applyVendorFilter($query);
    $orders = $query->orderby('ord_date', 'DESC')->findAll();

    $groupedOrders = [];
    foreach ($orders as $item) {
        $oid = $item->order_id;

        if (!isset($groupedOrders[$oid])) {
            $groupedOrders[$oid] = [
                'order_id' => $oid,
                'customer_name' => $item->name,
                'address' => $item->address,
                'created_date' => date('Y-m-d', strtotime($item->ord_date)),
                'city' => $item->city,
                'pincode' => $item->pincode,
                'landmark' => $item->landmark,
                'track_id' => $item->track_id ?? null,
                'track_url' => $item->track_url ?? null,
                'items' => [],
            ];
        }

        $groupedOrders[$oid]['items'][] = $item;
    }

    $data['groupedOrders'] = $groupedOrders;

    return view('other/delivered_product', $data + $this->data);
}
    public function invoice($id){
    $social = new MediaModel();
    $data['social']  = $social->first();


    $car = new OrderModel;
    $this->data['orderdata'] = $orderdata = $car->get_data_by()->where('status', 'Y')->where('order_id', $id)->findAll();


    $address = new AddressModel;
    $this->data['address'] = $address = $address->select('tbl_cus_add.*,countries.name as country_name,states.name as state_name,cities.name as city_name')->where('tbl_cus_add.id', $orderdata[0]->add_id)->where('cus_id', $orderdata[0]->cus_id)->country()->state()->city()->first();



        return view('web/invoice', $this->data );
    }

public function cancelled_products()
{
    $data['main'] = 21;
    $data['sub'] = 5;

    $query = model('OrderModel')->get_data()->where('ord_status', 'C');
    $query = $this->applyVendorFilter($query);
    $orders = $query->orderby('ord_date', 'DESC')->findAll();


    $groupedOrders = [];
    foreach ($orders as $item) {
        $oid = $item->order_id;

        if (!isset($groupedOrders[$oid])) {
            $groupedOrders[$oid] = [
                'order_id' => $oid,
                'customer_name' => $item->name,
                'address' => $item->address,
                 'created_date' => date('Y-m-d', strtotime($item->ord_date)),
                'city' => $item->city,
                'pincode' => $item->pincode,
                'landmark' => $item->landmark,
                'track_id' => $item->track_id ?? null,
                'track_url' => $item->track_url ?? null,
                'items' => [],
            ];
        }

        $groupedOrders[$oid]['items'][] = $item;
    }

    $data['groupedOrders'] = $groupedOrders;

    return view('other/cancelled_product', $data + $this->data);
}
function pending_orders(){
    $data['main'] = 21;
    $data['sub'] = 29;

    // Vendors should not access pending payment orders
    if ($this->isVendor()) {
        return redirect()->to(base_url('users/user-dashboard'));
    }

    $orders = model('OrderModel')->select('tbl_order.*, tbl_cus.name,tbl_cus.mobile ')->where('ord_status', 'N')->customer()->orderby('tbl_order.created_date', 'DESC')->findAll();
    $data['orders'] = $orders;

    return view('other/pending_product', $data + $this->data);
}
function order_status($order_id){
    $data['main'] = 21;
    $data['sub'] = 29;

    // Vendors should not access payment status
    if ($this->isVendor()) {
        return redirect()->to(base_url('users/user-dashboard'));
    }

    $paymentModel = new PaymentGate();
    $order = model('OrderModel')->find($order_id);
    $payment = (new PaymentStatusModel())->find($order->payment_id);

     $res=  $paymentModel->paymentStatus($payment);
              $json = json_decode($res);

              if(!empty($json->error))
            {
               (new PaymentStatusModel())->update($payment->id, ['sts' => 'F','q_status' => 0, "response"=>json_encode($json->error),"updated_at"=>date("Y-m-d H:i:s")]);
               $this->session->setFlashdata('error', $json->error->message);
               return redirect()->back();

            }else if(!empty($json->data))
            {

              $paymentModel->paymentSave((array)$json->data[0]);
              $this->session->setFlashdata('success', 'Order #'.$order_id.' marked as success.');
              return redirect()->back();

            }



}

}
