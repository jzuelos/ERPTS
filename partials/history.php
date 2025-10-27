<?php
// Fetch historical snapshots from owner_audit_log
$history_records = [];
if ($property_id) {
  $history_stmt = $conn->prepare("
        SELECT 
            log_id,
            action,
            owner_id,
            user_id,
            `tax-dec_id` as tax_dec_id,
            details,
            created_at
        FROM owner_audit_log 
        WHERE property_id = ? AND action = 'Snapshot'
        ORDER BY created_at DESC
    ");
  $history_stmt->bind_param("i", $property_id);
  $history_stmt->execute();
  $history_result = $history_stmt->get_result();

  while ($row = $history_result->fetch_assoc()) {
    $snapshot_data = json_decode($row['details'], true);
    if ($snapshot_data) {
      $history_records[] = [
        'log_id' => $row['log_id'],
        'created_at' => $row['created_at'],
        'tax_dec_id' => $row['tax_dec_id'],
        'snapshot' => $snapshot_data
      ];
    }
  }
  $history_stmt->close();
}
?>

<section class="container my-5" id="history-section">
  <!-- Section Title -->
  <div class="mb-4">
    <h4 class="section-title">History</h4>
  </div>

  <div class="card border-0 shadow p-4 rounded-3 bg-white">
    <!-- Preceding Label -->
    <div class="mb-3">
      <span class="fw-bold text-uppercase text-muted">Preceding Ownership Records</span>
    </div>

    <?php if (empty($history_records)): ?>
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        No historical ownership transfer records found for this property.
      </div>
    <?php else: ?>
      <!-- History Table -->
      <div class="table-responsive" id="historyTableContainer">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th class="fw-bold">OD ID</th>
              <th class="fw-bold">Transfer Date</th>
              <th class="fw-bold">ARP Number</th>
              <th class="fw-bold">Owner(s)</th>
              <th class="fw-bold">Location</th>
              <th class="fw-bold text-center">Land Area (sq m)</th>
              <th class="fw-bold text-center" style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($history_records as $record):
              $snapshot = $record['snapshot'];
              $rpu_dec = $snapshot['rpu_dec'] ?? null;
              $p_info = $snapshot['p_info'] ?? null;
              $land_records = $snapshot['land'] ?? [];

              // Calculate total land area
              $total_land_area = 0;
              if (!empty($land_records)) {
                foreach ($land_records as $land) {
                  $total_land_area += ($land['area'] ?? 0);
                }
              }

              // Format location
              $location = '';
              if ($p_info) {
                $location_parts = [];
                if (!empty($p_info['street']))
                  $location_parts[] = $p_info['street'];
                if (!empty($p_info['barangay']))
                  $location_parts[] = $p_info['barangay'];
                if (!empty($p_info['city']))
                  $location_parts[] = $p_info['city'];
                if (!empty($p_info['province']))
                  $location_parts[] = $p_info['province'];
                $location = implode(', ', $location_parts);
              }

              // Get owner names at time of snapshot
              $owner_names = [];
              $owner_stmt = $conn->prepare("
                  SELECT DISTINCT o.own_fname, o.own_mname, o.own_surname
                  FROM owner_audit_log oal
                  JOIN owners_tb o ON oal.owner_id = o.own_id
                  WHERE oal.log_id = ? AND oal.action = 'Snapshot'
              ");
              $owner_stmt->bind_param("i", $record['log_id']);
              $owner_stmt->execute();
              $owner_result = $owner_stmt->get_result();
              while ($owner_row = $owner_result->fetch_assoc()) {
                $owner_names[] = trim(($owner_row['own_surname'] ?? '') . ', ' .
                  ($owner_row['own_fname'] ?? '') . ' ' .
                  ($owner_row['own_mname'] ?? ''));
              }
              $owner_stmt->close();
              ?>
              <tr>
                <td><?= htmlspecialchars($record['log_id']) ?></td>
                <td><?= date('M d, Y', strtotime($record['created_at'])) ?></td>
                <td><?= htmlspecialchars($rpu_dec['arp_no'] ?? 'N/A') ?></td>
                <td>
                  <?php if (!empty($owner_names)): ?>
                    <?php foreach ($owner_names as $name): ?>
                      <?= htmlspecialchars($name) ?><br>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <span class="text-muted">No owner data</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($location ?: 'N/A') ?></td>
                <td class="text-center"><?= number_format($total_land_area, 2) ?></td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm px-3"
                    onclick="viewHistoricalSnapshot(<?= $record['log_id'] ?>)">
                    View &raquo;
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Historical Snapshot Modal -->
<div class="modal fade" id="historicalSnapshotModal" tabindex="-1" aria-labelledby="historicalSnapshotModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="historicalSnapshotModalLabel">
          <i class="bi bi-clock-history me-2"></i>History
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="snapshotContent">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function viewHistoricalSnapshot(logId) {
    const modal = new bootstrap.Modal(document.getElementById('historicalSnapshotModal'));
    const content = document.getElementById('snapshotContent');

    // Show loading spinner
    content.innerHTML = `
    <div class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  `;

    modal.show();

    // Fetch snapshot data
    fetch('get_historical_snapshot.php?log_id=' + logId)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          displaySnapshot(data.snapshot, data.created_at);
        } else {
          content.innerHTML = `<div class="alert alert-danger">Error loading snapshot: ${data.message}</div>`;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        content.innerHTML = `<div class="alert alert-danger">Failed to load historical data</div>`;
      });
  }

  function displaySnapshot(snapshot, createdAt) {
    const content = document.getElementById('snapshotContent');
    const rpu_dec = snapshot.rpu_dec || {};
    const rpu_idnum = snapshot.rpu_idnum || {};
    const p_info = snapshot.p_info || {};
    const land = snapshot.land || [];

    let html = `
    <div class="alert alert-info mb-4">
      <i class="bi bi-info-circle me-2"></i>
      <strong>Creation Date:</strong> ${new Date(createdAt).toLocaleString('en-US', {
      year: 'numeric', month: 'long', day: 'numeric',
      hour: '2-digit', minute: '2-digit'
    })}
    </div>
    
    <!-- RPU Declaration -->
    <div class="card mb-3">
      <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bi bi-file-text me-2"></i>Tax Declaration (RPU)</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>ARP Number:</strong> ${rpu_dec.arp_no || 'N/A'}</p>
            <p><strong>Tax Year:</strong> ${rpu_dec.tax_year || 'N/A'}</p>
            <p><strong>Provincial Assessor:</strong> ${rpu_dec.pro_assess || 'N/A'}</p>
            <p><strong>Provincial Date:</strong> ${rpu_dec.pro_date || 'N/A'}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Municipal Assessor:</strong> ${rpu_dec.mun_assess || 'N/A'}</p>
            <p><strong>Municipal Date:</strong> ${rpu_dec.mun_date || 'N/A'}</p>
            <p><strong>Total Property Value:</strong> ₱${parseFloat(rpu_dec.total_property_value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- RPU Identification -->
    <div class="card mb-3">
      <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bi bi-card-text me-2"></i>RPU Identification</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>PIN:</strong> ${rpu_idnum.pin || 'N/A'}</p>
            <p><strong>ARP:</strong> ${rpu_idnum.arp || 'N/A'}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Taxability:</strong> ${rpu_idnum.taxability || 'N/A'}</p>
            <p><strong>Effectivity:</strong> ${rpu_idnum.effectivity || 'N/A'}</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Property Information -->
    <div class="card mb-3">
      <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bi bi-house me-2"></i>Property Information</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>House Number:</strong> ${p_info.house_no || 'N/A'}</p>
            <p><strong>Street:</strong> ${p_info.street || 'N/A'}</p>
            <p><strong>Barangay:</strong> ${p_info.barangay || 'N/A'}</p>
          </div>
          <div class="col-md-6">
            <p><strong>City:</strong> ${p_info.city || 'N/A'}</p>
            <p><strong>Province:</strong> ${p_info.province || 'N/A'}</p>
            <p><strong>Land Area:</strong> ${p_info.land_area || 'N/A'} sq.m</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Land Records -->
    <div class="card mb-3">
      <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Land Records (${land.length})</h6>
      </div>
      <div class="card-body">
        ${land.length === 0 ? '<p class="text-muted">No land records</p>' : ''}
  `;

    land.forEach((landRecord, index) => {
      html += `
      <div class="border rounded p-3 mb-3">
        <h6 class="text-primary">Land Record #${index + 1}</h6>
        <div class="row">
          <div class="col-md-6">
            <p><strong>OCT/TCT:</strong> ${landRecord.oct_no || 'N/A'}</p>
            <p><strong>Classification:</strong> ${landRecord.classification || 'N/A'}</p>
            <p><strong>Sub-Class:</strong> ${landRecord.sub_class || 'N/A'}</p>
            <p><strong>Area:</strong> ${landRecord.area || 'N/A'} sq.m</p>
          </div>
          <div class="col-md-6">
            <p><strong>Unit Value:</strong> ₱${parseFloat(landRecord.unit_value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
            <p><strong>Market Value:</strong> ₱${parseFloat(landRecord.market_value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
            <p><strong>Assessment Level:</strong> ${landRecord.assess_lvl || 'N/A'}%</p>
            <p><strong>Assessed Value:</strong> ₱${parseFloat(landRecord.assess_value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
          </div>
        </div>
      </div>
    `;
    });

    html += `
      </div>
    </div>
  `;

    content.innerHTML = html;
  }
</script>

<style>
  #historicalSnapshotModal .modal-body p {
    margin-bottom: 0.5rem;
  }

  #historicalSnapshotModal .card {
    border: 1px solid #dee2e6;
  }

  #historicalSnapshotModal .card-header {
    border-bottom: 2px solid #dee2e6;
  }
</style>