$(function () {
  $.ajax({
    url: '/admin/user-image-count',
    method: 'GET',
    success: function (data) {
      const options = {
        series: [{
          name: "Image Targets",
          data: data.counts
        }],
        chart: {
          type: 'bar',
          height: 320,
          fontFamily: "inherit",
          foreColor: "#adb0bb"
        },
        plotOptions: {
          bar: {
            borderRadius: 4,
            horizontal: false,
            columnWidth: '50%',
          }
        },
        dataLabels: {
          enabled: false
        },
        xaxis: {
          categories: data.users,
          labels: {
            rotate: -45
          }
        },
        colors: ["var(--bs-primary)"],
        tooltip: {
          theme: 'dark'
        }
      };

      var chart = new ApexCharts(document.querySelector("#traffic-overview"), options);
      chart.render();
    },
    error: function (xhr) {
      console.error("Gagal mengambil data chart:", xhr.responseText);
    }
  });
});
