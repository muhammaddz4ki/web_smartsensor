var ctxSensor = document.getElementById('sensorChart')?.getContext('2d');
if (ctxSensor) {
  new Chart(ctxSensor, {
    type: 'bar',
    data: {
      labels: window.sensorLabels || [],
      datasets: [{
        label: 'Sensor Value (cm)',
        data: window.sensorData || [],
        backgroundColor: 'rgba(54, 185, 204, 0.7)'
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}

