<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?= $orderdata[0]->ord_id ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="<?= base_url(); ?>asset/css/main.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>asset/css/mystyle.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>asset/css/swiper.min.css" rel="stylesheet" type="text/css">

    <style>
        body { background:#f5f5f5; font-size:14px; font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; }
        .invoice-wrapper { max-width:900px; margin:30px auto; background:#fff; padding:25px 30px 35px; border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
        .invoice-header { border-bottom:1px solid #e5e5e5; padding-bottom:15px; margin-bottom:20px; }
        .invoice-title { font-size:24px; font-weight:600; letter-spacing:.5px; }
        .invoice-meta span { display:block; line-height:1.4; }
        .text-small { font-size:12px; }
        .table thead th { background:#f1f1f1; border-bottom:none; }
        .table th, .table td { vertical-align:middle; }
        .totals-label { text-align:right; }
        .footer-text { font-size:12px; color:#777; margin-top:25px; border-top:1px dashed #ddd; padding-top:10px; }
        @media print { body { background:#fff; } .invoice-wrapper { box-shadow:none; margin:0; border-radius:0; } .no-print { display:none !important; } }
    </style>
    <!-- Meta Pixel: Purchase event removed — tracked on myorders.php to avoid duplicates -->
</head>
<body>

<div class="invoice-wrapper">

    <!-- Print Button -->
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">Print</button>
    </div>

    <!-- Header -->
    <div class="invoice-header d-flex justify-content-between align-items-start">
        <div>
            <h2 class="mb-1">Ajwyn</h2>
            <div class="text-small text-muted">
                <div><?= $social->address ?? '' ?></div>
                <div>Phone: <?= $social->mobile ?? '' ?></div>
                <div>Email: <?= $social->email ?? '' ?></div>
               
            </div>
        </div>
        <div class="text-end invoice-meta">
            <div class="invoice-title mb-1">INVOICE</div>
            <span><strong>Invoice No:</strong> <?= $orderdata[0]->order_id ?></span>
            <span><strong>Date:</strong> <?= date("d M Y", strtotime($orderdata[0]->created_date)) ?></span>
            <span><strong>Order ID:</strong> #<?= $orderdata[0]->order_id ?></span>
            <span class="mt-2">
                <span class="badge bg-<?= ($orderdata[0]->status == 'Y') ? 'success' : 'warning' ?> badge-status">
                    <?= ($orderdata[0]->status == 'Y') ? 'Paid' : 'Pending' ?>
                </span>
            </span>
        </div>
    </div>

    <!-- Billing & Shipping -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h6 class="text-uppercase text-muted mb-2">Bill To</h6>
            <p class="mb-1"><strong><?= $address->name ?></strong></p>
            <p class="mb-1"><?= $address->address ?></p>
            <p class="mb-1"><?= $address->city_name ?>, <?= $address->state_name ?>, <?= $address->pincode ?></p>
            <p class="mb-0">Phone: <?= $address->mobile ?></p>
            
        </div>
        <div class="col-md-6 text-md-end">
            <h6 class="text-uppercase text-muted mb-2">Shipping To</h6>
            <p class="mb-1"><strong><?= $address->name ?></strong></p>
            <p class="mb-1"><?= $address->address ?></p>
            <p class="mb-1"><?= $address->city_name ?>, <?= $address->state_name ?>, <?= $address->pincode ?></p>
            <p class="mb-0">Phone: <?= $address->mobile ?></p>
        </div>
    </div>

    <!-- Items Table -->
    <div class="table-responsive mb-3">
        <table class="table table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Item</th>
                    <th class="text-end" style="width: 120px;">Price</th>
                    <th class="text-center" style="width: 90px;">Qty</th>
                    <th class="text-end" style="width: 120px;">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $sl = 1;
                $subtotal = 0;
                foreach ($orderdata as $item):
                   $price  = (float)$item->price;
$offer  = (float)$item->if_offer_per_price;
$tax    = isset($item->tax) ? (float)$item->tax : 0;

// discount calculation
$offer_price = $price - ($price * $offer / 100);

// price after tax
$final_price = $offer_price + ($offer_price * $tax / 100);

// subtotal per item (final price * qty)
$item_total = $final_price * $item->quantity;

// add to total
$subtotal += $item_total;

                ?>
                <tr>
                    <td><?= $sl++ ?></td>
                    <td>
                        <?= $item->product_name ?><br>
                        <span class="text-muted text-small">Variant: <?= $item->veriant ?></span>
                    </td>
                    <td class="text-end">₹ <?= number_format($item->price, 2) ?></td>
                    <td class="text-center"><?= $item->quantity ?></td>
                    <td class="text-end">₹ <?= number_format($item_total, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <!-- Totals -->
    <?php
   $shipping = $orderdata[0]->shipping ?? 0;
$tax_amount = 0;
$total_discount = 0;

foreach ($orderdata as $row) {
    $price  = (float)$row->price;
    $offer  = (float)$row->if_offer_per_price;
    $tax    = isset($row->tax) ? (float)$row->tax : 0;

    $offer_price = $price - ($price * $offer / 100);
    $final_price = $offer_price + ($offer_price * $tax / 100);

    $total_discount += ($price - $offer_price) * $row->quantity;
    $tax_amount += ($offer_price * $tax / 100) * $row->quantity;
}

$grand_total = $subtotal + $shipping;

    ?>

    <div class="row justify-content-end mt-3">
        <div class="col-md-6 col-lg-5">
            <table class="table mb-0">
                <tbody>
                 <tr>
    <td class="totals-label"><strong>Subtotal:</strong></td>
    <td class="text-end">₹ <?= number_format($subtotal, 2) ?></td>
</tr>
<tr>
    <td class="totals-label">Discount:</td>
    <td class="text-end">- ₹ <?= number_format($total_discount, 2) ?></td>
</tr>
<tr>
    <td class="totals-label">Tax:</td>
    <td class="text-end">₹ <?= number_format($tax_amount, 2) ?></td>
</tr>
<tr>
    <td class="totals-label">Shipping:</td>
    <td class="text-end">₹ <?= number_format($shipping, 2) ?></td>
</tr>
<tr>
    <th class="totals-label"><strong>Grand Total:</strong></th>
    <th class="text-end"><strong>₹ <?= number_format($grand_total, 2) ?></strong></th>
</tr>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-3">
        <p class="text-small mb-1"><strong>Notes:</strong></p>
        <p class="text-small mb-0">Thank you for shopping with us! For any queries about this invoice, contact support.</p>
        <p class="footer-text mb-0">This is a computer generated invoice and does not require a physical signature.</p>
    </div>

</div>

</body>
</html>
