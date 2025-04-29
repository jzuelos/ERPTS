<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Tracking Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="../Admin-Page-2.php" class="nav-brand">
        <i class="fas fa-home"></i> Home
      </a>
      <div class="nav-title">Transaction Dashboard</div>
    </div>
  </nav>

  <!--Main Content-->
  <div class="container">
    <h1><i class="fas fa-exchange-alt"></i> Transaction Dashboard</h1>

    <div class="dashboard">
      <div class="card">
        <div>Total Transactions</div>
        <div id="totalCount">0</div>
      </div>
      <div class="card">
        <div>In Progress</div>
        <div id="inProgressCount">0</div>
      </div>
      <div class="card">
        <div>Completed</div>
        <div id="completedCount">0</div>
      </div>
    </div>

    <button class="btn btn-add" onclick="openModal()">
      <i class="fas fa-plus"></i> Add Transaction
    </button>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Transaction</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="transactionTable">
        <!-- Rows will be injected here -->
      </tbody>
    </table>

    <div class="recent-activity">
      <h3><i class="fas fa-history"></i> Recent Transaction Activity</h3>
      <div id="activityLog">
        <!-- Logs appear here -->
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal" id="transactionModal">
    <div class="modal-content">
      <h3 id="modalTitle"><i class="fas fa-exchange-alt"></i> Add Transaction</h3>
      <input type="text" id="nameInput" placeholder="Name">
      <input type="text" id="transactionInput" placeholder="Transaction Description">
      <select id="statusInput">
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
      </select>
      <div class="modal-actions">
        <button class="btn btn-add" onclick="saveTransaction()">
          <i class="fas fa-save"></i> Save
        </button>
        <button class="btn btn-cancel" onclick="closeModal()">
          <i class="fas fa-times"></i> Cancel
        </button>
      </div>
    </div>
  </div>

  <script src="dboard.js"></script>
</body>
</html>