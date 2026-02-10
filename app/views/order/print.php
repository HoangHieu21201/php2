<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #<?= $order['id'] ?></title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 40px;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #009981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company h1 {
            color: #009981;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .invoice-info h2 {
            margin: 0;
            font-size: 28px;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
        }

        th {
            text-align: left;
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            font-size: 16px;
            color: #009981;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            text-align: center;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #009981; color: #fff; border: none; border-radius: 5px; font-weight: bold;">In Hóa Đơn</button>
    </div>

    <div class="header">
        <div class="company">
            <h1>CỬA HÀNG THỜI TRANG</h1>
            <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
            <p>Hotline: 1900 1234</p>
        </div>
        <div class="invoice-info">
            <h2>HÓA ĐƠN</h2>
            <p>Mã đơn: #<?= $order['id'] ?></p>
            <p>Ngày: <?= date('d/m/Y', strtotime($order['created_at'])) ?></p>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3>Thông tin khách hàng</h3>
        <p><strong>Người nhận:</strong> <?= $order['recipient_name'] ?></p>
        <p><strong>Điện thoại:</strong> <?= $order['recipient_phone'] ?></p>
        <p><strong>Địa chỉ:</strong> <?= $order['recipient_address'] ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th class="text-right">Đơn giá</th>
                <th class="text-right">SL</th>
                <th class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($details as $item): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <?= $item['product_name'] ?>
                        <br><small style="color: #666">Biến thể: #<?= $item['product_variant_id'] ?></small>
                    </td>
                    <td class="text-right"><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                    <td class="text-right"><?= $item['quantity'] ?></td>
                    <td class="text-right"><strong><?= number_format($item['total_price'], 0, ',', '.') ?>đ</strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">Tạm tính:</td>
                <td class="text-right"><?= number_format($order['subtotal'], 0, ',', '.') ?>đ</td>
            </tr>
            <?php if ($order['discount_amount'] > 0): ?>
                <tr>
                    <td colspan="4" class="text-right">Giảm giá:</td>
                    <td class="text-right">-<?= number_format($order['discount_amount'], 0, ',', '.') ?>đ</td>
                </tr>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="4" class="text-right">Tổng cộng:</td>
                <td class="text-right"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div style="width: 40%">
            <p><strong>Người mua hàng</strong></p>
            <br><br><br>
            <p>(Ký, ghi rõ họ tên)</p>
        </div>
        <div style="width: 40%">
            <p><strong>Người bán hàng</strong></p>
            <br><br><br>
            <p>(Ký, đóng dấu)</p>
        </div>
    </div>
</body>

</html>