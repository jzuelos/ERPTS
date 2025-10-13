<?php
session_start();
$user_role = $_SESSION['user_type'] ?? 'user';
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css"> <!-- Custom CSS -->
  <link rel="stylesheet" href="FAAS.css">
  <title>Electronic Real Property Tax System</title>
</head>
<!--Header-->
 <?php include 'header.php'; ?>

 <div class="container mt-4">

  <?php
  $p_id = isset($_GET['p_id']) ? htmlspecialchars($_GET['p_id']) : '';
  ?>
  <a href="FAAS.php?id=<?=$p_id; ?>" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-arrow-left"></i> Back
  </a>
</div>
  <!-- Carousel Wrapper -->
  <div class="container my-5" id="property-carousel-section">
    <div id="propertyCarousel" class="carousel slide" data-bs-ride="false">
      <div class="carousel-inner">

        <!-- PLANTS AND TREES Section -->
        <div class="carousel-item active">
          <section class="container my-5" id="plants-trees-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="section-title">
                PLANTS AND TREES
              </h4>
            </div>

            <div class="card border-0 shadow p-4 rounded-3">
              <!-- Quick Actions Row -->
              <div class="row mb-4">
                <?php
                // Get the property ID from the current URL (e.g., FAAS.php?id=140)
                $p_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
                ?>
                <div class="col-md-6 mb-3">
                  <a href="<?= ($is_active == 1) ? 'Property/AddPnTrees.php?p_id=' . $p_id : '#' ?>"
                    class="btn w-100 py-2 text-white text-decoration-none <?= ($is_active == 0) ? 'disabled' : '' ?>"
                    style="background-color: #379777; border-color: #2e8266; pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
                    <i class="fas fa-plus-circle me-2"></i>Add Plants/Trees
                  </a>
                </div>
              </div>

              <!-- Toggle Section -->
              <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
                <span class="fw-bold me-3">Show/Hide</span>
                <div class="form-check form-switch m-0">
                  <input class="form-check-input" type="checkbox" id="showPlantsToggle" checked style="margin-left: 0;">
                </div>
              </div>

              <!-- Value Table -->
              <div class="table-responsive">
                <table class="table table-borderless">
                  <thead>
                    <tr>
                      <th class="text-muted">Market Value</th>
                      <th class="text-muted">Assessed Value</th>
                      <th class="text-muted">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>$100,000.00</td>
                      <td>$50,000.00</td>
                      <td class="text-center">
                        <div class="btn-group" role="group">
                          <a href="EditPnT.php" class="btn btn-sm btn-primary" title="Edit">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <a href="#" class="btn btn-sm btn-danger ml-3" title="Delete">
                            <i class="bi bi-trash"></i>
                          </a>
                        </div>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
        </div>
        <!-- BUILDING AND IMPROVEMENT Section -->
<div class="carousel-item">
  <section class="container my-5" id="building-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">BUILDING AND IMPROVEMENT</h4>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <!-- Quick Actions Row -->
      <div class="row mb-4">
        <?php
        $p_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
        ?>
        <div class="col-md-6 mb-3">
          <a href="<?= ($is_active == 1) ? "Building.php?p_id=$p_id" : '#' ?>"
            class="btn w-100 py-2 text-white text-decoration-none <?= ($is_active == 0) ? 'disabled' : '' ?>"
            style="background-color: #379777; border-color: #2e8266; pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
            <i class="fas fa-plus-circle me-2"></i>Add Building
          </a>
        </div>
      </div>

      <!-- Toggle Section -->
      <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
        <span class="fw-bold me-3">Show/Hide</span>
        <div class="form-check form-switch m-0">
          <input class="form-check-input" type="checkbox" id="showBuildingToggle" checked style="margin-left: 0;">
        </div>
      </div>

      <!-- Value Table -->
      <div class="table-responsive" id="buildingTableContainer">
        <table class="table table-borderless text-center">
          <thead class="border-bottom border-2">
            <tr>
              <th class="bold">Building Name</th>
              <th class="bold">Floor Area (sq m)</th>
              <th class="bold">Market Value</th>
              <th class="bold">Assessed Value</th>
              <th class="bold" style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($buildingRecords)): ?>
              <?php foreach ($buildingRecords as $record): ?>
                <tr class="border-bottom border-3">
                  <td><?= htmlspecialchars($record['building_name']) ?></td>
                  <td><?= htmlspecialchars($record['floor_area']) ?></td>
                  <td><?= number_format($record['market_value'], 2) ?></td>
                  <td><?= isset($record['assess_value']) ? number_format($record['assess_value'], 2) : '0.00' ?></td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="Building_Edit.php?p_id=<?= urlencode($p_id); ?>&building_id=<?= urlencode($record['building_id']); ?>"
                        class="btn btn-sm btn-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="<?= ($is_active == 1)
                        ? 'print-building.php?p_id=' . urlencode($p_id) . '&building_id=' . urlencode($record['building_id'])
                        : '#' ?>"
                        class="btn btn-sm btn-secondary ml-3 <?= ($is_active == 0) ? 'disabled' : '' ?>"
                        title="View" target="_blank"
                        style="pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
                        <i class="bi bi-printer"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">No records found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>

<!-- MACHINERIES Section -->
<div class="carousel-item">
  <section class="container my-5" id="machineries-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="section-title">MACHINERIES</h4>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
      <!-- Quick Actions Row -->
      <div class="row mb-4">
        <div class="col-md-6 mb-3">
          <a href="<?= ($is_active == 1) ? "Machineries.php?p_id=$p_id" : '#' ?>"
            class="btn w-100 py-2 text-white text-decoration-none <?= ($is_active == 0) ? 'disabled' : '' ?>"
            style="background-color: #379777; border-color: #2e8266; pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
            <i class="fas fa-plus-circle me-2"></i>Add Machinery
          </a>
        </div>
      </div>

      <!-- Toggle Section -->
      <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
        <span class="fw-bold me-3">Show/Hide</span>
        <div class="form-check form-switch m-0">
          <input class="form-check-input" type="checkbox" id="showMachineryToggle" checked style="margin-left: 0;">
        </div>
      </div>

      <!-- Value Table -->
      <div class="table-responsive" id="machineryTableContainer">
        <table class="table table-borderless text-center">
          <thead class="border-bottom border-2">
            <tr>
              <th class="bold">Machinery Name</th>
              <th class="bold">Quantity</th>
              <th class="bold">Market Value</th>
              <th class="bold">Assessed Value</th>
              <th class="bold" style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($machineryRecords)): ?>
              <?php foreach ($machineryRecords as $record): ?>
                <tr class="border-bottom border-3">
                  <td><?= htmlspecialchars($record['machinery_name']) ?></td>
                  <td><?= htmlspecialchars($record['quantity']) ?></td>
                  <td><?= number_format($record['market_value'], 2) ?></td>
                  <td><?= isset($record['assess_value']) ? number_format($record['assess_value'], 2) : '0.00' ?></td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="Machinery_Edit.php?p_id=<?= urlencode($p_id); ?>&machinery_id=<?= urlencode($record['machinery_id']); ?>"
                        class="btn btn-sm btn-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="<?= ($is_active == 1)
                        ? 'print-machinery.php?p_id=' . urlencode($p_id) . '&machinery_id=' . urlencode($record['machinery_id'])
                        : '#' ?>"
                        class="btn btn-sm btn-secondary ml-3 <?= ($is_active == 0) ? 'disabled' : '' ?>"
                        title="View" target="_blank"
                        style="pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
                        <i class="bi bi-printer"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">No records found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
        <!-- Put Code In Additional Carousel Items Here-->
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
        <i class="fas fa-chevron-left"></i>
        <span class="visually-hidden">Previous</span>
      </button>

      <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
        <i class="fas fa-chevron-right"></i>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
 
<!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>
  
  <!-- Load External Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb5g5/5hb5g5/5hb5g5/5hb5g5/5hb5g5" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="LAND.js"></script>
</body>

</html>