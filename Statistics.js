 //Line Chart JS
 const ctx = document.getElementById('propertyChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
          {
            label: 'Land',
            data: [12, 15, 10, 18, 20, 25, 22],
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
            tension: 0.3
          },
          {
            label: 'Plant/Trees',
            data: [8, 12, 9, 14, 16, 18, 15],
            borderColor: 'rgba(255, 159, 64, 1)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            fill: true,
            tension: 0.3
          },
          {
            label: 'Machineries',
            data: [5, 7, 6, 10, 12, 9, 11],
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            fill: true,
            tension: 0.3
          },
          {
            label: 'Building',
            data: [20, 22, 18, 25, 28, 30, 27],
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
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
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Count',
              font: { weight: 'bold',
                size: 14 }
            }
          },
          x: {
            title: {
              display: true,
              text: 'Month',
                font: { weight: 'bold',
                size: 14}
            }
          }
        }
      }
    });

    //Create Chart JS 
const propertyChart = new Chart(ctx, {
  type: 'line',
  data: chartData,
  options: {
    responsive: true,
    plugins: { legend: { position: 'top' } },
    scales: {
      y: {
        beginAtZero: true,
        title: { display: true, text: 'Count', font: { weight: 'bold', size: 14 } }
      },
      x: {
        title: { display: true, text: 'Date', font: { weight: 'bold', size: 14 } }
      }
    }
  }
});

// Filter function
function filterChart() {
  const start = document.getElementById('startDate').value;
  const end = document.getElementById('endDate').value;

  if (!start || !end) {
    alert('Please select both start and end dates.');
    return;
  }
  const filteredLabels = [];
  const filteredDatasets = chartData.datasets.map(ds => ({ ...ds, data: [] }));

  chartData.labels.forEach((label, index) => {
    if (label >= start && label <= end) {
      filteredLabels.push(label);
      filteredDatasets.forEach((ds, i) => {
        ds.data.push(chartData.datasets[i].data[index]);
      });
    }
  });

  propertyChart.data.labels = filteredLabels;
  propertyChart.data.datasets = filteredDatasets;
  propertyChart.update();
}

// Export as Image
document.getElementById('exportBtn').addEventListener('click', function() {
  const link = document.createElement('a');
  link.href = propertyChart.toBase64Image(); // Chart.js built-in
  link.download = 'property-chart.png';     // File name
  link.click();
});