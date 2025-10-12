// Enhanced Activity Log Formatter
document.addEventListener('DOMContentLoaded', function() {
  
  // Toggle between Activity Logs and Login/Logout Logs
  const toggleBtn = document.getElementById('toggleLogsBtn');
  const activityLogs = document.getElementById('activitylogs');
  const loginLogs = document.getElementById('loginlogs');
  
  if (toggleBtn) {
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

  // Format activity text for better readability FIRST
  formatActivityText();
  
  // THEN set up toggle functionality
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const id = this.getAttribute('data-id');
      const activityDiv = document.getElementById('activity' + id);
      
      if (!activityDiv) return;
      
      // Check if currently collapsed
      const isCollapsed = activityDiv.style.maxHeight === '1.5em' || !activityDiv.style.maxHeight;
      
      if (isCollapsed) {
        // Expand
        activityDiv.style.maxHeight = 'none';
        activityDiv.style.whiteSpace = 'normal';
        activityDiv.style.overflow = 'visible';
        this.classList.remove('bi-caret-down-fill');
        this.classList.add('bi-caret-up-fill');
      } else {
        // Collapse
        activityDiv.style.maxHeight = '1.5em';
        activityDiv.style.whiteSpace = 'nowrap';
        activityDiv.style.overflow = 'hidden';
        this.classList.remove('bi-caret-up-fill');
        this.classList.add('bi-caret-down-fill');
      }
    });
  });
  
  // Initialize all activity divs to collapsed state
  document.querySelectorAll('.activity-text').forEach(element => {
    element.style.maxHeight = '1.5em';
    element.style.overflow = 'hidden';
    element.style.whiteSpace = 'nowrap';
    element.style.textOverflow = 'ellipsis';
  });
});

function formatActivityText() {
  document.querySelectorAll('.activity-text').forEach(element => {
    let text = element.textContent.trim();
    
    // Format "Updated user account" logs
    if (text.includes('Updated user account')) {
      text = formatUserAccountUpdate(text);
    }
    // Format "Added new" logs
    else if (text.includes('Added new')) {
      text = formatAddedNew(text);
    }
    // Format other logs with "Changes:"
    else if (text.includes('Changes:')) {
      text = formatChanges(text);
    }
    
    element.innerHTML = text;
  });
}

function formatUserAccountUpdate(text) {
  // Extract main parts
  const parts = text.split('Changes:');
  if (parts.length < 2) return text;
  
  const header = parts[0].trim();
  const changes = parts[1].trim();
  
  // Extract user info from header (Username, Full Name, Role)
  const userInfoMatch = header.match(/Updated user account\s+(.+)/);
  const userInfo = userInfoMatch ? userInfoMatch[1].trim() : '';
  
  // Split user info into separate lines
  let formattedHeader = 'Updated user account';
  if (userInfo) {
    formattedHeader += '<br>' + userInfo.replace(/\s+(Username:|Full Name:|Role:)/g, '<br>$1');
  }
  
  // Split changes by bullet points
  const changeItems = changes.split('•').filter(item => item.trim());
  
  let formatted = `${formattedHeader}<br><br>Changes:`;
  
  changeItems.forEach(item => {
    const trimmed = item.trim();
    if (trimmed) {
      formatted += `<br>• ${trimmed}`;
    }
  });
  
  return formatted;
}

function formatAddedNew(text) {
  // Format "Added new" entries
  const parts = text.split(/Details:|with details:/i);
  if (parts.length < 2) return text;
  
  const header = parts[0].trim();
  const details = parts[1].trim();
  
  // Split details by commas or semicolons
  const detailItems = details.split(/,(?![^()]*\))/).filter(item => item.trim());
  
  let formatted = `${header}<br><br>Details:`;
  
  detailItems.forEach(item => {
    const trimmed = item.trim();
    if (trimmed) {
      formatted += `<br>• ${trimmed}`;
    }
  });
  
  return formatted;
}

function formatChanges(text) {
  // Generic changes formatter
  const parts = text.split('Changes:');
  if (parts.length < 2) return text;
  
  const header = parts[0].trim();
  const changes = parts[1].trim();
  
  // Try to split by common delimiters
  const changeItems = changes.split(/[•|]/).filter(item => item.trim());
  
  let formatted = `${header}<br><br>Changes:`;
  
  changeItems.forEach(item => {
    const trimmed = item.trim();
    if (trimmed) {
      formatted += `<br>• ${trimmed}`;
    }
  });
  
  return formatted;
}