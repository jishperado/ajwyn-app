<?php

namespace App\Services;

use App\Models\CommanModel;

class EmailNotificationService
{
    private $adminEmail = 'info@ajwyn.site';
    private $appName;

    public function __construct()
    {
        $this->appName = getenv('APP_NAME') ?: 'AJWYN';
    }

    /**
     * Send email using direct SMTP (bypasses CI4 email library)
     */
    private function send(string $to, string $subject, string $body): bool
    {
        if (empty($to)) return false;

        helper('smtp');
        return smtp_send($to, $subject, $body);
    }

    /**
     * Get all vendor emails
     */
    private function getVendorEmails(): array
    {
        $om = new CommanModel();
        $vendors = $om->get_selected_data('email', 'admin_log', ['role' => 'vendor', 'is_active' => 'Y']);
        $emails = [];
        foreach ($vendors as $v) {
            if (!empty($v->email)) {
                $emails[] = $v->email;
            }
        }
        return $emails;
    }

    /**
     * Wrap content in a styled email template
     */
    private function wrap(string $content): string
    {
        return '<div style="font-family: Arial, sans-serif; max-width: 520px; margin: 0 auto; padding: 20px; background: #ffffff; border-radius: 10px; border: 1px solid #eee;">'
            . $content
            . '<hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
               <p style="font-size: 12px; color: #999; text-align: center;">' . $this->appName . ' &bull; <a href="https://www.ajwyn.site" style="color: #0B61D6;">www.ajwyn.site</a></p>
             </div>';
    }

    // =========================================================
    //  1. ORDER PLACED
    // =========================================================

    /**
     * Notify admin when a new order is placed
     */
    public function orderPlacedAdmin(string $orderId, string $customerName, string $amount, string $date): void
    {
        $body = $this->wrap('
            <h2 style="color: #0B61D6; margin-top: 0;">New Order Received</h2>
            <p style="font-size: 15px; color: #333;">A new order has been placed on your store.</p>
            <div style="background: #f0f7ff; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '<br>
                    <strong>Customer:</strong> ' . $customerName . '<br>
                    <strong>Date:</strong> ' . $date . '<br>
                    <strong>Amount:</strong> ₹' . $amount . '
                </p>
            </div>
            <p style="font-size: 14px; color: #555;">Please log in to the admin panel to process this order.</p>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . ' System</strong></p>
        ');

        $this->send($this->adminEmail, 'New Order #' . $orderId . ' - ' . $this->appName, $body);
    }

    /**
     * Notify all vendors when a new order is placed
     */
    public function orderPlacedVendors(string $orderId, string $customerName, string $amount, string $date): void
    {
        $body = $this->wrap('
            <h2 style="color: #0B61D6; margin-top: 0;">New Order Placed</h2>
            <p style="font-size: 15px; color: #333;">A new order has been placed on ' . $this->appName . '.</p>
            <div style="background: #f0f7ff; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '<br>
                    <strong>Customer:</strong> ' . $customerName . '<br>
                    <strong>Date:</strong> ' . $date . '<br>
                    <strong>Amount:</strong> ₹' . $amount . '
                </p>
            </div>
            <p style="font-size: 14px; color: #555;">Log in to the vendor panel to check if this order includes your products.</p>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . '</strong></p>
        ');

        $subject = 'New Order #' . $orderId . ' - ' . $this->appName;
        foreach ($this->getVendorEmails() as $vendorEmail) {
            $this->send($vendorEmail, $subject, $body);
        }
    }

    // =========================================================
    //  2. ORDER SHIPPED
    // =========================================================

    /**
     * Notify customer when order is shipped
     */
    public function orderShippedCustomer(string $customerEmail, string $customerName, string $orderId, ?string $trackId = null, ?string $trackUrl = null): void
    {
        $trackingHtml = '';
        if (!empty($trackId)) {
            $trackingHtml .= '<strong>Tracking ID:</strong> ' . $trackId . '<br>';
        }
        if (!empty($trackUrl)) {
            $trackingHtml .= '<strong>Track here:</strong> <a href="' . $trackUrl . '" style="color: #0B61D6;">' . $trackUrl . '</a><br>';
        }

        $body = $this->wrap('
            <h2 style="color: #0B61D6; margin-top: 0;">Order Shipped!</h2>
            <p style="font-size: 15px; color: #333;">Hi ' . $customerName . ',</p>
            <p style="font-size: 15px; color: #333;">Your order <strong>#' . $orderId . '</strong> has been <strong>shipped</strong> and is on its way!</p>
            <div style="background: #f0f7ff; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '<br>
                    ' . $trackingHtml . '
                </p>
            </div>
            <p style="font-size: 14px; color: #555;">You will receive another notification when your order is delivered.</p>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . '</strong></p>
        ');

        $this->send($customerEmail, 'Your Order #' . $orderId . ' has been shipped! - ' . $this->appName, $body);
    }

    /**
     * Notify admin when order is shipped
     */
    public function orderShippedAdmin(string $orderId, ?string $trackId = null): void
    {
        $body = $this->wrap('
            <h2 style="color: #0B61D6; margin-top: 0;">Order Shipped</h2>
            <p style="font-size: 15px; color: #333;">Order <strong>#' . $orderId . '</strong> has been marked as <strong>shipped</strong>.</p>
            <div style="background: #f0f7ff; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '<br>
                    ' . (!empty($trackId) ? '<strong>Tracking ID:</strong> ' . $trackId : '') . '
                </p>
            </div>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . ' System</strong></p>
        ');

        $this->send($this->adminEmail, 'Order #' . $orderId . ' Shipped - ' . $this->appName, $body);
    }

    /**
     * Notify all vendors when order is shipped
     */
    public function orderShippedVendors(string $orderId, ?string $trackId = null): void
    {
        $body = $this->wrap('
            <h2 style="color: #0B61D6; margin-top: 0;">Order Shipped</h2>
            <p style="font-size: 15px; color: #333;">Order <strong>#' . $orderId . '</strong> has been shipped.</p>
            <div style="background: #f0f7ff; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '<br>
                    ' . (!empty($trackId) ? '<strong>Tracking ID:</strong> ' . $trackId : '') . '
                </p>
            </div>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . '</strong></p>
        ');

        $subject = 'Order #' . $orderId . ' Shipped - ' . $this->appName;
        foreach ($this->getVendorEmails() as $vendorEmail) {
            $this->send($vendorEmail, $subject, $body);
        }
    }

    // =========================================================
    //  3. ORDER DELIVERED
    // =========================================================

    /**
     * Notify customer when order is delivered
     */
    public function orderDeliveredCustomer(string $customerEmail, string $customerName, string $orderId): void
    {
        $body = $this->wrap('
            <h2 style="color: #28a745; margin-top: 0;">Order Delivered!</h2>
            <p style="font-size: 15px; color: #333;">Hi ' . $customerName . ',</p>
            <p style="font-size: 15px; color: #333;">Your order <strong>#' . $orderId . '</strong> has been <strong>delivered</strong> successfully!</p>
            <div style="background: #f0fff4; padding: 12px 16px; border-radius: 8px; margin: 14px 0; border-left: 4px solid #28a745;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '<br>
                    <strong>Status:</strong> Delivered
                </p>
            </div>
            <p style="font-size: 14px; color: #555;">We hope you enjoy your purchase! If you have any issues, please contact our support.</p>
            <p style="font-size: 14px; color: #333;">Thank you for shopping with us!<br><strong>' . $this->appName . '</strong></p>
        ');

        $this->send($customerEmail, 'Your Order #' . $orderId . ' has been delivered! - ' . $this->appName, $body);
    }

    /**
     * Notify admin when order is delivered
     */
    public function orderDeliveredAdmin(string $orderId): void
    {
        $body = $this->wrap('
            <h2 style="color: #28a745; margin-top: 0;">Order Delivered</h2>
            <p style="font-size: 15px; color: #333;">Order <strong>#' . $orderId . '</strong> has been marked as <strong>delivered</strong>.</p>
            <div style="background: #f0fff4; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '
                </p>
            </div>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . ' System</strong></p>
        ');

        $this->send($this->adminEmail, 'Order #' . $orderId . ' Delivered - ' . $this->appName, $body);
    }

    /**
     * Notify all vendors when order is delivered
     */
    public function orderDeliveredVendors(string $orderId): void
    {
        $body = $this->wrap('
            <h2 style="color: #28a745; margin-top: 0;">Order Delivered</h2>
            <p style="font-size: 15px; color: #333;">Order <strong>#' . $orderId . '</strong> has been delivered.</p>
            <div style="background: #f0fff4; padding: 12px 16px; border-radius: 8px; margin: 14px 0;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '
                </p>
            </div>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . '</strong></p>
        ');

        $subject = 'Order #' . $orderId . ' Delivered - ' . $this->appName;
        foreach ($this->getVendorEmails() as $vendorEmail) {
            $this->send($vendorEmail, $subject, $body);
        }
    }

    // =========================================================
    //  4. ORDER CANCELLED
    // =========================================================

    /**
     * Notify admin when order is cancelled
     */
    public function orderCancelledAdmin(string $orderId, string $customerName): void
    {
        $body = $this->wrap('
            <h2 style="color: #e63946; margin-top: 0;">Order Cancelled</h2>
            <p style="font-size: 15px; color: #333;">An order has been <strong>cancelled</strong> by the customer.</p>
            <div style="background: #fff5f5; padding: 12px 16px; border-radius: 8px; margin: 14px 0; border-left: 4px solid #e63946;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '<br>
                    <strong>Customer:</strong> ' . $customerName . '
                </p>
            </div>
            <p style="font-size: 14px; color: #555;">Please review this cancellation in the admin panel.</p>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . ' System</strong></p>
        ');

        $this->send($this->adminEmail, 'Order #' . $orderId . ' Cancelled - ' . $this->appName, $body);
    }

    /**
     * Notify all vendors when order is cancelled
     */
    public function orderCancelledVendors(string $orderId, string $customerName): void
    {
        $body = $this->wrap('
            <h2 style="color: #e63946; margin-top: 0;">Order Cancelled</h2>
            <p style="font-size: 15px; color: #333;">Order <strong>#' . $orderId . '</strong> has been cancelled by customer <strong>' . $customerName . '</strong>.</p>
            <div style="background: #fff5f5; padding: 12px 16px; border-radius: 8px; margin: 14px 0; border-left: 4px solid #e63946;">
                <p style="margin: 0; font-size: 14px; color: #555;">
                    <strong>Order ID:</strong> ' . $orderId . '
                </p>
            </div>
            <p style="font-size: 14px; color: #333;">Regards,<br><strong>' . $this->appName . '</strong></p>
        ');

        $subject = 'Order #' . $orderId . ' Cancelled - ' . $this->appName;
        foreach ($this->getVendorEmails() as $vendorEmail) {
            $this->send($vendorEmail, $subject, $body);
        }
    }
}
