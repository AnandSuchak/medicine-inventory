@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center mb-4" style="color:#00838f;">Generate Bill</h3>

    <form action="{{ route('bills.store') }}" method="POST" id="billForm">
        @csrf

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th>Medicine</th>
                    <th>Batch Code</th>
                    <th>PTR</th>
                    <th>Price</th>
                    <th>GST (%)</th>
                    <th>Expiry Date</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Tax</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="bill-body">
                <tr class="bill-row">
                    <td>
                        <select name="medicines[0][medicine_id]" class="form-control medicine-select" required>
                            <option value="">-- Select Medicine --</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="medicines[0][batch_id]" class="batch-id">
                    </td>
                    <td><input type="text" class="form-control batch-code" readonly></td>
                    <td><input type="text" class="form-control ptr" readonly></td>
                    <td><input type="text" class="form-control price" readonly></td>
                    <td><input type="text" class="form-control gst" readonly></td>
                    <td><input type="text" class="form-control expiry" readonly></td>
                    <td><input type="number" name="medicines[0][quantity]" class="form-control qty" min="1" value="1" required></td>
                    <td><input type="text" class="form-control subtotal" readonly></td>
                    <td><input type="text" class="form-control tax" readonly></td>
                    <td><input type="text" class="form-control total" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-end fw-bold">Totals:</td>
                    <td><input type="text" id="total_subtotal" class="form-control" readonly></td>
                    <td><input type="text" id="total_gst" class="form-control" readonly></td>
                    <td><input type="text" id="grand_total" class="form-control" readonly></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <button type="button" id="addRow" class="btn btn-info" style="background-color:#00838f; border:none;">Add Medicine</button>
            <button type="submit" class="btn btn-success" style="background-color:#00838f; border:none;">Generate Bill</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const batchesByMedicineId = @json($batchesByMedicine);
        const billBody = document.getElementById('bill-body');
        let rowCount = 1;

        function updateRow(row) {
            const medicineSelect = row.querySelector('.medicine-select');
            const qtyInput = row.querySelector('.qty');
            const batchIdInput = row.querySelector('.batch-id');
            const batchCodeInput = row.querySelector('.batch-code');
            const ptrInput = row.querySelector('.ptr');
            const priceInput = row.querySelector('.price');
            const gstInput = row.querySelector('.gst');
            const expiryInput = row.querySelector('.expiry');
            const subtotalInput = row.querySelector('.subtotal');
            const taxInput = row.querySelector('.tax');
            const totalInput = row.querySelector('.total');

            const medicineId = medicineSelect.value;
            const batch = batchesByMedicineId[medicineId]?.[0]; // Use the earliest-expiry batch

            if (!batch) {
                batchIdInput.value = '';
                batchCodeInput.value = '';
                ptrInput.value = '';
                priceInput.value = '';
                gstInput.value = '';
                expiryInput.value = '';
                subtotalInput.value = '';
                taxInput.value = '';
                totalInput.value = '';
                updateTotals();
                return;
            }

            batchIdInput.value = batch.batch_id;
            batchCodeInput.value = batch.batch_code || '';
            ptrInput.value = parseFloat(batch.ptr || 0).toFixed(2);
            priceInput.value = parseFloat(batch.price || 0).toFixed(2);
            gstInput.value = parseFloat(batch.gst_percent || 0).toFixed(2);
            expiryInput.value = batch.expiry_date || '';

            const qty = parseInt(qtyInput.value) || 0;
            const subtotal = (parseFloat(batch.ptr) || 0) * qty;
            const tax = subtotal * ((parseFloat(batch.gst_percent) || 0) / 100);
            const total = subtotal + tax;

            subtotalInput.value = subtotal.toFixed(2);
            taxInput.value = tax.toFixed(2);
            totalInput.value = total.toFixed(2);

            updateTotals();
        }

        function updateTotals() {
            let totalSubtotal = 0;
            let totalGST = 0;
            let grandTotal = 0;

            document.querySelectorAll('.bill-row').forEach(row => {
                const subtotal = parseFloat(row.querySelector('.subtotal').value) || 0;
                const tax = parseFloat(row.querySelector('.tax').value) || 0;
                const total = parseFloat(row.querySelector('.total').value) || 0;

                totalSubtotal += subtotal;
                totalGST += tax;
                grandTotal += total;
            });

            document.getElementById('total_subtotal').value = totalSubtotal.toFixed(2);
            document.getElementById('total_gst').value = totalGST.toFixed(2);
            document.getElementById('grand_total').value = grandTotal.toFixed(2);
        }

        function bindRowEvents(row) {
            const medicineSelect = row.querySelector('.medicine-select');
            const qtyInput = row.querySelector('.qty');
            const removeBtn = row.querySelector('.remove-row');

            medicineSelect.addEventListener('change', () => updateRow(row));
            qtyInput.addEventListener('input', () => updateRow(row));
            removeBtn.addEventListener('click', () => {
                if (document.querySelectorAll('.bill-row').length > 1) {
                    row.remove();
                    updateTotals();
                } else {
                    alert('At least one medicine row is required.');
                }
            });
        }

        document.querySelectorAll('.bill-row').forEach(row => {
            bindRowEvents(row);
            updateRow(row);
        });

        document.getElementById('addRow').addEventListener('click', function () {
            const firstRow = billBody.querySelector('.bill-row');
            const newRow = firstRow.cloneNode(true);

            // Clear values
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('.medicine-select').selectedIndex = 0;
            newRow.querySelector('.qty').value = 1;

            // Update input names
            newRow.querySelector('.medicine-select').setAttribute('name', `medicines[${rowCount}][medicine_id]`);
            newRow.querySelector('.batch-id').setAttribute('name', `medicines[${rowCount}][batch_id]`);
            newRow.querySelector('.qty').setAttribute('name', `medicines[${rowCount}][quantity]`);

            billBody.appendChild(newRow);
            bindRowEvents(newRow);
            rowCount++;
        });
    });
</script>
@endsection
