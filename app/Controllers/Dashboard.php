<?php

namespace App\Controllers;

use App\Controllers\BaseStaff;
use App\Models\CusModel;

class Dashboard extends BaseStaff
{
    public function index()
    {
        $this->data['main'] = 0;
        $this->data['sub'] = 0;

        $role = $this->data['user_role'] ?? 'admin';
        $db = \Config\Database::connect();

        if ($role === 'vendor') {
            $this->data['product_count'] = $db->table('product')->where('vendor_id', $this->user_id)->countAllResults();
            $this->data['order_pending'] = $db->table('tbl_order')
                ->join('product_veriant', 'tbl_order.variant = product_veriant.id')
                ->join('product', 'product_veriant.product_id = product.id')
                ->where('product.vendor_id', $this->user_id)
                ->where('ord_status', 'P')
                ->countAllResults();
            $this->data['order_shipped'] = $db->table('tbl_order')
                ->join('product_veriant', 'tbl_order.variant = product_veriant.id')
                ->join('product', 'product_veriant.product_id = product.id')
                ->where('product.vendor_id', $this->user_id)
                ->where('ord_status', 'S')
                ->countAllResults();
            $this->data['order_delivered'] = $db->table('tbl_order')
                ->join('product_veriant', 'tbl_order.variant = product_veriant.id')
                ->join('product', 'product_veriant.product_id = product.id')
                ->where('product.vendor_id', $this->user_id)
                ->where('ord_status', 'D')
                ->countAllResults();
        } else {
            $this->data['product_count'] = $db->table('product')->countAllResults();
            $this->data['vendor_count'] = $db->table('admin_log')->where('role', 'vendor')->countAllResults();
            $this->data['order_pending'] = $db->table('tbl_order')->where('ord_status', 'P')->countAllResults();
            $this->data['order_shipped'] = $db->table('tbl_order')->where('ord_status', 'S')->countAllResults();
            $this->data['order_delivered'] = $db->table('tbl_order')->where('ord_status', 'D')->countAllResults();
            $this->data['order_cancelled'] = $db->table('tbl_order')->where('ord_status', 'C')->countAllResults();
            $this->data['customer_count'] = $db->table('tbl_cus')->countAllResults();

            // Monthly revenue data for last 6 months (line chart)
            $monthlyData = [];
            for ($i = 5; $i >= 0; $i--) {
                $monthStart = date('Y-m-01', strtotime("-$i months"));
                $monthEnd = date('Y-m-t', strtotime("-$i months"));
                $label = date('M Y', strtotime("-$i months"));

                $revenue = $db->table('tbl_order')
                    ->selectSum('amount')
                    ->where('status', 'Y')
                    ->where('ord_status !=', 'C')
                    ->where('created_date >=', $monthStart)
                    ->where('created_date <=', $monthEnd . ' 23:59:59')
                    ->get()->getRow()->amount ?? 0;

                $orderCount = $db->table('tbl_order')
                    ->where('status', 'Y')
                    ->where('ord_status !=', 'C')
                    ->where('created_date >=', $monthStart)
                    ->where('created_date <=', $monthEnd . ' 23:59:59')
                    ->countAllResults();

                $monthlyData[] = [
                    'label' => $label,
                    'revenue' => (float)$revenue,
                    'orders' => $orderCount,
                ];
            }
            $this->data['monthly_data'] = json_encode($monthlyData);
        }

        return view('admin/dash',$this->data);
    }



    // =========================================================
    //  CUSTOMERS PAGE
    // =========================================================
    public function customers()
    {
        if (($this->data['user_role'] ?? 'admin') !== 'admin') {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $this->data['main'] = 50;
        $this->data['sub'] = 0;
        $this->data['title'] = 'Customers';

        $db = \Config\Database::connect();
        $this->data['customers'] = $db->table('tbl_cus')
            ->select('id, name, email, mobile, is_active, created_at')
            ->orderBy('id', 'DESC')
            ->get()->getResult();

        return view('admin/customers', $this->data);
    }

    // =========================================================
    //  SEND PROMOTIONAL EMAIL
    // =========================================================
    public function sendPromoEmail()
    {
        if (($this->data['user_role'] ?? 'admin') !== 'admin') {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $sendTo = $this->request->getPost('send_to'); // 'all' or 'selected'
        $selectedIds = $this->request->getPost('customer_ids') ?? [];

        if (empty($subject) || empty($message)) {
            return redirect()->back()->with('error', 'Subject and message are required!');
        }

        $db = \Config\Database::connect();

        if ($sendTo === 'all') {
            $customers = $db->table('tbl_cus')
                ->select('email, name')
                ->where('email !=', '')
                ->get()->getResult();
        } else {
            if (empty($selectedIds)) {
                return redirect()->back()->with('error', 'Please select at least one customer!');
            }
            $customers = $db->table('tbl_cus')
                ->select('email, name')
                ->whereIn('id', $selectedIds)
                ->where('email !=', '')
                ->get()->getResult();
        }

        $appName = getenv('APP_NAME') ?: 'AJWYN';
        $sent = 0;
        $failed = 0;
        $lastDebug = '';

        foreach ($customers as $cus) {
            if (empty($cus->email) || !filter_var($cus->email, FILTER_VALIDATE_EMAIL)) continue;

            $body = '<div style="font-family: Arial, sans-serif; max-width: 520px; margin: 0 auto; padding: 20px; background: #ffffff; border-radius: 10px; border: 1px solid #eee;">'
                . '<h2 style="color: #0B61D6; margin-top: 0;">' . htmlspecialchars($subject) . '</h2>'
                . '<p style="font-size: 15px; color: #333;">Hi ' . htmlspecialchars($cus->name ?: 'Customer') . ',</p>'
                . '<div style="font-size: 15px; color: #333; line-height: 1.6;">' . nl2br(htmlspecialchars($message)) . '</div>'
                . '<hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">'
                . '<p style="font-size: 12px; color: #999; text-align: center;">' . $appName . ' &bull; <a href="https://www.ajwyn.site" style="color: #0B61D6;">www.ajwyn.site</a></p>'
                . '</div>';

            helper('smtp');
            if (smtp_send($cus->email, $subject . ' - ' . $appName, $body)) {
                $sent++;
            } else {
                $failed++;
            }
        }

        $total = $sent + $failed;
        if ($sent > 0 && $failed === 0) {
            $resultMsg = "Promotional email sent successfully to {$sent} customer" . ($sent > 1 ? 's' : '') . "!";
        } elseif ($sent > 0) {
            $resultMsg = "Email sent to {$sent} of {$total} customers ({$failed} failed).";
        } else {
            $resultMsg = "Failed to send emails. Please try again later.";
        }

        return redirect()->back()->with($sent > 0 ? 'success' : 'error', $resultMsg);
    }

    /**
     * Send email via direct SMTP with debug output
     */
    private function sendSmtpEmail(string $to, string $subject, string $htmlBody, string &$debugOut = ''): bool
    {
        $host     = 'mail.ajwyn.site';
        $port     = 587;
        $username = 'info@ajwyn.site';
        $password = 'Ajwyn@2026';
        $from     = 'info@ajwyn.site';
        $fromName = 'AJWYN';
        $d = [];

        try {
            $d[] = 'CONN';

            // Use stream_socket_client with SSL context to skip certificate verification
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                    'crypto_method'     => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                ]
            ]);

            $socket = @stream_socket_client(
                "tcp://{$host}:{$port}",
                $errno,
                $errstr,
                30,
                STREAM_CLIENT_CONNECT,
                $context
            );
            if (!$socket) {
                $debugOut = "CONN-FAIL:$errstr($errno)";
                return false;
            }
            $resp = $this->smtpRead($socket);
            $d[] = 'OK';

            $this->smtpCmd($socket, "EHLO ajwyn.site");
            $d[] = 'EHLO';

            $resp = $this->smtpCmd($socket, "STARTTLS");
            $d[] = 'STLS:' . substr(trim($resp), 0, 20);

            $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
            if (!$crypto) {
                $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            }
            if (!$crypto) {
                // Last resort: try ANY TLS method
                $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);
            }
            if (!$crypto) {
                $debugOut = implode('>', $d) . '>TLS-FAIL';
                fclose($socket);
                return false;
            }
            $d[] = 'TLS';

            $this->smtpCmd($socket, "EHLO ajwyn.site");

            $this->smtpCmd($socket, "AUTH LOGIN");
            $this->smtpCmd($socket, base64_encode($username));
            $resp = $this->smtpCmd($socket, base64_encode($password));
            if (strpos($resp, '235') === false) {
                $debugOut = implode('>', $d) . '>AUTH-FAIL:' . substr(trim($resp), 0, 40);
                fclose($socket);
                return false;
            }
            $d[] = 'AUTH';

            $resp = $this->smtpCmd($socket, "MAIL FROM:<{$from}>");
            $d[] = 'FROM:' . substr(trim($resp), 0, 10);

            $resp = $this->smtpCmd($socket, "RCPT TO:<{$to}>");
            $d[] = 'RCPT:' . substr(trim($resp), 0, 10);

            $this->smtpCmd($socket, "DATA");

            // Generate unique Message-ID and boundary
            $messageId = '<' . uniqid('ajwyn_', true) . '@ajwyn.site>';
            $boundary = 'AJWYN_' . md5(uniqid());
            $date = date('r'); // RFC 2822 date

            // Plain text version (strip HTML)
            $plainText = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>', '</h2>'], "\n", $htmlBody));
            $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');
            $plainText = trim(preg_replace('/\n{3,}/', "\n\n", $plainText));

            $msg  = "Date: {$date}\r\n";
            $msg .= "From: {$fromName} <{$from}>\r\n";
            $msg .= "Reply-To: {$from}\r\n";
            $msg .= "To: {$to}\r\n";
            $msg .= "Subject: {$subject}\r\n";
            $msg .= "Message-ID: {$messageId}\r\n";
            $msg .= "X-Mailer: AJWYN-Mailer/1.0\r\n";
            $msg .= "List-Unsubscribe: <mailto:{$from}?subject=Unsubscribe>\r\n";
            $msg .= "MIME-Version: 1.0\r\n";
            $msg .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
            $msg .= "\r\n";
            // Plain text part
            $msg .= "--{$boundary}\r\n";
            $msg .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $msg .= "Content-Transfer-Encoding: quoted-printable\r\n";
            $msg .= "\r\n";
            $msg .= $plainText . "\r\n";
            $msg .= "\r\n";
            // HTML part
            $msg .= "--{$boundary}\r\n";
            $msg .= "Content-Type: text/html; charset=UTF-8\r\n";
            $msg .= "Content-Transfer-Encoding: quoted-printable\r\n";
            $msg .= "\r\n";
            $msg .= $htmlBody . "\r\n";
            $msg .= "\r\n";
            $msg .= "--{$boundary}--\r\n";
            $msg .= ".\r\n";

            fwrite($socket, $msg);
            $resp = $this->smtpRead($socket);
            $d[] = 'DATA:' . substr(trim($resp), 0, 20);

            $this->smtpCmd($socket, "QUIT");
            fclose($socket);

            $success = (strpos($resp, '250') !== false);
            $d[] = $success ? 'OK!' : 'FAIL';
            $debugOut = implode('>', $d);
            return $success;

        } catch (\Throwable $e) {
            $debugOut = implode('>', $d) . '>EX:' . $e->getMessage();
            return false;
        }
    }

    private function smtpCmd($socket, string $cmd): string
    {
        fwrite($socket, $cmd . "\r\n");
        return $this->smtpRead($socket);
    }

    private function smtpRead($socket): string
    {
        $data = '';
        while ($line = @fgets($socket, 515)) {
            $data .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        return $data;
    }

    /**
     * Test email route - DELETE AFTER DEBUGGING
     */
    // =========================================================
    //  SALES REPORT PAGE
    // =========================================================
    public function salesReport()
    {
        if (($this->data['user_role'] ?? 'admin') !== 'admin') {
            return redirect()->to(base_url('users/user-dashboard'));
        }

        $this->data['main'] = 51;
        $this->data['sub'] = 0;
        $this->data['title'] = 'Sales Report';

        $db = \Config\Database::connect();

        $dateFrom = $this->request->getGet('from') ?: date('Y-m-01');
        $dateTo = $this->request->getGet('to') ?: date('Y-m-d');

        $this->data['date_from'] = $dateFrom;
        $this->data['date_to'] = $dateTo;

        // Summary stats
        $baseQuery = function() use ($db, $dateFrom, $dateTo) {
            return $db->table('tbl_order')
                ->where('status', 'Y')
                ->where('created_date >=', $dateFrom)
                ->where('created_date <=', $dateTo . ' 23:59:59');
        };

        $this->data['total_orders'] = $baseQuery()->countAllResults();
        $this->data['total_revenue'] = $baseQuery()->selectSum('amount')->get()->getRow()->amount ?? 0;
        $this->data['report_pending'] = $baseQuery()->where('ord_status', 'P')->countAllResults();
        $this->data['report_shipped'] = $baseQuery()->where('ord_status', 'S')->countAllResults();
        $this->data['report_delivered'] = $baseQuery()->where('ord_status', 'D')->countAllResults();
        $this->data['report_cancelled'] = $baseQuery()->where('ord_status', 'C')->countAllResults();

        // Order list
        $this->data['orders'] = $db->table('tbl_order')
            ->select('tbl_order.order_id, tbl_order.amount, tbl_order.ord_status, tbl_order.created_date, tbl_cus.name as customer_name, tbl_cus.mobile as customer_mobile')
            ->join('tbl_cus', 'tbl_order.cus_id = tbl_cus.id', 'left')
            ->where('tbl_order.status', 'Y')
            ->where('tbl_order.created_date >=', $dateFrom)
            ->where('tbl_order.created_date <=', $dateTo . ' 23:59:59')
            ->groupBy('tbl_order.order_id')
            ->orderBy('tbl_order.created_date', 'DESC')
            ->get()->getResult();

        return view('admin/sales_report', $this->data);
    }

    public function profile()
    {
        $this->data['main'] = 1;
        $this->data['sub'] = 1;
        if ($_POST) {
            $this->validation->setRule('name', 'Name', 'required');
            $this->validation->setRule('pwd', 'Password', 'required');

            if ( $this->validation->withRequest($this->request)->run() == TRUE) {
               
                $name = $this->request->getPost('name');
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('pwd');
                $password = $this->om->encrypt($password);
                $this->session->setFlashdata("success","Data Saved");
                $this->om->update_data("admin_log",["id"=>$this->user_id],["name"=>$name,"username"=>$username,"password"=>$password]);
                return redirect()->to(base_url() . 'users/user-profile');
                 
                } 
             else {
                $data['errors'] = $this->validation->getErrors();
            }
        }
        
        return view('admin/profile',$this->data);
    }


    public function banner()
    {
        $this->data['main'] = 2;
        $this->data['sub'] = 3;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_banner",[],["order"=>["id","desc"]]);
        return view('admin/banner_list',$this->data);
    }
    public function mid_banner()
    {
        $this->data['main'] = 2;
        $this->data['sub'] = 26;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_midbanner",[],["order"=>["id","desc"]]);
        return view('admin/mid_banner_list',$this->data);
    }
    public function other_banner()
    {
        $this->data['main'] = 2;
        $this->data['sub'] = 27;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_otherbanner",[],["order"=>["id","desc"]]);
        return view('admin/other_banner_list',$this->data);
    }




    function banner_add()
    {
        $this->data['main'] = 2;
        $this->data['sub'] = 3;
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('status', 'Status', 'required');
         
            
          $file = $this->request->getFile('photo');
$file2 = $this->request->getFile('mobile_photo');

if (empty($file->getName())) {
    $this->validation->setRule('photo', 'Banner', 'required');
}
if (empty($file2->getName())) {
    $this->validation->setRule('mobile_photo', 'Mobile Banner', 'required');
}

if ($this->validation->withRequest($this->request)->run()) {

    $filename = $file->getRandomName();
    $PATH = getcwd();
    $file->move($PATH . '/uploads/banner/', $filename);

    $filename2 = $file2->getRandomName();
    $file2->move($PATH . '/uploads/banner/', $filename2);

    $data_val = [
        "title"          => strip_tags($this->request->getPost('title')),
        "banner_title"   => strip_tags($this->request->getPost('banner_title')),
        "status"         => strip_tags($this->request->getPost('status')),
        "desk_banner"    => $filename,
        "mobile_banner"  => $filename2
    ];
    
    $this->om->insert_data('tbl_banner', $data_val);

    $this->session->setFlashdata('success', 'Data Saved successfully!!');
    return redirect()->to(base_url() . '/users/create-banner');
}
 else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
      
        return view('admin/banner_add',$this->data);
    }

     function mid_banner_add()
    {
        $this->data['main'] = 2;
        $this->data['sub'] = 26;
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('status', 'Status', 'required');
         
            
            $file = $this->request->getFile('photo');

         


            if(empty($file->getName())) {
                $this->validation->setRule('photo', 'Banner', 'required');
            }
            
            else{
                $validated = $this->validate([
                    'photo' => [
                        'uploaded[photo]',
                        'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png]',
                       //  'max_size[photo,1024]',
                       //  "max_dims[photo,1920,450]"
                    ],
                ]);

                
            }


            if ($this->validation->withRequest($this->request)->run() == TRUE) {

                    $file = $this->request->getFile('photo');
                     $filename = $file->getRandomName();
                    $PATH = getcwd();
                    $file->move($PATH . '/uploads/banner/', $filename);

                  
                    $data_val =[
                        
                        "title" =>strip_tags($this->request->getPost('title')),
                        "banner_title" =>strip_tags($this->request->getPost('banner_title')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        "desk_banner" =>$filename
                        
                    ];
                   
                        $id=$this->om->insert_data('tbl_midbanner', $data_val);

                        //Second part updation start

                    
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/midcreate-banner');
          
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
      
        return view('admin/midbanner_create',$this->data);
    }


function banner_edit($id)
{
    $this->data['main'] = 2;
    $this->data['sub'] = 3;
    $this->data['result'] = $result = $this->om->get_selected_data("*", "tbl_banner", ["id" => $id]);

    if ($_POST) {

        $this->data['post'] = $post = $this->request->getPost();
        $this->validation->setRule('status', 'Status', 'required');

        $file  = $this->request->getFile('photo');          // Desktop banner
        $file2 = $this->request->getFile('mobile_photo');   // Mobile banner

        if ($this->validation->withRequest($this->request)->run() == TRUE) {

            $PATH = getcwd();

            // ---- Desktop Banner Upload ----
            if (!empty($file->getName())) {
                $filename = $file->getRandomName();
                $file->move($PATH . '/uploads/banner/', $filename);

                if (!empty($result[0]->desk_banner) && file_exists($PATH . '/uploads/banner/' . $result[0]->desk_banner)) {
                    unlink($PATH . '/uploads/banner/' . $result[0]->desk_banner);
                }
            } else {
                $filename = $result[0]->desk_banner;
            }

            // ---- Mobile Banner Upload ----
            if (!empty($file2->getName())) {
                $filename2 = $file2->getRandomName();
                $file2->move($PATH . '/uploads/banner/', $filename2);

                if (!empty($result[0]->mobile_banner) && file_exists($PATH . '/uploads/banner/' . $result[0]->mobile_banner)) {
                    unlink($PATH . '/uploads/banner/' . $result[0]->mobile_banner);
                }
            } else {
                $filename2 = $result[0]->mobile_banner;
            }

            // ---- Data Update ----
            $data_val = [
                "title"         => strip_tags($this->request->getPost('title')),
                "status"        => strip_tags($this->request->getPost('status')),
                "banner_title"  => strip_tags($this->request->getPost('banner_title')),
                "desk_banner"   => $filename,
                "mobile_banner" => $filename2
            ];

            $this->om->update_data('tbl_banner', ["id" => $id], $data_val);

            $this->session->setFlashdata('success', 'Data Saved successfully!!');
            return redirect()->to(base_url() . '/users/banner-list');
        } else {
            $this->data['errors'] = $this->validation->getErrors();
        }
    }

    return view('admin/bnr_edit', $this->data);
}

    

     function mid_banner_edit($id)
    {
        $this->data['main'] = 2;
        $this->data['sub'] = 26;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_midbanner",["id"=>$id]);
      
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('status', 'Status', 'required');
         
            $file = $this->request->getFile('photo');
          
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                    
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/banner/', $filename);
                        if (!empty($result[0]->desk_banner)&& file_exists($PATH . '/uploads/banner/' . $result[0]->desk_banner))
                            unlink($PATH . '/uploads/banner/' . $result[0]->desk_banner);
                  
                        }else{
                          $filename = $result[0]->desk_banner;
                       }

                    
                   
                  
                    $data_val =[
                        "title "=>strip_tags($this->request->getPost('title')),
                        "status "=> strip_tags($this->request->getPost('status')),
                        "banner_title" =>strip_tags($this->request->getPost('banner_title')),
                        "desk_banner" =>$filename,
                     

                    ];
                   
                        $this->om->update_data('tbl_midbanner',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . 'users/middle-banner-list');     
                // }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/mid_banner_edit',$this->data);
    }

       function other_banner_edit($id)
    {
        $this->data['main'] = 2;
        $this->data['sub'] = 27;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_otherbanner",["id"=>$id]);
      
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('status', 'Status', 'required');
         
            $file = $this->request->getFile('photo');
          
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                    
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/banner/', $filename);
                        if (!empty($result[0]->desk_banner)&& file_exists($PATH . '/uploads/banner/' . $result[0]->desk_banner))
                            unlink($PATH . '/uploads/banner/' . $result[0]->desk_banner);
                  
                        }else{
                          $filename = $result[0]->desk_banner;
                       }

                    
                   
                  
                    $data_val =[
                        "title "=>strip_tags($this->request->getPost('title')),
                        "status "=> strip_tags($this->request->getPost('status')),
                        "banner_title" =>strip_tags($this->request->getPost('banner_title')),
                        "desk_banner" =>$filename,
                     

                    ];
                   
                        $this->om->update_data('tbl_otherbanner',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . 'users/other-banner-list');     
                // }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/other_banner_edit',$this->data);
    }


    public function bnr_dlt()
    {
        $id = $this->request->getPost('deleteclii');
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/banner-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_banner",["id"=>$val]);
                    if (!empty($result[0]->desk_banner)&& file_exists($PATH . '/uploads/banner/' . $result[0]->desk_banner))
                         unlink($PATH . '/uploads/banner/' . $result[0]->desk_banner);
                    $this->om->delete_data('tbl_banner', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/banner-list');
        }
    }
        public function mid_bnr_dlt()
    {
        $id = $this->request->getPost('deleteclii');
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/banner-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_midbanner",["id"=>$val]);
                    if (!empty($result[0]->desk_banner)&& file_exists($PATH . '/uploads/banner/' . $result[0]->desk_banner))
                         unlink($PATH . '/uploads/banner/' . $result[0]->desk_banner);
                    $this->om->delete_data('tbl_midbanner', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/middle-banner-list');
        }
    }




    

    function about_edit($id)
    {
        $this->data['main'] = 7;
        $this->data['sub'] = 8;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_uniform",["id"=>$id]);
      
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
       
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         

          
           
                $file = $this->request->getFile('photo');
               
               // print_r( $file);exit;
                if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                    {
                        $validated = $this->validate([
                            'photo' => [
                                'uploaded[photo]',
                                'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                              //  'max_size[photo,1024]',
                                //"max_dims[photo,850,570]"
                            ],
                        ]);
        
                        
                    }      
                        
                   
                }
                    
                $filename = $result[0]->img;
                    
                if (!empty($file->getName())) {
                    
                        
                    $file = $this->request->getFile('photo');
                    
                   
                    $filename = $file->getRandomName();
                    $PATH = getcwd();
                    $file->move($PATH . '/uploads/about_ban/', $filename);
                    if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/about_ban/' . $result[0]->img))
                        unlink($PATH . '/uploads/about_ban/' . $result[0]->img);
                

    
                     
                    $data_val =[
                        "title"=>strip_tags($this->request->getPost('title')),
                        "content"=> trim($this->request->getPost('content')),
                        "img" =>$filename

                    ];
                  //print_r($data_val);exit;
                        $this->om->update_data('tbl_uniform',["id"=>$id], $data_val);
                       
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/about-rugo/1');
   
         } else {
               $this->data['errors'] = $this->validation->getErrors();
           }
        }

        return view('admin/about_jazz',$this->data);
    }




   



    function company_details_edit($id)
    {
        $this->data['main'] = 7;
        $this->data['sub'] = 19;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_uniform",["id"=>$id]);
      
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('ph_one', 'Phone Number', 'required');
            $this->validation->setRule('ph_two', 'Phone Number', 'required');
            $this->validation->setRule('email_one', 'Email', 'required');
            $this->validation->setRule('email_two', 'Email', 'required');
            
            $this->validation->setRule('gmap', 'Google Map Embed', 'required');
            $this->validation->setRule('fb', 'Facebook Link', 'required');
            $this->validation->setRule('twi', 'Twitter Link', 'required');
            $this->validation->setRule('inst', 'Instagram Link', 'required');
            $this->validation->setRule('whats', 'Whatsapp Number', 'required');
            $this->validation->setRule('lin', 'Linkedin', 'required');
            $this->validation->setRule('content', 'Company Address', 'required');
            $this->validation->setRule('youtube', 'youtube', 'required');

         
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      

                    $data_val =[
                        "ph_1"=>strip_tags($this->request->getPost('ph_one')),
                        "ph_2"=> strip_tags($this->request->getPost('ph_two')),
                        "email_1"=>strip_tags($this->request->getPost('email_one')),
                        "email_2"=> strip_tags($this->request->getPost('email_two')),
                        "goog_map"=>trim($this->request->getPost('gmap')),
                        "face_book"=> trim($this->request->getPost('fb')),
                        "twitter"=>trim($this->request->getPost('twi')),
                        "instagram"=> trim($this->request->getPost('inst')),
                        "youtube"=> trim($this->request->getPost('youtube')),

                        "whats_app"=>trim($this->request->getPost('whats')),
                        "linkedn"=>trim($this->request->getPost('lin')),
                        "address"=> trim($this->request->getPost('content'))
                        
                    ];
                   
                        $this->om->update_data('tbl_uniform',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/company-details/12');
   
         } else {
               $this->data['errors'] = $this->validation->getErrors();
           }
        }

        return view('admin/company_jazz',$this->data);
    }



    public function upload_ckeditor(){
        $CKEditor = $this->request->getGet("CKEditor");
        $funcNum =$this->request->getGet("CKEditorFuncNum"); 
           $file = $this->request->getFile('upload');
           $type = $file->getMimeType();
           if( $type == "application/pdf" || $type == "image/gif")
           {
            $validated = $this->validate([
              'file' => [
                  'uploaded[upload]',
                  'mime_in[upload,image/jpg,image/jpeg,image/png,application/pdf,image/gif]',
                  'max_size[upload,1024]',
              ],
          ]);
           }else{
            $validated = $this->validate([
              'file' => [
                  'uploaded[upload]',
                  'mime_in[upload,image/jpg,image/jpeg,image/png,application/pdf,image/gif]',
                  
              ],
          ]);
           }
          

          if ($validated) {
            $filename = date("Y-m-d") .'cllit'. $file->getRandomName();
            $PATH = getcwd();
            if( $type == "application/pdf" || $type == "image/gif" || $type == "image/png" || $type == "image/jpg" || $type == "image/jpeg")
            {
              $file->move($PATH . '/uploads/ckeditor/', $filename);
            }
            
            $url = base_url() . 'uploads/ckeditor/' . $filename;
            echo '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "uploaded")</script>';
 
          }else{
            echo "Error: only allowed  jpg,png,pdf,gif or file max size is 1mb";
          }
}

function file_browser()
{
  $path = $this->request->getPost('path');

  if(!empty($path) && file_exists($path))
  {
    unlink($path);
  } 
  $data['fileList'] = glob('uploads/ckeditor/*');



  return view('admin/file', $data);


}




public function service_lst()
{
    $this->data['main'] = 55;
    $this->data['sub'] = 21;
    $this->data['result']  =  $this->om->get_selected_data("*","tbl_service",[],["order"=>["li_order","desc"]]);
    //print_r($this->data['result']);exit;
    return view('admin/service_list',$this->data);
}





    



    function testimo_edit($id)
    {
        $this->data['main'] = 26;
        $this->data['sub'] = 27;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_testi",["id"=>$id]);
      
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('title', 'Title', 'required');
            
            $this->validation->setRule('content', 'Content', 'required');
            $this->validation->setRule('status', 'Status', 'required');
         
            $file = $this->request->getFile('photo');
           
         
            $file = $this->request->getFile('photo');
            $file_mob = $this->request->getFile('mob_bnr');
         
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                    
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/testimo/', $filename);
                        if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/testimo/' . $result[0]->img))
                            unlink($PATH . '/uploads/testimo/' . $result[0]->img);
                    }else{
                        $filename = $result[0]->img;
                    }
//Second Banner
                    if (!empty($file_mob->getName())) {
                        $file_mob = $this->request->getFile('mob_bnr');
                        
                       
                        $filename_mob = $file_mob->getRandomName();
                        $PATH = getcwd();

                       
                        $file_mob->move($PATH . '/uploads/testimo_bnr/', $filename_mob);
                        if (!empty($result[0]->image)&& file_exists($PATH . '/uploads/tstimo_bnr/' . $result[0]->image))
                            unlink($PATH . '/uploads/testimo_bnr/' . $result[0]->image);
                    }else{
                        $filename_mob = $result[0]->image;
                    }
                   
                  
                    $data_val =[
                       
                     //   "name" =>strip_tags($this->request->getPost('name')),
                        "title" =>strip_tags($this->request->getPost('title')),
                       // "order_li" =>strip_tags($this->request->getPost('order_li')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                       
                        
                        "img" =>$filename,
                        "image" =>$filename_mob,

                    ];
                   
                        $this->om->update_data('tbl_testi',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/testimonial-list');     
      
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/testi_edit',$this->data);
    }

   

    
//mobile menu started
   


 

   


    public function proposal()
    {
        $this->data['main'] = 23;
        $this->data['sub'] = 24;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_contact",[],["order"=>["id","desc"]]);
    
       
    
        return view('admin/proposal_list',$this->data);
    }



    public function pro_dlt()
    {
        $id = $this->request->getPost('deleteclii');



       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/proposal');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_contact",["id"=>$val]);
                    if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/contact/' . $result[0]->img))
                    unlink($PATH . '/uploads/contact/' . $result[0]->img);
                    $this->om->delete_data('tbl_contact', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/proposal');
        }
    }


    public function enq()
    {
        $this->data['main'] = 23;
        $this->data['sub'] = 25;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_enq",[],["order"=>["id","desc"]]);
    
       
    
        return view('admin/enq_list',$this->data);
    }


    public function enq_dlt()
    {
        $id = $this->request->getPost('deleteclii');



       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/enq-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                

                    $this->om->delete_data('tbl_enq', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/enq-list');
        }
    }


    public function news_list()
{
    $this->data['main'] = 28;
    $this->data['sub'] = 29;
    $this->data['result']  =  $this->om->get_selected_data("*","tbl_news_update",[],["order"=>["id","desc"]]);
    return view('admin/news_list',$this->data);
}

function news_add()
    {
        $this->data['main'] = 20;
        $this->data['sub'] = 21;
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('title', 'Title', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            
            $file = $this->request->getFile('photo');

    


            if (empty($file->getName())) {
                $this->validation->setRule('photo', 'News Image', 'required');
               
            }
            
            else{
                $validated = $this->validate([
                    'photo' => [
                        'uploaded[photo]',
                        'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                      //  'max_size[photo,1024]',
                      //  "max_dims[photo,700,360]"
                    ],
                ]);

                
            }


            if ($this->validation->withRequest($this->request)->run() == TRUE) {

                    $file = $this->request->getFile('photo');
                    $filename = $file->getRandomName();
                    $PATH = getcwd();
                    $file->move($PATH . '/uploads/news/', $filename);

                    $date = date('Y-m-d H:i:s');

                 
                  
                    $data_val =[
                        
                        "head" =>strip_tags($this->request->getPost('title')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        "doc" => $date,

                        


                        "img" =>$filename
                        
                    ];
                   
                        $id=$this->om->insert_data('tbl_news_update', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/news-list');
          
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
      
        return view('admin/news_add',$this->data);
    }



    function news_edit($id)
    {
        $this->data['main'] = 20;
        $this->data['sub'] = 21;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_news_update",["id"=>$id]);
      
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
                        
            $this->validation->setRule('title', 'Title', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            
           

    
            $file = $this->request->getFile('photo');
            $file_mob = $this->request->getFile('mob_bnr');
         
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                    
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/news/', $filename);
                        if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/news/' . $result[0]->img))
                            unlink($PATH . '/uploads/news/' . $result[0]->img);
                    }else{
                        $filename = $result[0]->img;
                    }
//Second Banner
                    if (!empty($file_mob->getName())) {
                        $file_mob = $this->request->getFile('mob_bnr');
                        
                       
                        $filename_mob = $file_mob->getRandomName();
                        $PATH = getcwd();

                       
                        $file_mob->move($PATH . '/uploads/news_bnr/', $filename_mob);
                        if (!empty($result[0]->image)&& file_exists($PATH . '/uploads/news_bnr/' . $result[0]->image))
                            unlink($PATH . '/uploads/news_bnr/' . $result[0]->image);
                    }else{
                        $filename_mob = $result[0]->image;
                    }
                   

                    $date = date('Y-m-d H:i:s');
                    $data_val =[
                        
                        "head" =>strip_tags($this->request->getPost('title')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        "doc" => $date,

                        


                        "img" =>$filename,
                        "image" =>$filename_mob
                        


                       
                        
                    ];
                  
                   
                        $this->om->update_data('tbl_news_update',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/news-list');     
      
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/news_edit',$this->data);
    }

    public function news_dlt()
    {
        $id = $this->request->getPost('deleteclii');



       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/news-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_news_update",["id"=>$val]);
                    if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/news/' . $result[0]->img))
                    unlink($PATH . '/uploads/news/' . $result[0]->img);
                    $this->om->delete_data('tbl_news_update', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/news-list');
        }
    }

    public function trusted_client_list()
    {
        $this->data['main'] = 30;
        $this->data['sub'] = 31;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_trusted_client",[],["order"=>["id","desc"]]);
        return view('admin/client_list',$this->data);
    }

    function client_add()
    {
        $this->data['main'] = 30;
        $this->data['sub'] = 31;
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('status', 'Status', 'required');
           
         
            
            $file = $this->request->getFile('photo');

    


            if (empty($file->getName())) {
                $this->validation->setRule('photo', 'Image', 'required');
               
            }
            
            else{
                $validated = $this->validate([
                    'photo' => [
                        'uploaded[photo]',
                        'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                        //'max_size[photo,1024]',
                       // "max_dims[photo,800,600]"
                    ],
                ]);

                
            }


            if ($this->validation->withRequest($this->request)->run() == TRUE) {

                    $file = $this->request->getFile('photo');
                    $filename = $file->getRandomName();
                    $PATH = getcwd();
                    $file->move($PATH . '/uploads/client/', $filename);

                    $date = date('Y-m-d H:i:s');

                 
                  
                    $data_val =[
                        
                        "name" =>strip_tags($this->request->getPost('title')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        "doc" => $date,
                        "img" =>$filename,
                        
                    ];

                  
                   
                        $id=$this->om->insert_data('tbl_trusted_client', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/client-list');
          
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
      
        return view('admin/client_add',$this->data);
    }

    function client_edit($id)
    {
        $this->data['main'] = 30;
        $this->data['sub'] = 31;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_trusted_client",["id"=>$id]);
    
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
                        
            $this->validation->setRule('title', 'title', 'required');

            $this->validation->setRule('status', 'Status', 'required');
         
            
           

    
            $file = $this->request->getFile('photo');
           
         
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                
                    $validated = $this->validate([
                        'photo' => [
                            'uploaded[photo]',
                            'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                          //  'max_size[photo,1024]',
                          //  "max_dims[photo,600,400]"
                        ],
                    ]);
    
                    
                 
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/client/', $filename);
                        if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/client/' . $result[0]->img))
                            unlink($PATH . '/uploads/client/' . $result[0]->img);
                            $date = date('Y-m-d H:i:s');
                    }else{
                        $filename = $result[0]->img;
                    }
                    $date = date('Y-m-d H:i:s');
                   
                    $data_val =[
                        
                        "name" =>strip_tags($this->request->getPost('title')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        "doc" => $date,

                        


                        "img" =>$filename

                        


                       
                        
                    ];
                  
                    
                        $this->om->update_data('tbl_trusted_client',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/client-list');     
      
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/client_edit',$this->data);
    }


    public function client_dlt()
    {
        $id = $this->request->getPost('deleteclii');



       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/client-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_trusted_client",["id"=>$val]);
                    if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/client/' . $result[0]->img))
                    unlink($PATH . '/uploads/client/' . $result[0]->img);
                    $this->om->delete_data('tbl_trusted_client', ["id"=>$val]);

            }
           

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/client-list');
        }
    }
   
      




      
        
        
         




         
            public function announce_list()
{
    $this->data['main'] = 55;
    $this->data['sub'] = 43;
    $this->data['result']  =  $this->om->get_selected_data("*","tbl_announce",[],["order"=>["id","desc"]]);
    return view('admin/annoucelist',$this->data);
}

function announce_add()
    {
        $this->data['main'] = 55;
        $this->data['sub'] = 43;
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('date', 'Date', 'required');
            $this->validation->setRule('order_li', 'Order', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            
            $file = $this->request->getFile('photo');

    


            if (empty($file->getName())) {
                $this->validation->setRule('photo', 'News Image', 'required');
               
            }
            
            else{
                $validated = $this->validate([
                    'photo' => [
                        'uploaded[photo]',
                        'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                       // 'max_size[photo,1024]',
                       // "max_dims[photo,700,360]"
                    ],
                ]);

                
            }


            if ($this->validation->withRequest($this->request)->run() == TRUE) {

                    $file = $this->request->getFile('photo');
                    $filename = $file->getRandomName();
                    $PATH = getcwd();
                    $file->move($PATH . '/uploads/announce/', $filename);

                    $date = date('Y-m-d H:i:s');

                 
                  
                    $data_val =[
                        
                        "title" =>strip_tags($this->request->getPost('title')),
                        "date" =>strip_tags($this->request->getPost('date')),
                        "order_li" =>strip_tags($this->request->getPost('order_li')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        "date" => $date,

                        


                        "img" =>$filename
                        
                    ];
                   
                        $id=$this->om->insert_data('tbl_announce', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/announce-list');
          
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
      
        return view('admin/announceadd',$this->data);
    }

    function announce_edit($id)
    {
        $this->data['main'] = 55;
        $this->data['sub'] = 43;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_announce",["id"=>$id]);
      
        if ($_POST) {
            
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('date', 'Date', 'required');
            $this->validation->setRule('order_li', 'Order', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            
           

    
            $file = $this->request->getFile('photo');
           
         
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                    
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/announce/', $filename);
                        if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/announce/' . $result[0]->img))
                            unlink($PATH . '/uploads/announce/' . $result[0]->img);
                            $date = date('Y-m-d H:i:s');
                    }else{
                        $filename = $result[0]->img;
                    }

                    $date = date('Y-m-d H:i:s');
                    $data_val =[
                        
                        "title" =>strip_tags($this->request->getPost('title')),
                        "date" =>strip_tags($this->request->getPost('date')),
                        "order_li" =>strip_tags($this->request->getPost('order_li')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        "date" => $date,

                        


                        "img" =>$filename
                        


                       
                        
                    ];
                  
                   
                        $this->om->update_data('tbl_announce',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/announce-list');     
      
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/announceedit',$this->data);
    }

    public function announce_dlt()
    {
        $id = $this->request->getPost('deleteclii');



       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/announce-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_announce",["id"=>$val]);
                    if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/announce/' . $result[0]->img))
                    unlink($PATH . '/uploads/announce/' . $result[0]->img);
                    $this->om->delete_data('tbl_announce', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/announce-list');
        }
    }

    public function activity_list()
    {
        $this->data['main'] = 55;
        $this->data['sub'] = 45;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_activities",[],["order"=>["id","desc"]]);
        return view('admin/activitylist',$this->data);
    }


    function activity_add()
    {
        $this->data['main'] = 55;
        $this->data['sub'] = 45;
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
            
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('date', 'Date', 'required');
            $this->validation->setRule('order_li', 'Order', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            
            $file = $this->request->getFile('photo');

    


            if (empty($file->getName())) {
                $this->validation->setRule('photo', 'News Image', 'required');
               
            }
            
            else{
                $validated = $this->validate([
                    'photo' => [
                        'uploaded[photo]',
                        'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                     //   'max_size[photo,1024]',
                    //    "max_dims[photo,700,360]"
                    ],
                ]);

                
            }


            if ($this->validation->withRequest($this->request)->run() == TRUE) {

                    $file = $this->request->getFile('photo');
                    $filename = $file->getRandomName();
                    $PATH = getcwd();
                    $file->move($PATH . '/uploads/activity/', $filename);

                    $date = date('Y-m-d H:i:s');

                 
                  
                    $data_val =[
                        
                        "title" =>strip_tags($this->request->getPost('title')),
                        "doc_d" =>strip_tags($this->request->getPost('date')),
                        "order_li" =>strip_tags($this->request->getPost('order_li')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                        //"date" => $date,

                        


                        "img" =>$filename
                        
                    ];
                   
                        $id=$this->om->insert_data('tbl_activities', $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/activity-list');
          
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
      
        return view('admin/activityadd',$this->data);
    }

    function activity_edit($id)
    {
        $this->data['main'] = 55;
        $this->data['sub'] = 45;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_activities",["id"=>$id]);
      
        if ($_POST) {
            
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('date', 'Date', 'required');
            $this->validation->setRule('order_li', 'Order', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            
           

    
            $file = $this->request->getFile('photo');
           
         
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                    
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/activity/', $filename);
                        if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/activity/' . $result[0]->img))
                            unlink($PATH . '/uploads/activity/' . $result[0]->img);
                            $date = date('Y-m-d H:i:s');
                    }else{
                        $filename = $result[0]->img;
                    }

                    $date = date('Y-m-d H:i:s');
                    $data_val =[
                        
                        "title" =>strip_tags($this->request->getPost('title')),
                        "doc_d" =>strip_tags($this->request->getPost('date')),
                        "order_li" =>strip_tags($this->request->getPost('order_li')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                       // "date" => $date,

                        


                        "img" =>$filename
                        


                       
                        
                    ];
                  
                   
                        $this->om->update_data('tbl_activities',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/activity-list');     
      
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/activityedit',$this->data);
    }

    public function activity_dlt()
    {
        $id = $this->request->getPost('deleteclii');



       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/activity-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_activities",["id"=>$val]);
                    if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/activity/' . $result[0]->img))
                    unlink($PATH . '/uploads/activity/' . $result[0]->img);
                    $this->om->delete_data('tbl_activities', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/activity-list');
        }
    }

    public function tourist_list()
    {
        $this->data['main'] = 46;
        $this->data['sub'] = 47;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_tourist_destin",[],["order"=>["id","desc"]]);
       // print_r($this->data['result'] );exit;
        return view('admin/touristlist',$this->data);
    }


    function tourisam_add()
    {
        $this->data['main'] = 46;
        $this->data['sub'] = 47;
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('date', 'Date', 'required');
            $this->validation->setRule('order_li', 'Order', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            
            $file = $this->request->getFile('photo');
            $filemob = $this->request->getFile('mob_bnr');

    

            if (empty($file->getName()) || empty($filemob->getName())) {
                $this->validation->setRule('photo', 'Desktop Banner', 'required');
                $this->validation->setRule('mob_bnr', 'Mobile Banner', 'required');


            }
            
            else{
                $validated = $this->validate([
                    'photo' => [
                        'uploaded[photo]',
                        'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image]',
                      //  'max_size[photo,1024]',
                     //   "max_dims[photo,700,360]"
                    ],
                ]);

                
            }


            if ($this->validation->withRequest($this->request)->run() == TRUE) {

                    $file = $this->request->getFile('photo');
                    $filemob = $this->request->getFile('mob_bnr');
                    $filename = $file->getRandomName();
                    $PATH = getcwd();
                    $file->move($PATH . '/uploads/tourisam/', $filename);

                    $date = date('Y-m-d H:i:s');

                 
                  
                    $data_val =[
                        "select_type" =>'',
                        "name" =>strip_tags($this->request->getPost('title')),
                        "date" =>strip_tags($this->request->getPost('date')),
                        "order_li" =>strip_tags($this->request->getPost('order_li')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                       
                        
                        //"date" => $date,

                        


                        "img" =>$filename
                        
                    ];
                 // print_r($data_val);exit;
                        $id=$this->om->insert_data('tbl_tourist_destin', $data_val);

                        $filename_mob = $filemob->getRandomName();
                        $PATHD = getcwd();
                        $filemob->move($PATHD . '/uploads/tourisam_mob/', $filename_mob);

                        $data_vals =["image" =>$filename_mob];

                        $this->om->update_data('tbl_tourist_destin',["id"=>$id], $data_vals);

                       
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/tourist-list');
          
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }
      
        return view('admin/tourisamadd',$this->data);
    }

    
    function tourist_edit($id)
    {
        $this->data['main'] = 46;
        $this->data['sub'] = 47;
        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_tourist_destin",["id"=>$id]);
      
        if ($_POST) {
            
            $this->data['post']=$post = $this->request->getPost();
           
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('date', 'Date', 'required');
            $this->validation->setRule('order_li', 'Order', 'required');

            $this->validation->setRule('status', 'Status', 'required');
            $this->validation->setRule('content', 'Content', 'required');
         
            $file = $this->request->getFile('photo');
            $file_mob = $this->request->getFile('mob_bnr');
         
            if ($this->validation->withRequest($this->request)->run() == TRUE) {
               
                      
                    
               

                
                    if (!empty($file->getName())) {
                        $file = $this->request->getFile('photo');
                        
                       
                        $filename = $file->getRandomName();
                        $PATH = getcwd();
                        $file->move($PATH . '/uploads/tourisam/', $filename);
                        if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/tourisam/' . $result[0]->img))
                            unlink($PATH . '/uploads/tourisam/' . $result[0]->img);
                    }else{
                        $filename = $result[0]->img;
                    }
//Second Banner
                    if (!empty($file_mob->getName())) {
                        $file_mob = $this->request->getFile('mob_bnr');
                        
                       
                        $filename_mob = $file_mob->getRandomName();
                        $PATH = getcwd();

                       
                        $file_mob->move($PATH . '/uploads/tourisam_mob/', $filename_mob);
                        if (!empty($result[0]->image)&& file_exists($PATH . '/uploads/tourisam_mob/' . $result[0]->image))
                            unlink($PATH . '/uploads/tourisam_mob/' . $result[0]->image);
                    }else{
                        $filename_mob = $result[0]->image;
                    }
                   
                  
                    $data_val =[
                        "select_type" =>'',
                        "name" =>strip_tags($this->request->getPost('title')),
                        "date" =>strip_tags($this->request->getPost('date')),
                        "order_li" =>strip_tags($this->request->getPost('order_li')),
                        "content" =>strip_tags($this->request->getPost('content')),
                        "status" =>strip_tags($this->request->getPost('status')),
                       
                        
                        "img" =>$filename,
                        "image" =>$filename_mob,

                    ];
                   
                        $this->om->update_data('tbl_tourist_destin',["id"=>$id], $data_val);
                        $this->session->setFlashdata('success', 'Data Saved successfully!!');
                        return  redirect()->to(base_url() . '/users/tourist-list');     
                // }
              
               
              
                
                    
                    
           
         } else {
            

               $this->data['errors'] = $this->validation->getErrors();
           }
        

       
        }

       
        return view('admin/tourisamedit',$this->data);
    }


    public function tourist_dlt()
    {
        $id = $this->request->getPost('deleteclii');



       
        if (empty($id)) {
            $this->session->setFlashdata('error', 'Please select at least one!!');
            return  redirect()->to(base_url() . '/users/tourist-list');
        } else {
            $all_error = 0;
            foreach ($id as $val) {
                $error = 0;

                
                $PATH = getcwd();

                    $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_tourist_destin",["id"=>$val]);
                    if (!empty($result[0]->mobile_banner)&& file_exists($PATH . '/uploads/tourisam_mob/' . $result[0]->image))
                    unlink($PATH . '/uploads/tourisam_mob/' . $result[0]->image);
                    if (!empty($result[0]->img)&& file_exists($PATH . '/uploads/tourisam/' . $result[0]->img))
                    unlink($PATH . '/uploads/tourisam/' . $result[0]->img);
                    $this->om->delete_data('tbl_tourist_destin', ["id"=>$val]);

            }

            if ($error == 0) {
                $this->session->setFlashdata('success', 'Data Deleted !!');
            } else {
                $this->session->setFlashdata('error', 'Some id already in use');
                $this->session->setFlashdata('success', 'Data Deleted !!');
            }

            return  redirect()->to(base_url() . '/users/tourist-list');
        }
    }


    public function companydtl_list()
    {
        $this->data['main'] = 33;
        $this->data['sub'] = 34;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_companydtl",[],["order"=>["id","desc"]]);
        //$this->data['result']  =  $this->om->jointbl("a.id,a.main,a.sub,a.order_no,a.sub_division,a.content,a.active,b.head","tbl_submenu a",["tbl_mainmenu b,a.main=b.id"],[],["order"=>["  b.id","desc"]]);
   
      
        return view('admin/companydtl_list',$this->data);
    }
    

    function companydtl_edit($id)
            {
             
                $this->data['main'] = 33;
                $this->data['sub'] = 34;
                $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_companydtl",["id"=>$id]);
                $this->data['main']  =  $this->om->get_selected_data("*","tbl_companydtl",[],["order"=>["id","desc"]]);
               
                $this->data['sub']  =  $this->om->get_selected_data("*","tbl_companydtl",[],["order"=>["id","desc"]]);
             
                if ($_POST) {
                    
                    $this->data['post']=$post = $this->request->getPost();
                                
                    $this->validation->setRule('ci', ' cities', 'required');
                    $this->validation->setRule('noci', 'No.cites', 'required');
                    $this->validation->setRule('user', 'No.user', 'required');
                    $this->validation->setRule('no.user', 'No.user', 'required');
                    $this->validation->setRule('driver', 'Driver', 'required');
                    $this->validation->setRule('nodriver', 'No.Driver', 'required');
                    $this->validation->setRule('cust', 'Customer', 'required');
                    $this->validation->setRule('nocust', 'No.Customer', 'required');
                 
                    
                   
                    
           
                   
        
                            $date = date('Y-m-d H:i:s');
                    
                           
                           
                           
                            $data_val =[
                                
                                "cities" =>strip_tags($this->request->getPost('ci')),
                                "no_cities" =>strip_tags($this->request->getPost('noci')),
                                "user" =>strip_tags($this->request->getPost('user')),
                                "nouser" =>strip_tags($this->request->getPost('nouser')),
                                "driver" =>strip_tags($this->request->getPost('driver')),
                                "nodriver" =>strip_tags($this->request->getPost('nodriver')),
                                "cust" =>strip_tags($this->request->getPost('cust')),
                                "nocust" =>strip_tags($this->request->getPost('nocust')),
                                
                            ];
                            
                     //print_r( $data_val);exit;
                           
                                $this->om->update_data('tbl_companydtl',["id"=>$id], $data_val);
                                $this->session->setFlashdata('success', 'Data Saved successfully!!');
                                return  redirect()->to(base_url() . '/users/companydtl-list');     
              
                   
                   {
                    
        
                       $this->data['errors'] = $this->validation->getErrors();
                      // print_r($this->data['errors']);exit;
                   }
                
        
               
            }
        
               
                return view('admin/companydtledit',$this->data);
            }

//footer link started
public function footerlink_list()
    {
        $this->data['main'] = 4;
        $this->data['sub'] = 12;
        $this->data['result']  =  $this->om->get_selected_data("*","tbl_footerlink",[],["order"=>["id","desc"]]);
        return view('admin/footerlinklist',$this->data);
    }
    
    function footerlink_add()
        {
            $this->data['main'] = 4;
            $this->data['sub'] = 13;
           
            if ($_POST) {
                
                $this->data['post']=$post = $this->request->getPost();
               
                $this->validation->setRule('title', 'Title', 'required');
    
                $this->validation->setRule('status', 'Status', 'required');
                
                  
                
             
    
                        $date = date('Y-m-d H:i:s');
    
                     
                      
                        $data_val =[
                            
                            "title" =>strip_tags($this->request->getPost('title')),
                           
                            "status" =>strip_tags($this->request->getPost('status')),
                         
    
                            
    
    
                           
                            
                        ];
                    
                            $id=$this->om->insert_data('tbl_footerlink', $data_val);
                            $this->session->setFlashdata('success', 'Data Saved successfully!!');
                            return  redirect()->to(base_url() . '/users/footerlink-list');
              
               
             } else {
                
    
                   $this->data['errors'] = $this->validation->getErrors();
               }
            
    
           
            
          
            return view('admin/footerlink',$this->data);
        }
    
    
    
        function footerlink_edit($id)
        {
            $this->data['main'] = 51;
            $this->data['sub'] = 0;
            $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_footerlink",["id"=>$id]);
          
            if ($_POST) {
                
                $this->data['post']=$post = $this->request->getPost();
                            
                $this->validation->setRule('title', 'Title', 'required');
    
                $this->validation->setRule('status', 'Status', 'required');
               
                
               
    
        
               
                        $date = date('Y-m-d H:i:s');
                        $data_val =[
                            
                            "title" =>strip_tags($this->request->getPost('title')),
                            
                            "status" =>strip_tags($this->request->getPost('status')),
                        
    
                       
                           
                            
                        ];
                      
                       
                            $this->om->update_data('tbl_footerlink',["id"=>$id], $data_val);
                            $this->session->setFlashdata('success', 'Data Saved successfully!!');
                            return  redirect()->to(base_url() . '/users/footerlink-list');     
          
               
             } else {
                
    
                   $this->data['errors'] = $this->validation->getErrors();
               }
            
    
           
            
    
           
            return view('admin/footerlinkedit',$this->data);
        }
    
        public function footerlink_dlt()
        {
            $id = $this->request->getPost('deleteclii');
    
    
    
           
            if (empty($id)) {
                $this->session->setFlashdata('error', 'Please select at least one!!');
                return  redirect()->to(base_url() . '/users/footerlink-list');
            } else {
                $all_error = 0;
                foreach ($id as $val) {
                    $error = 0;
    
                    
                    $PATH = getcwd();
    
                        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_footerlink",["id"=>$val]);
                        
                        $this->om->delete_data('tbl_footerlink', ["id"=>$val]);
    
                }
    
                if ($error == 0) {
                    $this->session->setFlashdata('success', 'Data Deleted !!');
                } else {
                    $this->session->setFlashdata('error', 'Some id already in use');
                    $this->session->setFlashdata('success', 'Data Deleted !!');
                }
    
                return  redirect()->to(base_url() . '/users/footerlink-list');
            }
        }


        public function footerlinksub_list()
    {
        $this->data['main'] = 4;
        $this->data['sub'] = 13;
        $this->data['result']  =  $this->om->footerlinkjoin();
        return view('admin/footermenulist',$this->data);
    }

    function footermenu_add()
        {
            $this->data['main'] = 4;
            $this->data['sub'] = 13;
            $this->data['result']  =  $this->om->get_selected_data("*","tbl_footerlink",[],["order"=>["id","desc"]]);
            if ($_POST) {
                
                $this->data['post']=$post = $this->request->getPost();
                $this->validation->setRule('head', 'Head', 'required');
                $this->validation->setRule('title', 'Title', 'required');
    
                $this->validation->setRule('status', 'Status', 'required');
             
                if ($this->validation->withRequest($this->request)->run() == TRUE) {
          
              
                
                
             
    
                        $date = date('Y-m-d H:i:s');
    
                     
                      
                        $data_val =[
                            "title_footlink_id" =>strip_tags($this->request->getPost('head')),
                            "head" =>strip_tags($this->request->getPost('title')),
                           
                            "status" =>strip_tags($this->request->getPost('status')),
               
    
                            
    
    
                           
                            
                        ];

                            $id=$this->om->insert_data('tbl_footermenus', $data_val);
                            $this->session->setFlashdata('success', 'Data Saved successfully!!');
                            return  redirect()->to(base_url() . '/users/footermenu-list');
              
               
             } else {
                
    
                   $this->data['errors'] = $this->validation->getErrors();
               }
            
            }
           
            
          
            return view('admin/footmenuadd',$this->data);
        }

        function footermenu_edit($id)
        {
            $this->data['main'] = 51;
            $this->data['sub'] = 0;
         
            $this->data['main']  =  $this->om->get_selected_data("*","tbl_footerlink",[],["order"=>["id","desc"]]);
           
             $this->data['result']  =  $this->om->footermenujoin($id);
          
          
            if ($_POST) {
                
                $this->data['post']=$post = $this->request->getPost();
                $this->validation->setRule('main', 'Head', 'required');         
                $this->validation->setRule('title', 'Title', 'required');
    
                $this->validation->setRule('status', 'Status', 'required');
             
                if ($this->validation->withRequest($this->request)->run() == TRUE) {
          
                
               
    
        
               
                        $date = date('Y-m-d H:i:s');
                        $data_val =[
                            "title_footlink_id" =>strip_tags($this->request->getPost('main')),
                            "head" =>strip_tags($this->request->getPost('title')),
                             "status" =>strip_tags($this->request->getPost('status')),
                           
                            "content"=>strip_tags($this->request->getPost('content')),
                           ];
                      
                 
                            $this->om->update_data('tbl_footermenus',["id"=>$id], $data_val);
                            $this->session->setFlashdata('success', 'Data Saved successfully!!');
                            return  redirect()->to(base_url() . '/users/footermenu-list');     
          
               
             } else {
                
    
                   $this->data['errors'] = $this->validation->getErrors();
                
               }
            
    
           
            
    
           
           
        }
        return view('admin/footermenuedit',$this->data);
    }

        public function footermenu_dlt()
        {
            $id = $this->request->getPost('deleteclii');
    
    
    
           
            if (empty($id)) {
                $this->session->setFlashdata('error', 'Please select at least one!!');
                return  redirect()->to(base_url() . '/users/footermenu-list');
            } else {
                $all_error = 0;
                foreach ($id as $val) {
                    $error = 0;
    
                    
                    $PATH = getcwd();
    
                        $this->data['result']=$result =  $this->om->get_selected_data("*","tbl_footermenus",["id"=>$val]);
                        
                        $this->om->delete_data('tbl_footermenus', ["id"=>$val]);
    
                }
    
                if ($error == 0) {
                    $this->session->setFlashdata('success', 'Data Deleted !!');
                } else {
                    $this->session->setFlashdata('error', 'Some id already in use');
                    $this->session->setFlashdata('success', 'Data Deleted !!');
                }
    
                return  redirect()->to(base_url() . '/users/footermenu-list');
            }
        }

//footer link ended



function getsubmenu($id)  {
    $getsubmenu  =  $this->om->get_selected_data("*","tbl_subdivmenu",[],["order"=>["id","desc"]]);
     echo \json_encode($getsubmenu);       
}






   




}




    







