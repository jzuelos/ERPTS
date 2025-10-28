 <?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
  }

  // prevent caching
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  include 'database.php';
  $conn = Database::getInstance();

  // Get filter inputs
  $start_date = $_GET['start_date'] ?? '';
  $end_date = $_GET['end_date'] ?? '';

  // Pagination setup
  $limit = 10;

  // --- MAIN LOGS (excluding login/logout) ---
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;

  // Build WHERE clause
  $where = [];
  $params = [];
  if ($start_date) {
    $where[] = "DATE(a.log_time) >= ?";
    $params[] = $start_date;
  }
  if ($end_date) {
    $where[] = "DATE(a.log_time) <= ?";
    $params[] = $end_date;
  }
  $where[] = "a.action NOT LIKE 'User logged in%' AND a.action NOT LIKE 'Failed login attempt%' AND a.action != 'Logged out of the system'";
  $where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

  // Count total rows
  $stmt_count = $conn->prepare("SELECT COUNT(*) as total 
                                FROM activity_log a
                                JOIN users u ON a.user_id = u.user_id
                                $where_sql");
  if ($params) $stmt_count->bind_param(str_repeat("s", count($params)), ...$params);
  $stmt_count->execute();
  $total_rows = $stmt_count->get_result()->fetch_assoc()['total'];
  $total_pages = ceil($total_rows / $limit);

  // Fetch logs
  $stmt = $conn->prepare("SELECT a.log_id, a.action, a.log_time, 
                                CONCAT(u.first_name, ' ', u.last_name) AS fullname,
                                u.email, u.user_type, u.contact_number
                        FROM activity_log a
                        JOIN users u ON a.user_id = u.user_id
                        $where_sql
                        ORDER BY a.log_time DESC
                        LIMIT ? OFFSET ?");
  $params_main = $params;
  $params_main[] = $limit;
  $params_main[] = $offset;
  $types_main = str_repeat("s", count($params)) . "ii";
  $stmt->bind_param($types_main, ...$params_main);
  $stmt->execute();
  $result = $stmt->get_result();

  // --- LOGIN/LOGOUT LOGS ---
  $page_login = isset($_GET['page_login']) && is_numeric($_GET['page_login']) ? (int)$_GET['page_login'] : 1;
  $offset_login = ($page_login - 1) * $limit;

  $where_login = [];
  $params_login = [];
  if ($start_date) {
    $where_login[] = "DATE(a.log_time) >= ?";
    $params_login[] = $start_date;
  }
  if ($end_date) {
    $where_login[] = "DATE(a.log_time) <= ?";
    $params_login[] = $end_date;
  }
  $where_login[] = "(a.action LIKE 'User logged in%' OR a.action LIKE 'Failed login attempt%' OR a.action = 'Logged out of the system')";
  $where_sql_login = $where_login ? "WHERE " . implode(" AND ", $where_login) : "";

  // Count login logs
  $stmt_count_login = $conn->prepare("SELECT COUNT(*) as total 
                                      FROM activity_log a
                                      JOIN users u ON a.user_id = u.user_id
                                      $where_sql_login");
  if ($params_login) $stmt_count_login->bind_param(str_repeat("s", count($params_login)), ...$params_login);
  $stmt_count_login->execute();
  $total_rows_login = $stmt_count_login->get_result()->fetch_assoc()['total'];
  $total_pages_login = ceil($total_rows_login / $limit);

  // Fetch login logs
  $stmt_login = $conn->prepare("SELECT a.log_id, a.action, a.log_time, 
                                      CONCAT(u.first_name, ' ', u.last_name) AS fullname,
                                      u.email, u.user_type, u.contact_number
                                FROM activity_log a
                                JOIN users u ON a.user_id = u.user_id
                                $where_sql_login
                                ORDER BY a.log_time DESC
                                LIMIT ? OFFSET ?");
  $params_login2 = $params_login;
  $params_login2[] = $limit;
  $params_login2[] = $offset_login;
  $types_login = str_repeat("s", count($params_login)) . "ii";
  $stmt_login->bind_param($types_login, ...$params_login2);
  $stmt_login->execute();
  $result_login = $stmt_login->get_result();

  ?>

 <!doctype html>
 <html lang="en">

 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <link rel="stylesheet" href="main_layout.css">
   <link rel="stylesheet" href="header.css">
   <link rel="stylesheet" href="activitylog.css">
   <title>Activity Log</title>
 </head>

 <body class="d-flex flex-column min-vh-100">
   <?php include 'header.php'; ?>

   <main class="container my-6">
     <section class="table-container p-5">
       <!-- Combined Header + Filters + Table -->
       <div class="container mt-4">
         <div class="row align-items-center">
           <!-- Back button and title -->
           <div class="col-md-8 d-flex align-items-center">
             <a href="Home.php" class="btn btn-outline-secondary me-3">
               <i class="fas fa-arrow-left"></i> Back
             </a>
             <h3 class="mb-0 fw-bold text-success">Activity Log</h3>
           </div>

           <!-- Filters -->
           <form method="get" class="row g-3 align-items-end">
             <div class="col-lg-2 col-md-4">
               <input type="date" name="start_date" class="form-control"
                 value="<?= htmlspecialchars($start_date) ?>" placeholder="Start Date">
             </div>
             <div class="col-lg-2 col-md-4">
               <input type="date" name="end_date" class="form-control"
                 value="<?= htmlspecialchars($end_date) ?>" placeholder="End Date">
             </div>
             <div class="col-lg-1 col-md-4">
               <button type="submit" class="btn btn-success w-100">
                 <i class="fas fa-calendar-check me-1"></i> Go
               </button>
             </div>

             <div class="col-lg-2 col-md-6">
               <input type="text" id="filter_value" class="form-control"
                 placeholder="Filter by Activity, User, or No.">
             </div>
             <div class="col-lg-1 col-md-3">
               <button type="button" id="resetFilterBtn" class="btn btn-outline-secondary w-100">
                 <i class="fas fa-rotate-left"></i>
               </button>
             </div>
             <div class="col-lg-2 col-md-3 ms-auto">
               <button type="button" id="printBtn" class="btn btn-success w-100" disabled>
                 <i class="fas fa-print me-1"></i> Print
               </button>
             </div>
             <div class="col-lg-2 col-md-12 ms-auto">
               <button type="button" id="toggleLogsBtn" class="btn btn-primary w-100">
                 <i class="fas fa-sign-in-alt me-1"></i> Login Activity
               </button>
             </div>

           </form>
         </div>

         <div class="filter-divider mb-4"></div>

         <!-- Activity Logs Table -->
         <div id="activitylogs">
           <table class="table table-striped table-hover align-middle text-start">
             <thead>
               <tr>
                 <th style="width: 8%">No.</th>
                 <th style="width: 42%">Activity</th>
                 <th style="width: 20%">Responsible Person/User</th>
                 <th style="width: 30%">Date and Time</th>
               </tr>
             </thead>
             <tbody>
               <?php
                if ($result->num_rows > 0) {
                  $no = $offset + 1;
                  while ($row = $result->fetch_assoc()) {
                    $activity_raw = $row['action'];
                    $fullname = htmlspecialchars($row['fullname']);
                    $log_time = htmlspecialchars($row['log_time']);

                    $lines = explode("\n", $activity_raw);
                    $first_line = htmlspecialchars(trim($lines[0]), ENT_QUOTES, 'UTF-8');
                    $full_activity = htmlspecialchars($activity_raw, ENT_QUOTES, 'UTF-8');

                    $email = htmlspecialchars($row['email'] ?? 'N/A');
                    $role = htmlspecialchars($row['user_type'] ?? 'N/A');
                    $contact_number = htmlspecialchars($row['contact_number'] ?? 'N/A');

                    echo "
                    <tr>
                      <td><strong>{$no}</strong></td>
                      <td>
                        <div id='activity{$no}' 
                            class='activity-text text-truncate' 
                            style='cursor: pointer;'>
                          {$first_line}
                        </div>
                      </td>
                      <td>
                        <div class='user-info-container'>
                          <div class='user-name-only user-name-clickable' data-id='{$no}' style='cursor: pointer;'>{$fullname}</div>
                          <div id='userdetails{$no}' class='user-details d-none'>
                            <small class='text-muted d-block'>
                              <i class='bi bi-person-badge'></i> {$role}
                            </small>
                            <small class='text-muted d-block'>
                              <i class='bi bi-envelope'></i> {$email}
                            </small>
                            <small class='text-muted d-block'>
                              <i class='bi bi-telephone'></i> {$contact_number}
                            </small>
                          </div>
                        </div>
                      </td>
                      <td style='white-space: nowrap;'>
                        <span>{$log_time}</span>
                        <i class='bi bi-caret-down-fill toggle-btn' 
                          data-id='{$no}'
                          data-full-text='{$full_activity}'></i>
                      </td>
                    </tr>";
                    $no++;
                  }
                } else {
                  echo "<tr><td colspan='4' class='text-center text-muted py-4'>No activity logs found.</td></tr>";
                }
                ?>
             </tbody>
           </table>

           <!-- Pagination -->
           <div class="pagination-container">
             <nav aria-label="Page navigation">
               <div class="d-flex justify-content-center align-items-center gap-3">
                 <?php if ($page > 1): ?>
                   <a class="btn btn-outline-primary btn-sm"
                     href="?log_type=activity&page=<?= $page - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">
                     <i class="fas fa-chevron-left me-1"></i> Prev
                   </a>
                 <?php else: ?>
                   <button class="btn btn-outline-secondary btn-sm" disabled>
                     <i class="fas fa-chevron-left me-1"></i> Prev
                   </button>
                 <?php endif; ?>

                 <span class="fw-semibold text-muted">Page <?= $page ?> of <?= $total_pages ?></span>

                 <?php if ($page < $total_pages): ?>
                   <a class="btn btn-outline-primary btn-sm"
                     href="?log_type=activity&page=<?= $page + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">
                     Next <i class="fas fa-chevron-right ms-1"></i>
                   </a>
                 <?php else: ?>
                   <button class="btn btn-outline-secondary btn-sm" disabled>
                     Next <i class="fas fa-chevron-right ms-1"></i>
                   </button>
                 <?php endif; ?>
               </div>
             </nav>
           </div>
         </div>

         <!-- Login/Logout Logs Table -->
         <div id="loginlogs" class="d-none">
           <table class="table table-striped table-hover align-middle text-start">
             <thead>
               <tr>
                 <th style="width: 8%">No.</th>
                 <th style="width: 42%">Activity</th>
                 <th style="width: 20%">User</th>
                 <th style="width: 30%">Date and Time</th>
               </tr>
             </thead>
             <tbody>
               <?php
                if ($result_login->num_rows > 0) {
                  $no = $offset_login + 1;
                  while ($row = $result_login->fetch_assoc()) {
                    $activity_raw = $row['action'];
                    $fullname = htmlspecialchars($row['fullname']);
                    $log_time = htmlspecialchars($row['log_time']);

                    $lines = explode("\n", $activity_raw);
                    $first_line = htmlspecialchars(trim($lines[0]), ENT_QUOTES, 'UTF-8');
                    $full_activity = htmlspecialchars($activity_raw, ENT_QUOTES, 'UTF-8');

                    $email = htmlspecialchars($row['email'] ?? 'N/A');
                    $role = htmlspecialchars($row['user_type'] ?? 'N/A');
                    $contact_number = htmlspecialchars($row['contact_number'] ?? 'N/A');

                    echo "
          <tr>
            <td><strong>{$no}</strong></td>
            <td>
              <div id='activitylogin{$no}' 
                  class='activity-text text-truncate' 
                  style='cursor: pointer;'>
                {$first_line}
              </div>
            </td>
            <td>
              <div class='user-info-container'>
                <div class='user-name-only user-name-clickable-login' data-id='{$no}' style='cursor: pointer;'>{$fullname}</div>
                <div id='userdetailslogin{$no}' class='user-details d-none'>
                  <small class='text-muted d-block'>
                    <i class='bi bi-person-badge'></i> {$role}
                  </small>
                  <small class='text-muted d-block'>
                    <i class='bi bi-envelope'></i> {$email}
                  </small>
                  <small class='text-muted d-block'>
                    <i class='bi bi-telephone'></i> {$contact_number}
                  </small>
                </div>
              </div>
            </td>
            <td style='white-space: nowrap;'>
              <span>{$log_time}</span>
              <i class='bi bi-caret-down-fill toggle-btn-login' 
                data-id='{$no}'
                data-full-text='{$full_activity}'></i>
            </td>
          </tr>";
                    $no++;
                  }
                } else {
                  echo "<tr><td colspan='4' class='text-center text-muted py-4'>No login/logout logs found.</td></tr>";
                }
                ?>
             </tbody>
           </table>

           <!-- Pagination -->
           <div class="pagination-container">
             <nav aria-label="Page navigation">
               <div class="d-flex justify-content-center align-items-center gap-3">
                 <?php if ($page_login > 1): ?>
                   <a class="btn btn-outline-primary btn-sm"
                     href="?log_type=login&page_login=<?= $page_login - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">
                     <i class="fas fa-chevron-left me-1"></i> Prev
                   </a>
                 <?php else: ?>
                   <button class="btn btn-outline-secondary btn-sm" disabled>
                     <i class="fas fa-chevron-left me-1"></i> Prev
                   </button>
                 <?php endif; ?>

                 <span class="fw-semibold text-muted">Page <?= $page_login ?> of <?= $total_pages_login ?></span>

                 <?php if ($page_login < $total_pages_login): ?>
                   <a class="btn btn-outline-primary btn-sm"
                     href="?log_type=login&page_login=<?= $page_login + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">
                     Next <i class="fas fa-chevron-right ms-1"></i>
                   </a>
                 <?php else: ?>
                   <button class="btn btn-outline-secondary btn-sm" disabled>
                     Next <i class="fas fa-chevron-right ms-1"></i>
                   </button>
                 <?php endif; ?>
               </div>
             </nav>
           </div>
         </div>
     </section>
   </main>


   <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
     <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
       <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
     </div>
   </footer>

   <script>
     document.addEventListener('DOMContentLoaded', () => {
       // Elements
       const filterInput = document.getElementById('filter_value');
       const resetBtn = document.getElementById('resetFilterBtn');
       const printBtn = document.getElementById('printBtn');
       const form = document.querySelector('form[method="get"]'); // your filter form
       const goBtn = form ? form.querySelector('button[type="submit"], input[type="submit"]') : null;

       console.log('[Filters] script init', {
         foundForm: !!form,
         foundPrint: !!printBtn,
         foundFilter: !!filterInput
       });

       // Safety: ensure printBtn exists
       if (!printBtn) {
         console.warn('[Filters] printBtn not found (#printBtn).');
       } else {
         printBtn.disabled = true; // initial state
       }

       // Utility: enable/disable print button
       function setPrintEnabled(enabled) {
         if (!printBtn) return;
         printBtn.disabled = !enabled;
         console.log('[Filters] setPrintEnabled ->', enabled);
       }

       // Check URL params and inputs to decide if print should be enabled
       function evaluatePrintStateFromURLAndInputs() {
         try {
           const params = new URLSearchParams(window.location.search);
           const urlHasDates = params.has('start_date') && params.has('end_date');
           const urlHasAnyDate = params.has('start_date') || params.has('end_date');
           const textHasValue = filterInput && filterInput.value.trim() !== '';
           const sessionFlag = sessionStorage.getItem('filtersApplied') === '1';

           const shouldEnable = urlHasDates || textHasValue || sessionFlag;
           console.log('[Filters] evaluatePrintState', {
             urlHasDates,
             urlHasAnyDate,
             textHasValue,
             sessionFlag,
             shouldEnable
           });
           setPrintEnabled(shouldEnable);

           // If session flag was used to enable, remove it so future reloads behave naturally
           if (sessionFlag && !textHasValue && !urlHasDates) {
             sessionStorage.removeItem('filtersApplied');
             console.log('[Filters] session flag cleared');
           }
         } catch (err) {
           console.error('[Filters] evaluate error', err);
         }
       }

       // If the user types into the live filter input, update print state immediately
       if (filterInput) {
         filterInput.addEventListener('input', () => {
           const hasText = filterInput.value.trim() !== '';
           // enable only if there's text (we don't auto-enable based on typing alone if there are no dates)
           setPrintEnabled(hasText);
           // don't set sessionStorage here — we only set it when the user actually applies (submits) filters
         });
       }

       // When the form is submitted (Go button), set a session flag before navigation so reload knows filters were applied
       if (form) {
         form.addEventListener('submit', (ev) => {
           try {
             // Determine if filters are actually being applied (dates or text)
             const startVal = form.querySelector('input[name="start_date"]')?.value;
             const endVal = form.querySelector('input[name="end_date"]')?.value;
             const textVal = filterInput?.value?.trim();

             const applying = !!((startVal && endVal) || textVal);
             console.log('[Filters] form submit detected', {
               startVal,
               endVal,
               textVal,
               applying
             });

             if (applying) {
               // remember across reload so we can enable print after server render
               sessionStorage.setItem('filtersApplied', '1');
               // provide visual feedback before reload
               setPrintEnabled(true);
             } else {
               // ensure disabled if nothing applied
               sessionStorage.removeItem('filtersApplied');
               setPrintEnabled(false);
             }
             // allow submit to proceed
           } catch (err) {
             console.error('[Filters] form submit handler error', err);
           }
         }, {
           passive: true
         });
       } else {
         console.warn('[Filters] form element not found — cannot detect Go submit.');
       }

       // Reset button behavior: clear inputs, clear session flag, and reload cleaned URL
       if (resetBtn) {
         resetBtn.addEventListener('click', (e) => {
           e.preventDefault();
           console.log('[Filters] Reset clicked');

           if (filterInput) filterInput.value = '';
           const sd = document.querySelector('input[name="start_date"]');
           const ed = document.querySelector('input[name="end_date"]');
           if (sd) sd.value = '';
           if (ed) ed.value = '';

           // clear session flag used for enabling print
           sessionStorage.removeItem('filtersApplied');

           // Clean url params that we know are filter-related
           const url = new URL(window.location.href);
           url.searchParams.delete('start_date');
           url.searchParams.delete('end_date');
           url.searchParams.delete('page');
           // preserve log_type if present
           const logType = url.searchParams.get('log_type');
           let target = url.pathname;
           if (logType) target += '?log_type=' + logType;

           console.log('[Filters] navigating to cleaned URL:', target);
           window.location.assign(target);
         });
       } else {
         console.warn('[Filters] reset button not found (#resetFilterBtn).');
       }

       // Print button behaviour (just window.print here)
       if (printBtn) {
         printBtn.addEventListener('click', (e) => {
           e.preventDefault();
           console.log('[Filters] Print clicked — opening print view...');
           window.open('printlogs.php', '_blank'); // open in new tab
         });
       }



       // On initial load evaluate URL / session to decide print state
       evaluatePrintStateFromURLAndInputs();

       // Extra: if the page is loaded with filters in the URL but your server renders empty filter inputs,
       // the session flag helps bridge that. We already set/cleared session flag on submit/reset.
     });
   </script>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   <script src="activitylog.js"></script>
 </body>

 </html>