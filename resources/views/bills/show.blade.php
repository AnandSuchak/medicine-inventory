<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $bill->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-align: left;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="invoice-box bg-white">
        <div class="flex justify-end mb-6 no-print">
            <a href="{{ route('bills.index') }}" class="text-sm text-gray-700 hover:text-black mr-4">Back to List</a>
            <button onclick="window.print()" class="text-sm text-gray-700 hover:text-black">Print Invoice</button>
        </div>
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h1 class="text-3xl font-bold text-gray-800">{{ $companyDetails->company_name ?? 'Your Company' }}</h1>
                            </td>
                            <td class="text-right">
                                <h2 class="font-bold text-xl">Tax Invoice</h2>
                                Invoice #: {{ $bill->id }}<br>
                                Created: {{ $bill->bill_date->format('F j, Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>From:</strong><br>
                                {{ $companyDetails->company_name ?? 'Your Company LLC' }}<br>
                                {{ $companyDetails->address ?? '123 Example St, City, Country' }}<br>
                                GSTIN: {{ $companyDetails->gstin ?? 'N/A' }}
                            </td>
                            <td class="text-right">
                                <strong>To:</strong><br>
                                {{ $bill->customer->shop_name }}<br>
                                {{ $bill->customer->address }}<br>
                                GSTIN/PAN: {{ $bill->customer->gst ?? $bill->customer->pan }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <table class="items-table">
            <thead>
                <tr class="heading">
                    <td>Medicine</td>
                    <td class="text-center">Batch</td>
                    <td class="text-center">Expiry</td>
                    <td class="text-center">Qty</td>
                    <td class="text-right">Rate</td>
                    <td class="text-right">Amount</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $subTotal = 0;
                    $gstTotal = 0;
                @endphp
                @foreach($bill->billItems as $item)
                    @php
                        $amount = $item->quantity * $item->price;
                        $subTotal += $amount;
                        // Assuming GST is stored on the medicine itself
                        $gstRate = $item->medicine->gst;
                        $gstAmount = $amount * ($gstRate / 100);
                        $gstTotal += $gstAmount;
                    @endphp
                    <tr class="item">
                        <td>
                            {{ $item->medicine->name }}<br>
                            <small class="text-gray-500">HSN: {{ $item->medicine->hsn_code ?? 'N/A' }}</small>
                        </td>
                        <td class="text-center">{{ $item->medicineBatch->batch_no ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->medicineBatch ? $item->medicineBatch->expiry_date->format('m/y') : 'N/A' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ number_format($amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="flex justify-end mt-4">
            <div class="w-full max-w-sm">
                <table class="w-full">
                    <tr class="total">
                        <td class="text-right font-bold">Subtotal:</td>
                        <td class="text-right">{{ number_format($subTotal, 2) }}</td>
                    </tr>
                    @if($gstTotal > 0)
                        <tr class="total">
                            @php
                                // Example of splitting GST into CGST and SGST
                                $cgst = $gstTotal / 2;
                                $sgst = $gstTotal / 2;
                            @endphp
                            <td class="text-right font-bold">CGST:</td>
                            <td class="text-right">{{ number_format($cgst, 2) }}</td>
                        </tr>
                        <tr class="total">
                            <td class="text-right font-bold">SGST:</td>
                            <td class="text-right">{{ number_format($sgst, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total text-lg">
                        <td class="text-right font-bold">Grand Total:</td>
                        <td class="text-right">{{ number_format($bill->net_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-8 text-sm text-gray-700">
            <strong>Amount in words:</strong> {{ numberToWords($bill->net_amount) }} Rupees Only.
        </div>

        <div class="mt-12 pt-8 border-t text-center text-sm text-gray-500">
            <p>Thank you for your business!</p>
            <p>{{ $companyDetails->company_name ?? 'Your Company' }}</p>
        </div>
    </div>
</body>
</html>

@php
function numberToWords($number) {
    // A simple implementation for converting number to words for the invoice.
    // This can be replaced with a more robust package if needed.
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'forty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . numberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= numberToWords($remainder);
            }
            break;
    }
    
    return ucwords($string);
}
@endphp