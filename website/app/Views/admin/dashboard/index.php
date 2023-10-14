<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $database[3]->jumlah ?></h3>

                <p>Peserta PKL</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?= base_url() ?>/am" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $database[4]->jumlah ?></h3>

                <p>Asal Peserta</p>
            </div>
            <div class="icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <a href="<?= base_url() ?>/ak" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $database[5]->jumlah ?></h3>

                <p>Tim Peserta</p>
            </div>
            <div class="icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <a href="<?= base_url() ?>/at" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $database[6]->jumlah ?></h3>

                <p>Karyawan</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?= base_url() ?>/akr" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
<hr class="mt-0">
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar pr-3"></i>Peserta PKL</h3>
            </div>
            <div class="card-body">
                <canvas id="myChartBulan" width="800" height="500"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie pr-3"></i>Tim Peserta</h3>
            </div>
            <div class="card-body">
                <canvas id="myChartTahun" width="800" height="660"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie pr-3"></i>Pembimbing</h3>
            </div>
            <div class="card-body">
                <canvas id="myChartTahun3" width="800" height="660"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie pr-3"></i>Asal Peserta</h3>
            </div>
            <div class="card-body">
                <canvas id="myChartTahun2" width="800" height="660"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar pr-3"></i>Status</h3>
            </div>
            <div class="card-body pt-0" style="padding-left:18px;">
                <!-- <canvas id="tim" width="800" height="195"></canvas> -->
                <canvas id="status" width="800" height="390"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar pr-3"></i>Karyawan</h3>
            </div>
            <div class="card-body pt-0" style="padding-left:18px;">
                <canvas id="karyawan" width="800" height="260"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar pr-3"></i>Agama</h3>
            </div>
            <div class="card-body">
                <canvas id="myChartBulan2" width="800" height="260"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center" style="padding-top:28px;padding-bottom:28px;">
                <p style="font-size:14px;">" Tanpa kamu, sistem informasi yang kita gunakan tidak akan berfungsi dengan baik. Teruslah melakukan pekerjaanmu dengan baik. "</p>
                <p class="mb-0"><b>-- Politeknik Negeri Malang --</b></p>
            </div>
        </div>
    </div>
</div>
<hr class="mt-0">
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
          <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
<script>
		const ctx = document.getElementById('myChartBulan');
    var datatahun = [];
    var datalakilaki = [];
    var dataperempuan = [];
    var datawarna = [];
    <?php foreach($database[70] as $warna){ ?>
      datawarna.push('<?php echo $warna; ?>');
    <?php } ?>

    <?php foreach($database[67] as $tahun){ ?>
      datatahun.push('<?php echo $tahun; ?>');
    <?php } ?>

    <?php foreach($database[65] as $lakilaki){ ?>
      datalakilaki.push('<?php echo $lakilaki['jumlah']; ?>');
    <?php } ?>

    <?php foreach($database[66] as $perempuan){ ?>
      dataperempuan.push('<?php echo $perempuan['jumlah']; ?>');
    <?php } ?>
	  
		new Chart(ctx, {
		  type: 'bar',
		  data: {
              labels: datatahun,
			datasets: [{
                label: 'Laki-laki',
                data: datalakilaki,
                backgroundColor: datawarna[0]
			},
			{
                label: 'Perempuan',
                data: dataperempuan,
                backgroundColor: datawarna[1]
			}]
		  },
		  plugins: [ChartDataLabels],
		  options: {
			  plugins:{
          legend: {
            position:'bottom',
					display: true,
          align:'center'
				},
				datalabels:{
					anchor:'end',
					align:'top',
				}  
			  },
			scales: {
			  y: {
				beginAtZero: true
			  }
			}
		  }
		});
</script>

<script>
		const ctx40 = document.getElementById('status');
    var datawarna = [];
    <?php foreach($database[72] as $warna){ ?>
      datawarna.push('<?php echo $warna; ?>');
    <?php } ?>

    var pesertaaktif = <?= $database[61][0]['jumlah'] ?>;
    var pesertatidakaktif = <?= $database[61][1]['jumlah'] ?>;
    var timaktif = <?= $database[62][0]['jumlah'] ?>;
    var timtidakaktif = <?= $database[62][1]['jumlah'] ?>;
    var dataaktif = [pesertaaktif,timaktif];
    var datatikakaktif = [pesertatidakaktif,timtidakaktif];

		new Chart(ctx40, {
      type: 'bar',
		  data: {
			labels: ['Peserta','Tim'],
			datasets: [
            {
                label: 'Aktif',
                data: dataaktif,
                backgroundColor: datawarna[0]
            },
            {
                label: 'Tidak Aktif',
                data: datatikakaktif,
                backgroundColor: datawarna[1]
            }
        ]
		  },
		  plugins: [ChartDataLabels],
		  options: {
        scales: {
            y: {
                ticks: {
                    maxRotation: 90,
                    minRotation: 90,
                }
            }
        },
			  plugins:{
          legend: {
            position:'bottom',
					display: true,
          align:'center'
				},
				datalabels:{
					anchor:'end',
					align:'right',
				}  
			  },
			indexAxis: 'y',
		  }
		});
</script>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.js'></script>
            <script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'Asia/Jakarta',
            locale: 'id',
            firstDay: 1,
            weekNumberCalculation: 'ISO',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: false
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                <?php 
                //melakukan koneksi ke database
                $koneksi = mysqli_connect('localhost', 'root', '', 'simaptapkl');
                //mengambil data dari tabel jadwal
                $data = mysqli_query($koneksi,'select * from tabel_libur_nasional');
                //melakukan looping
                while($d = mysqli_fetch_array($data)){     
                ?>
                {
                    title: '<?php echo $d['KEGIATAN_LBR']; ?>', //menampilkan title dari tabel
                    start: '<?php echo $d['TANGGAL_LBR']; ?>',
                    rendering: 'background',
                    color: '#cc0000',
                    textColor: '#ffffff'
                },
                <?php } ?>
            ],
            dayCellDidMount: function(info) {
                var date = new Date(info.date);
                if (date.getDay() === 0 || date.getDay() === 6) {
                    info.el.style.backgroundColor = 'rgba(204,0,0, 0.1)';
                }
            },
            selectOverlap: function (event) {
                return event.rendering === 'background';
            }
        });
        calendar.render();
    });
</script>

<script>
		const ctx41 = document.getElementById('myChartBulan2');
	  var xValues = ["Budha", "Hindu", "Islam", "Konghucu", "Kristen"];
    var datawarna = [];
    var datakaryawan = [];
    var datamahasiswa = [];
    <?php foreach($database[73] as $warna){ ?>
      datawarna.push('<?php echo $warna; ?>');
    <?php } ?>
    <?php foreach($database[77] as $agama){ ?>
      datakaryawan.push('<?php echo $agama['jumlah']; ?>');
    <?php } ?>
    <?php foreach($database[76] as $agama){ ?>
      datamahasiswa.push('<?php echo $agama['jumlah']; ?>');
    <?php } ?>

		new Chart(ctx41, {
		  type: 'bar',
		  data: {
              labels: xValues,
			datasets: [{
                label: 'Peserta PKL',
                data: datamahasiswa,
                backgroundColor: datawarna[0]
			},
			{
                label: 'Karyawan',
                data: datakaryawan,
                backgroundColor: datawarna[1]
			}]
		  },
		  plugins: [ChartDataLabels],
		  options: {
			  plugins:{
          legend: {
            position:'bottom',
					display: true,
          align:'center'
				},
				datalabels:{
					anchor:'end',
					align:'top',
				}  
			  },
			scales: {
			  y: {
				beginAtZero: true
			  }
			}
		  }
		});
</script>

<script>
		const ctx1 = document.getElementById('karyawan');
    var datawarna = [];
    <?php foreach($database[71] as $warna){ ?>
      datawarna.push('<?php echo $warna; ?>');
    <?php } ?>

		new Chart(ctx1, {
		  type: 'bar',
		  data: {
			labels: ['Kelamin'],
			datasets: [
            {
                label: 'Laki-laki',
                data: [<?= $database[60][0]['jumlah'] ?>],
                backgroundColor: datawarna[0]
            },
            {
                label: 'Perempuan',
                data: [<?= $database[60][1]['jumlah'] ?>],
                backgroundColor: datawarna[1]
            }
        ]
		  },
		  plugins: [ChartDataLabels],
		  options: {
        scales: {
            y: {
                ticks: {
                    maxRotation: 90,
                    minRotation: 90
                }
            }
        },
			  plugins:{
          legend: {
          position:'bottom',
					display: true,
          align:'center'
				},
				datalabels:{
					anchor:'end',
					align:'right',
				}  
			  },
			indexAxis: 'y',
		  }
		});
</script>

<!-- <script>
		const ctx2 = document.getElementById('peserta');
    var datawarna = [];
    <?php foreach($database[72] as $warna){ ?>
      datawarna.push('<?php echo $warna; ?>');
    <?php } ?>
		new Chart(ctx2, {
		  type: 'bar',
		  data: {
			labels: [''],
			datasets: [
                {
                label: 'Aktif',
                data: [<?= $database[61][0]['jumlah'] ?>],
                backgroundColor: datawarna[0]
            },
                {
                label: 'Tidak Aktif',
                data: [<?= $database[61][1]['jumlah'] ?>],
                backgroundColor: datawarna[1]
            }
        ]
		  },
		  plugins: [ChartDataLabels],
		  options: {
			  plugins:{
          legend: {
            position:'bottom',
					display: false,
          align:'start'
				},
				datalabels:{
					anchor:'end',
					align:'right',
				}  
			  },
			indexAxis: 'y',
		  }
		});
</script> -->

<!-- <script>
		const ctx4 = document.getElementById('tim');
    var datawarna = [];
    <?php foreach($database[73] as $warna){ ?>
      datawarna.push('<?php echo $warna; ?>');
    <?php } ?>
		new Chart(ctx4, {
		  type: 'bar',
		  data: {
			labels: [''],
			datasets: [
                {
                label: 'Aktif',
                data: [<?= $database[62][0]['jumlah'] ?>],
                backgroundColor: datawarna[0]
            },
                {
                label: 'Tidak Aktif',
                data: [<?= $database[62][1]['jumlah'] ?>],
                backgroundColor: datawarna[1]
            }
        ]
		  },
		  plugins: [ChartDataLabels],
		  options: {
			  plugins:{
          legend: {
            position:'bottom',
					display: false,
          align:'start'
				},
				datalabels:{
					anchor:'end',
					align:'right',
				}  
			  },
			indexAxis: 'y',
		  }
		});
</script> -->

<script>
        var datawarna1 = [];
        var datalabel1 = [];
        var dataangka1 = [];
        <?php foreach($database[69] as $warna){ ?>
          datawarna1.push('<?php echo $warna; ?>');
        <?php } ?>
        <?php foreach($database[68] as $data){ ?>
          datalabel1.push('<?php echo ucwords($data['NAMA_ASAL']); ?>');
          dataangka1.push('<?php echo $data['jumlah']; ?>');
        <?php } ?>
		  		var ctx10 = document.getElementById("myChartTahun2");

  const getSuitableY = (y, yArray = [], direction) => {
    let result = y;
    yArray.forEach((existedY) => {
      if (existedY - 14 < result && existedY + 14 > result) {
        if (direction === "right") {
          result = existedY + 14;
        } else {
          result = existedY - 14;
        }
      }
    });
    return result;
  };

  const getOriginPoints = (source, center, l) => {
    // console.log(source, center, l)

    let a = {x: 0, y: 0};
    var dx = (center.x - source.x) / l
    var dy = (center.y - source.y) / l
    a.x = center.x + l * dx
    a.y = center.y + l * dy
    return a
  };
  const options = {
    plugins: {
      legend: {
        display: true,
        position: "bottom"
      },
    }
  };
  const plugins = [
    {
      afterDraw: (chart) => {
        const ctx = chart.ctx;
        ctx.save();
        ctx.font = "10px 'Averta Std CY'";
        const leftLabelCoordinates = [];
        const rightLabelCoordinates = [];
        const chartCenterPoint = {
          x:
            (chart.chartArea.right - chart.chartArea.left) / 2 +
            chart.chartArea.left,
          y:
            (chart.chartArea.bottom - chart.chartArea.top) / 2 +
            chart.chartArea.top
        };
        chart.config.data.labels.forEach((label, i) => {
          const meta = chart.getDatasetMeta(0);
          const arc = meta.data[i];
          const dataset = chart.config.data.datasets[0];

          // Prepare data to draw
          // important point 1
          const centerPoint = arc.getCenterPoint();
          let color = chart.config._config.data.datasets[0].backgroundColor[i];
          let labelColor = chart.config._config.data.datasets[0].backgroundColor[i];


          const angle = Math.atan2(
            centerPoint.y - chartCenterPoint.y,
            centerPoint.x - chartCenterPoint.x
          );
          // important point 2, this point overlapsed with existed points
          // so we will reduce y by 14 if it's on the right
          // or add by 14 if it's on the left
          let originPoint = getOriginPoints(chartCenterPoint, centerPoint, arc.outerRadius)
          const point2X =
            chartCenterPoint.x + Math.cos(angle) * (centerPoint.x < chartCenterPoint.x ? arc.outerRadius + 10 : arc.outerRadius + 10);
          let point2Y =
            chartCenterPoint.y + Math.sin(angle) * (centerPoint.y < chartCenterPoint.y ? arc.outerRadius + 15 : arc.outerRadius + 15);

          let suitableY;
          if (point2X < chartCenterPoint.x) {
            // on the left
            suitableY = getSuitableY(point2Y, leftLabelCoordinates, "left");
          } else {
            // on the right

            suitableY = getSuitableY(point2Y, rightLabelCoordinates, "right");
          }

          point2Y = suitableY;

          let value = dataset.data[i];
          // if (dataset.polyline && dataset.polyline.formatter) {
          //   value = dataset.polyline.formatter(value);
          // }
          let edgePointX = point2X < chartCenterPoint.x ? chartCenterPoint.x - arc.outerRadius - 10 : chartCenterPoint.x + arc.outerRadius + 10;

          if (point2X < chartCenterPoint.x) {
            leftLabelCoordinates.push(point2Y);
          } else {
            rightLabelCoordinates.push(point2Y);
          }

          //DRAW CODE
          // first line: connect between arc's center point and outside point
          ctx.lineWidth = 2;
          ctx.strokeStyle = color;
          ctx.beginPath();
          ctx.moveTo(originPoint.x, originPoint.y);
          ctx.lineTo(point2X, point2Y);
          ctx.stroke();
          // second line: connect between outside point and chart's edge
          ctx.beginPath();
          ctx.moveTo(point2X, point2Y);
          ctx.lineTo(edgePointX, point2Y);
          ctx.stroke();
          //fill custom label
          const labelAlignStyle =
            edgePointX < chartCenterPoint.x ? "right" : "left";
          const labelX = edgePointX < chartCenterPoint.x ? edgePointX : edgePointX + 0;
          const labelY = point2Y + 7;
          ctx.textAlign = labelAlignStyle;
          ctx.textBaseline = "bottom";
          ctx.font = "bold 12px Lato";
          ctx.fillStyle = "grey";
          // ctx.fillStyle = labelColor;
          ctx.fillText('  '+value+'  ', labelX, labelY);
        });
        ctx.restore();
      }
    }
  ];

  var myChart = new Chart(ctx10, {
    type: 'pie',
    plugins: plugins,
    options: options,
    data: {
      labels: datalabel1,
      datasets: [
        {
          label: "Jumlah Peserta",
          data: dataangka1,
          backgroundColor: datawarna1
        }
      ]
    }
  });
</script>

<script>
        var datawarna = [];
        var datalabel = [];
        var dataangka = [];
        <?php foreach($database[64] as $warna){ ?>
          datawarna.push('<?php echo $warna; ?>');
        <?php } ?>
        <?php foreach($database[63] as $data){ ?>
          datalabel.push('<?php echo ucwords($data['NAMA_ASAL']); ?>');
          dataangka.push('<?php echo $data['jumlah']; ?>');
        <?php } ?>
		  		var ctx10 = document.getElementById("myChartTahun");

  const getSuitableY1 = (y, yArray = [], direction) => {
    let result = y;
    yArray.forEach((existedY) => {
      if (existedY - 14 < result && existedY + 14 > result) {
        if (direction === "right") {
          result = existedY + 14;
        } else {
          result = existedY - 14;
        }
      }
    });
    return result;
  };

  const getOriginPoints1 = (source, center, l) => {
    // console.log(source, center, l)

    let a = {x: 0, y: 0};
    var dx = (center.x - source.x) / l
    var dy = (center.y - source.y) / l
    a.x = center.x + l * dx
    a.y = center.y + l * dy
    return a
  };
  const options1 = {
    plugins: {
      legend: {
        display: true,
        position: "bottom"
      },
    }
  };
  const plugins1 = [
    {
      afterDraw: (chart) => {
        const ctx = chart.ctx;
        ctx.save();
        ctx.font = "10px 'Averta Std CY'";
        const leftLabelCoordinates = [];
        const rightLabelCoordinates = [];
        const chartCenterPoint = {
          x:
            (chart.chartArea.right - chart.chartArea.left) / 2 +
            chart.chartArea.left,
          y:
            (chart.chartArea.bottom - chart.chartArea.top) / 2 +
            chart.chartArea.top
        };
        chart.config.data.labels.forEach((label, i) => {
          const meta = chart.getDatasetMeta(0);
          const arc = meta.data[i];
          const dataset = chart.config.data.datasets[0];

          // Prepare data to draw
          // important point 1
          const centerPoint = arc.getCenterPoint();
          let color = chart.config._config.data.datasets[0].backgroundColor[i];
          let labelColor = chart.config._config.data.datasets[0].backgroundColor[i];


          const angle = Math.atan2(
            centerPoint.y - chartCenterPoint.y,
            centerPoint.x - chartCenterPoint.x
          );
          // important point 2, this point overlapsed with existed points
          // so we will reduce y by 14 if it's on the right
          // or add by 14 if it's on the left
          let originPoint = getOriginPoints1(chartCenterPoint, centerPoint, arc.outerRadius)
          const point2X =
            chartCenterPoint.x + Math.cos(angle) * (centerPoint.x < chartCenterPoint.x ? arc.outerRadius + 10 : arc.outerRadius + 10);
          let point2Y =
            chartCenterPoint.y + Math.sin(angle) * (centerPoint.y < chartCenterPoint.y ? arc.outerRadius + 15 : arc.outerRadius + 15);

          let suitableY;
          if (point2X < chartCenterPoint.x) {
            // on the left
            suitableY = getSuitableY1(point2Y, leftLabelCoordinates, "left");
          } else {
            // on the right

            suitableY = getSuitableY1(point2Y, rightLabelCoordinates, "right");
          }

          point2Y = suitableY;

          let value = dataset.data[i];
          // if (dataset.polyline && dataset.polyline.formatter) {
          //   value = dataset.polyline.formatter(value);
          // }
          let edgePointX = point2X < chartCenterPoint.x ? chartCenterPoint.x - arc.outerRadius - 10 : chartCenterPoint.x + arc.outerRadius + 10;

          if (point2X < chartCenterPoint.x) {
            leftLabelCoordinates.push(point2Y);
          } else {
            rightLabelCoordinates.push(point2Y);
          }

          //DRAW CODE
          // first line: connect between arc's center point and outside point
          ctx.lineWidth = 2;
          ctx.strokeStyle = color;
          ctx.beginPath();
          ctx.moveTo(originPoint.x, originPoint.y);
          ctx.lineTo(point2X, point2Y);
          ctx.stroke();
          // second line: connect between outside point and chart's edge
          ctx.beginPath();
          ctx.moveTo(point2X, point2Y);
          ctx.lineTo(edgePointX, point2Y);
          ctx.stroke();
          //fill custom label
          const labelAlignStyle =
            edgePointX < chartCenterPoint.x ? "right" : "left";
          const labelX = edgePointX < chartCenterPoint.x ? edgePointX : edgePointX + 0;
          const labelY = point2Y + 7;
          ctx.textAlign = labelAlignStyle;
          ctx.textBaseline = "bottom";
          ctx.font = "bold 12px Lato";
          ctx.fillStyle = "grey";
          // ctx.fillStyle = labelColor;
          ctx.fillText('  '+value+'  ', labelX, labelY);
        });
        ctx.restore();
      }
    }
  ];

  var myChart = new Chart(ctx10, {
    type: 'pie',
    plugins: plugins1,
    options: options1,
    data: {
      labels: datalabel,
      datasets: [
        {
          label: "Jumlah Tim",
          data: dataangka,
          backgroundColor: datawarna
        }
      ]
    }
  });
</script>

<script>
        var datawarna = [];
        var datalabel = [];
        var dataangka = [];
        <?php foreach($database[75] as $warna){ ?>
          datawarna.push('<?php echo $warna; ?>');
        <?php } ?>
        <?php foreach($database[74] as $data){ ?>
          datalabel.push('<?php echo ucwords($data['nama_kawan']); ?>');
          dataangka.push('<?php echo $data['jumlah']; ?>');
        <?php } ?>
		  		var ctx10 = document.getElementById("myChartTahun3");

  const getSuitableY2 = (y, yArray = [], direction) => {
    let result = y;
    yArray.forEach((existedY) => {
      if (existedY - 14 < result && existedY + 14 > result) {
        if (direction === "right") {
          result = existedY + 14;
        } else {
          result = existedY - 14;
        }
      }
    });
    return result;
  };

  const getOriginPoints2 = (source, center, l) => {
    // console.log(source, center, l)

    let a = {x: 0, y: 0};
    var dx = (center.x - source.x) / l
    var dy = (center.y - source.y) / l
    a.x = center.x + l * dx
    a.y = center.y + l * dy
    return a
  };
  const options2 = {
    plugins: {
      legend: {
        display: true,
        position: "bottom"
      },
    }
  };
  const plugins2 = [
    {
      afterDraw: (chart) => {
        const ctx = chart.ctx;
        ctx.save();
        ctx.font = "10px 'Averta Std CY'";
        const leftLabelCoordinates = [];
        const rightLabelCoordinates = [];
        const chartCenterPoint = {
          x:
            (chart.chartArea.right - chart.chartArea.left) / 2 +
            chart.chartArea.left,
          y:
            (chart.chartArea.bottom - chart.chartArea.top) / 2 +
            chart.chartArea.top
        };
        chart.config.data.labels.forEach((label, i) => {
          const meta = chart.getDatasetMeta(0);
          const arc = meta.data[i];
          const dataset = chart.config.data.datasets[0];

          // Prepare data to draw
          // important point 1
          const centerPoint = arc.getCenterPoint();
          let color = chart.config._config.data.datasets[0].backgroundColor[i];
          let labelColor = chart.config._config.data.datasets[0].backgroundColor[i];


          const angle = Math.atan2(
            centerPoint.y - chartCenterPoint.y,
            centerPoint.x - chartCenterPoint.x
          );
          // important point 2, this point overlapsed with existed points
          // so we will reduce y by 14 if it's on the right
          // or add by 14 if it's on the left
          let originPoint = getOriginPoints2(chartCenterPoint, centerPoint, arc.outerRadius)
          const point2X =
            chartCenterPoint.x + Math.cos(angle) * (centerPoint.x < chartCenterPoint.x ? arc.outerRadius + 10 : arc.outerRadius + 10);
          let point2Y =
            chartCenterPoint.y + Math.sin(angle) * (centerPoint.y < chartCenterPoint.y ? arc.outerRadius + 15 : arc.outerRadius + 15);

          let suitableY;
          if (point2X < chartCenterPoint.x) {
            // on the left
            suitableY = getSuitableY2(point2Y, leftLabelCoordinates, "left");
          } else {
            // on the right

            suitableY = getSuitableY2(point2Y, rightLabelCoordinates, "right");
          }

          point2Y = suitableY;

          let value = dataset.data[i];
          // if (dataset.polyline && dataset.polyline.formatter) {
          //   value = dataset.polyline.formatter(value);
          // }
          let edgePointX = point2X < chartCenterPoint.x ? chartCenterPoint.x - arc.outerRadius - 10 : chartCenterPoint.x + arc.outerRadius + 10;

          if (point2X < chartCenterPoint.x) {
            leftLabelCoordinates.push(point2Y);
          } else {
            rightLabelCoordinates.push(point2Y);
          }

          //DRAW CODE
          // first line: connect between arc's center point and outside point
          ctx.lineWidth = 2;
          ctx.strokeStyle = color;
          ctx.beginPath();
          ctx.moveTo(originPoint.x, originPoint.y);
          ctx.lineTo(point2X, point2Y);
          ctx.stroke();
          // second line: connect between outside point and chart's edge
          ctx.beginPath();
          ctx.moveTo(point2X, point2Y);
          ctx.lineTo(edgePointX, point2Y);
          ctx.stroke();
          //fill custom label
          const labelAlignStyle =
            edgePointX < chartCenterPoint.x ? "right" : "left";
          const labelX = edgePointX < chartCenterPoint.x ? edgePointX : edgePointX + 0;
          const labelY = point2Y + 7;
          ctx.textAlign = labelAlignStyle;
          ctx.textBaseline = "bottom";
          ctx.font = "bold 12px Lato";
          ctx.fillStyle = "grey";
          // ctx.fillStyle = labelColor;
          ctx.fillText('  '+value+'  ', labelX, labelY);
        });
        ctx.restore();
      }
    }
  ];

  var myChart = new Chart(ctx10, {
    type: 'pie',
    plugins: plugins2,
    options: options2,
    data: {
      labels: datalabel,
      datasets: [
        {
          label: "Jumlah Tim",
          data: dataangka,
          backgroundColor: datawarna
        }
      ]
    }
  });
</script>
    

