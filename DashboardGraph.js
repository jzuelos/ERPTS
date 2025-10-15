 const ctxDashboard = document.getElementById('dashboardChart').getContext('2d');

  new Chart(ctxDashboard, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [
        {
          label: 'Land',
          data: [totalLand, totalLand + 2, totalLand - 1, totalLand + 3, totalLand + 1, totalLand], // demo trend
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: true,
          tension: 0.3
        },
        {
          label: 'Plant/Trees',
          data: [8, 14, 10, 18, 20, 15], // dummy
          borderColor: 'rgba(153, 102, 255, 1)',
          backgroundColor: 'rgba(153, 102, 255, 0.2)',
          fill: true,
          tension: 0.3
        },
        {
          label: 'Machineries',
          data: [totalProperties, totalProperties + 1, totalProperties - 1, totalProperties + 3, totalProperties, totalProperties - 2],
          borderColor: 'rgba(255, 159, 64, 1)',
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          fill: true,
          tension: 0.3
        },
        {
          label: 'Building',
          data: [15, 22, 18, 25, 28, 23], // dummy
          borderColor: 'rgba(255, 206, 86, 1)',
          backgroundColor: 'rgba(255, 206, 86, 0.2)',
          fill: true,
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            font: { weight: 'bold' }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Count',
            font: { weight: 'bold' }
          },
          ticks: {
            font: { weight: 'bold' }
          }
        },
        x: {
          title: {
            display: true,
            text: 'Month',
            font: { weight: 'bold' }
          },
          ticks: {
            font: { weight: 'bold' }
          }
        }
      }
    }
  });