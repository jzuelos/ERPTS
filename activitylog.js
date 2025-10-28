// Activity Log Display Handler
document.addEventListener('DOMContentLoaded', function() {
  
  // Toggle between Activity Logs and Login/Logout Logs
  const toggleBtn = document.getElementById('toggleLogsBtn');
  const activityLogs = document.getElementById('activitylogs');
  const loginLogs = document.getElementById('loginlogs');
  
  if (toggleBtn && activityLogs && loginLogs) {
    toggleBtn.addEventListener('click', function() {
      if (activityLogs.classList.contains('d-none')) {
        activityLogs.classList.remove('d-none');
        loginLogs.classList.add('d-none');
        toggleBtn.innerHTML = '<i class="fas fa-sign-in-alt me-1"></i> Login Activity';
      } else {
        activityLogs.classList.add('d-none');
        loginLogs.classList.remove('d-none');
        toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i> Activity Logs';
      }
    });
  }

  // Handle expand/collapse for MAIN activity text
  const toggleButtons = document.querySelectorAll('.toggle-btn');
  
  toggleButtons.forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const id = this.getAttribute('data-id');
      const fullText = this.getAttribute('data-full-text');
      const activityDiv = document.getElementById('activity' + id);
      const userDetailsDiv = document.getElementById('userdetails' + id);
      const parentRow = this.closest('tr');
      
      if (!activityDiv || !fullText) return;
      
      // Check if currently expanded
      const isExpanded = this.classList.contains('bi-caret-up-fill');
      
      if (isExpanded) {
        // Collapse - show only first line
        const lines = fullText.split('\n');
        activityDiv.textContent = lines[0];
        activityDiv.style.whiteSpace = 'nowrap';
        activityDiv.style.overflow = 'hidden';
        activityDiv.style.textOverflow = 'ellipsis';
        
        // Hide user details
        if (userDetailsDiv) {
          userDetailsDiv.classList.add('d-none');
        }
        
        // Reset table cell styling
        parentRow.querySelectorAll('td').forEach(td => {
          td.style.verticalAlign = 'middle';
        });
        
        // Change icon to down arrow
        this.classList.remove('bi-caret-up-fill');
        this.classList.add('bi-caret-down-fill');
      } else {
        // Expand - show full formatted text
        const formatted = formatActivityText(fullText);
        activityDiv.innerHTML = formatted;
        activityDiv.style.whiteSpace = 'normal';
        activityDiv.style.overflow = 'visible';
        activityDiv.style.textOverflow = 'clip';
        
        // Show user details
        if (userDetailsDiv) {
          userDetailsDiv.classList.remove('d-none');
        }
        
        // Adjust table cell styling for multiline content
        parentRow.querySelectorAll('td').forEach(td => {
          td.style.verticalAlign = 'top';
        });
        
        // Change icon to up arrow
        this.classList.remove('bi-caret-down-fill');
        this.classList.add('bi-caret-up-fill');
      }
    });
  });
  
  // Make MAIN activity text clickable
  document.querySelectorAll('.activity-text').forEach(function(text) {
    // Only handle main activity logs (not login logs)
    if (text.id.startsWith('activity') && !text.id.startsWith('activitylogin')) {
      text.addEventListener('click', function() {
        const id = this.id.replace('activity', '');
        const toggleBtn = document.querySelector(`.toggle-btn[data-id="${id}"]`);
        if (toggleBtn) {
          toggleBtn.click();
        }
      });
    }
  });

  // Make MAIN user names clickable
  document.querySelectorAll('.user-name-clickable').forEach(function(nameDiv) {
    nameDiv.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const toggleBtn = document.querySelector(`.toggle-btn[data-id="${id}"]`);
      if (toggleBtn) {
        toggleBtn.click();
      }
    });
  });

  // ============================================
  // LOGIN/LOGOUT TABLE HANDLERS
  // ============================================
  
  // Handle expand/collapse for LOGIN activity text
  const toggleButtonsLogin = document.querySelectorAll('.toggle-btn-login');
  
  toggleButtonsLogin.forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const id = this.getAttribute('data-id');
      const fullText = this.getAttribute('data-full-text');
      const activityDiv = document.getElementById('activitylogin' + id);
      const userDetailsDiv = document.getElementById('userdetailslogin' + id);
      const parentRow = this.closest('tr');
      
      if (!activityDiv || !fullText) return;
      
      // Check if currently expanded
      const isExpanded = this.classList.contains('bi-caret-up-fill');
      
      if (isExpanded) {
        // Collapse - show only first line
        const lines = fullText.split('\n');
        activityDiv.textContent = lines[0];
        activityDiv.style.whiteSpace = 'nowrap';
        activityDiv.style.overflow = 'hidden';
        activityDiv.style.textOverflow = 'ellipsis';
        
        // Hide user details
        if (userDetailsDiv) {
          userDetailsDiv.classList.add('d-none');
        }
        
        // Reset table cell styling
        parentRow.querySelectorAll('td').forEach(td => {
          td.style.verticalAlign = 'middle';
        });
        
        // Change icon to down arrow
        this.classList.remove('bi-caret-up-fill');
        this.classList.add('bi-caret-down-fill');
      } else {
        // Expand - show full formatted text
        const formatted = formatActivityText(fullText);
        activityDiv.innerHTML = formatted;
        activityDiv.style.whiteSpace = 'normal';
        activityDiv.style.overflow = 'visible';
        activityDiv.style.textOverflow = 'clip';
        
        // Show user details
        if (userDetailsDiv) {
          userDetailsDiv.classList.remove('d-none');
        }
        
        // Adjust table cell styling for multiline content
        parentRow.querySelectorAll('td').forEach(td => {
          td.style.verticalAlign = 'top';
        });
        
        // Change icon to up arrow
        this.classList.remove('bi-caret-down-fill');
        this.classList.add('bi-caret-up-fill');
      }
    });
  });

  // Make LOGIN activity text clickable
  document.querySelectorAll('.activity-text').forEach(function(text) {
    if (text.id.startsWith('activitylogin')) {
      text.addEventListener('click', function() {
        const id = this.id.replace('activitylogin', '');
        const toggleBtn = document.querySelector(`.toggle-btn-login[data-id="${id}"]`);
        if (toggleBtn) {
          toggleBtn.click();
        }
      });
    }
  });

  // Make LOGIN user names clickable
  document.querySelectorAll('.user-name-clickable-login').forEach(function(nameDiv) {
    nameDiv.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const toggleBtn = document.querySelector(`.toggle-btn-login[data-id="${id}"]`);
      if (toggleBtn) {
        toggleBtn.click();
      }
    });
  });
});

/**
 * Format activity text - converts \n to <br> and preserves formatting with horizontal lines
 */
function formatActivityText(text) {
  if (!text) return '';
  
  // Split by newlines and filter out completely empty lines
  const lines = text.split('\n').filter(line => line.trim() !== '');
  let result = '';
  
  lines.forEach((line, index) => {
    const trimmedLine = line.trim();
    const escaped = escapeHtml(trimmedLine);
    
    // Check if line starts with bullet point
    if (trimmedLine.startsWith('•')) {
      result += `<div class="activity-detail-line" style="padding: 6px 0; border-bottom: 1px solid #d1d5db;"><span style="display: inline-block; padding-left: 1em;">${escaped}</span></div>`;
    } else {
      result += `<div class="activity-detail-line" style="padding: 6px 0; border-bottom: 1px solid #d1d5db;">${escaped}</div>`;
    }
  });
  
  return result;
}

/**
 * Escape HTML special characters
 */
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

/**
 * Preserve scroll position on page navigation
 */
window.addEventListener('beforeunload', function() {
  sessionStorage.setItem('scrollPos', window.scrollY);
});

window.addEventListener('load', function() {
  const scrollPos = sessionStorage.getItem('scrollPos');
  if (scrollPos) {
    window.scrollTo(0, parseInt(scrollPos));
    sessionStorage.removeItem('scrollPos');
  }
});