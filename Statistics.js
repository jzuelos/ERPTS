const ctx1 = document.getElementById('propertyChart').getContext('2d');
  const ctx2 = document.getElementById('userChart').getContext('2d');
  const ctx3 = document.getElementById('auditChart').getContext('2d');

  //Plugins
  // White BG
const whiteBackground = {
  id: 'whiteBackground',
  beforeDraw: (chart) => {
    const ctx = chart.ctx;
    ctx.save();
    ctx.globalCompositeOperation = 'destination-over';
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, chart.width, chart.height);
    ctx.restore();
  }
};

// Add date 
const addDatePlugin = {
  id: 'addDatePlugin',
  beforeDraw: (chart) => {
    const { ctx, chartArea: { left, right, bottom } } = chart;
    ctx.save();
    ctx.font = '12px Arial';
    ctx.fillStyle = 'black';
    ctx.textAlign = 'right';

    const today = new Date();
    const formattedDate = today.getFullYear() + "-" +
                          String(today.getMonth() + 1).padStart(2, '0') + "-" +
                          String(today.getDate()).padStart(2, '0');

    // Draw date at bottom-right corner
    ctx.fillText(`Generated on: ${formattedDate}`, right, bottom - 5);
    ctx.restore();
  }
};


// Property Statistics (Line Chart)
let propertyChart = new Chart(ctx1, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    datasets: [
      {
        label: 'Land',
        data: [12, 15, 10, 18, 20, 25, 22],
        borderColor: 'rgba(75,192,192,1)',
        backgroundColor: 'rgba(75,192,192,0.2)',
        fill: true,
        tension: 0.3
      },
      {
        label: 'Plant/Trees',
        data: [8, 12, 9, 14, 16, 18, 15],
        borderColor: 'rgba(255,159,64,1)',
        backgroundColor: 'rgba(255,159,64,0.2)',
        fill: true,
        tension: 0.3
      },
      {
        label: 'Machineries',
        data: [5, 7, 6, 10, 12, 9, 11],
        borderColor: 'rgba(153,102,255,1)',
        backgroundColor: 'rgba(153,102,255,0.2)',
        fill: true,
        tension: 0.3
      },
      {
        label: 'Building',
        data: [20, 22, 18, 25, 28, 30, 27],
        borderColor: 'rgba(255,99,132,1)',
        backgroundColor: 'rgba(255,99,132,0.2)',
        fill: true,
        tension: 0.3
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      title: {
        display: true,
        text: 'Property Statistics',
        font: { size: 18, weight: 'bold' }
      }
    }
  },
  plugins: [whiteBackground, addDatePlugin],
});

// User Activity (Bar Chart)
let userChart = new Chart(ctx2, {
  type: 'bar',
  data: {
    labels: ['Users', 'Transaction Logs', 'Login Counts', 'Transactions Done'],
    datasets: [{
      label: 'User Activity',
      data: [120, 300, 450, 280],
      backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: {
        display: true,
        text: 'User Activity',
        font: { size: 18, weight: 'bold' }
      }
    },
    scales: {
      y: { beginAtZero: true, title: { display: true, text: 'Count' } }
    }
  },
  plugins: [whiteBackground, addDatePlugin]
});

// Audit Trail (Bar Chart)
let auditChart = new Chart(ctx3, {
  type: 'bar',
  data: {
    labels: ['Property Module', 'User Management', 'Reports', 'Transactions', 'Audit Trail'],
    datasets: [{
      label: 'Modules Accessed',
      data: [80, 50, 70, 60, 90],
      backgroundColor: ['#9966FF','#36A2EB','#FF9F40','#4BC0C0','#FF6384']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Audit Trail - Modules Accessed',
        font: { size: 18, weight: 'bold' }
      }
    },
    scales: {
      y: { beginAtZero: true, title: { display: true, text: 'Access Frequency' } }
    }
  },
  plugins: [whiteBackground, addDatePlugin]
});


  // Chart Switcher
  document.getElementById('chartSelector').addEventListener('change', function () {
    const selected = this.value;

    // Hide all canvases
    document.getElementById('propertyChart').style.display = 'none';
    document.getElementById('userChart').style.display = 'none';
    document.getElementById('auditChart').style.display = 'none';

    // Show only selected chart
    if (selected === 'property') {
      document.getElementById('propertyChart').style.display = 'block';
      propertyChart.update();
    } else if (selected === 'user') {
      document.getElementById('userChart').style.display = 'block';
      userChart.update();
    } else if (selected === 'audit') {
      document.getElementById('auditChart').style.display = 'block';
      auditChart.update();
    }
  });

  //Export JS 
document.getElementById('exportBtn').addEventListener('click', function() {
  const selected = document.getElementById('chartSelector').value;
  let chart;
  let title;

  if (selected === 'property') {
    chart = propertyChart;
    title = "Property_Statistics";
  } else if (selected === 'user') {
    chart = userChart;
    title = "User_Activity";
  } else {
    chart = auditChart;
    title = "Audit_Trail";
  }

  // Get today's date (YYYY-MM-DD)
  const today = new Date();
  const formattedDate = today.getFullYear() + "-" +
                        String(today.getMonth() + 1).padStart(2, '0') + "-" +
                        String(today.getDate()).padStart(2, '0');

  // Export as JPG with white background
  const link = document.createElement('a');
  link.href = chart.toBase64Image("image/jpeg", 1.0);
  link.download = `${title}_${formattedDate}.jpg`;  // e.g., Property_Statistics_2025-09-16.jpg
  link.click();
});

