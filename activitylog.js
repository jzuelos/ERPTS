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
        toggleBtn.innerHTML = '<i class="fas fa-sign-in-alt me-1"></i> Log In/Log Out Logs';
      } else {
        activityLogs.classList.add('d-none');
        loginLogs.classList.remove('d-none');
        toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i> Activity Logs';
      }
    });
  }

  // Handle expand/collapse for activity text
  const toggleButtons = document.querySelectorAll('.toggle-btn');
  
  toggleButtons.forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const id = this.getAttribute('data-id');
      const fullText = this.getAttribute('data-full-text');
      const activityDiv = document.getElementById('activity' + id);
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
  
  // Make activity text clickable too
  document.querySelectorAll('.activity-text').forEach(function(text) {
    text.addEventListener('click', function() {
      const id = this.id.replace('activity', '');
      const toggleBtn = document.querySelector(`.toggle-btn[data-id="${id}"]`);
      if (toggleBtn) {
        toggleBtn.click();
      }
    });
  });
});

/**
 * Format activity text - converts \n to <br> and preserves formatting
 */
function formatActivityText(text) {
  if (!text) return '';
  
  // Split by newlines
  const lines = text.split('\n');
  let result = '';
  
  for (let i = 0; i < lines.length; i++) {
    const line = lines[i].trim();
    
    if (line === '') {
      // Empty line - add extra spacing
      result += '<br>';
    } else {
      // Add line break before each line (except first)
      if (result !== '') {
        result += '<br>';
      }
      
      // Escape HTML
      const escaped = escapeHtml(line);
      
      // Check if line starts with bullet point
      if (line.startsWith('•')) {
        // Add indentation for bullet points
        result += '<span style="display: inline-block; padding-left: 1em;">' + escaped + '</span>';
      } else {
        result += escaped;
      }
    }
  }
  
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

