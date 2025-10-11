<section class="container my-5" id="valuation-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">Valuation</h4>
    </div>

    <div class="card border-0 shadow p-5 rounded-3 bg-light">
        <table class="table table-borderless mt-4">
            <thead class="border-bottom">
                <tr>
                    <th scope="col">Total Value</th>
                    <th scope="col" class="text-center">Market Value</th>
                    <th scope="col" class="text-center">Assessed Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Land</td>
                    <td class="text-center">
                        <input type="text" class="form-control text-center" id="landMarketValue"
                               value="₱<?= number_format($totalMarketValue ?? 0, 2) ?>" disabled>
                    </td>
                    <td class="text-center">
                        <input type="text" class="form-control text-center" id="landAssessedValue"
                               value="₱<?= number_format($totalAssessedValue ?? 0, 2) ?>" disabled>
                    </td>
                </tr>
                <tr>
                    <td>Plants/Trees</td>
                    <td class="text-center">
                        <input type="text" class="form-control text-center" id="plantsMarketValue"
                               value="₱0.00" disabled>
                    </td>
                    <td class="text-center">
                        <input type="text" class="form-control text-center" id="plantsAssessedValue"
                               value="₱0.00" disabled>
                    </td>
                </tr>
                <tr class="border-top font-weight-bold">
                    <td><strong>Total</strong></td>
                    <td class="text-center">
                        <input type="text" class="form-control text-center fw-bold" id="totalMarketValue"
                               value="₱<?= number_format($totalMarketValue ?? 0, 2) ?>" disabled>
                    </td>
                    <td class="text-center">
                        <input type="text" class="form-control text-center fw-bold" id="totalAssessedValue"
                               value="₱<?= number_format($totalAssessedValue ?? 0, 2) ?>" disabled>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>