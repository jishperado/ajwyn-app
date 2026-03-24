<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper" style="min-height: 1472px;">

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
			<small>Control panel</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>


	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<!-- ./col -->

			<!-- ./col -->

			<div class="col-sm-12">
				<div class="box box-primary">
					<!-- small box -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-6 col-12">
								<hr>
								<canvas id="canvas" width="190" style="background-image:linear-gradient(#337ab7,#337ab7); padding-left:40px; border-radius: 25px;">
								</canvas>

								<script>
									var canvas = document.getElementById("canvas");
									var ctx = canvas.getContext("2d");
									var radius = canvas.height / 2;
									ctx.translate(radius, radius);
									radius = radius * 0.90
									setInterval(drawClock, 1000);

									function drawClock() {
										drawFace(ctx, radius);
										drawNumbers(ctx, radius);
										drawTime(ctx, radius);
									}

									function drawFace(ctx, radius) {
										var grad;
										ctx.beginPath();
										ctx.arc(0, 0, radius, 0, 2 * Math.PI);
										ctx.fillStyle = 'white';
										ctx.fill();
										grad = ctx.createRadialGradient(0, 0, radius * 0.95, 0, 0, radius * 1.05);
										grad.addColorStop(0, '#333');
										grad.addColorStop(0.5, 'white');
										grad.addColorStop(1, '#333');
										ctx.strokeStyle = grad;
										ctx.lineWidth = radius * 0.1;
										ctx.stroke();
										ctx.beginPath();
										ctx.arc(0, 0, radius * 0.1, 0, 2 * Math.PI);
										ctx.fillStyle = '#333';
										ctx.fill();
									}

									function drawNumbers(ctx, radius) {
										var ang;
										var num;
										ctx.font = radius * 0.15 + "px arial";
										ctx.textBaseline = "middle";
										ctx.textAlign = "center";
										for (num = 1; num < 13; num++) {
											ang = num * Math.PI / 6;
											ctx.rotate(ang);
											ctx.translate(0, -radius * 0.85);
											ctx.rotate(-ang);
											ctx.fillText(num.toString(), 0, 0);
											ctx.rotate(ang);
											ctx.translate(0, radius * 0.85);
											ctx.rotate(-ang);
										}
									}

									function drawTime(ctx, radius) {
										var now = new Date();
										var hour = now.getHours();
										var minute = now.getMinutes();
										var second = now.getSeconds();
										//hour
										hour = hour % 12;
										hour = (hour * Math.PI / 6) +
											(minute * Math.PI / (6 * 60)) +
											(second * Math.PI / (360 * 60));
										drawHand(ctx, hour, radius * 0.5, radius * 0.07);
										//minute
										minute = (minute * Math.PI / 30) + (second * Math.PI / (30 * 60));
										drawHand(ctx, minute, radius * 0.8, radius * 0.07);
										// second
										second = (second * Math.PI / 30);
										drawHand(ctx, second, radius * 0.9, radius * 0.02);
									}

									function drawHand(ctx, pos, length, width) {
										ctx.beginPath();
										ctx.lineWidth = width;
										ctx.lineCap = "round";
										ctx.moveTo(0, 0);
										ctx.rotate(pos);
										ctx.lineTo(0, -length);
										ctx.stroke();
										ctx.rotate(-pos);
									}
								</script>
								<hr>
							</div>
							<div class="col-md-6 col-12">

								<ul class="products-list product-list-in-box">
									<li class="item">
										<div class="product-img">
											<a href=""><img src="<?=  base_url('asset/images/ajwyn_logo.png') ?>"></a>
										</div>
										<div class="product-info" style="padding-left: 50px">
											<strong>Name : <?= $userdetails[0]->name ?? "" ?></strong> <br>

											<a href="<?= getenv('app_baseURL') ?>users/user-profile">Change Password</a><br>


										</div>
									</li>
									<!-- /.item -->


									<!-- /.item -->
								</ul>



							</div>


				</div>
			</div>





			<!-- Quick Stats -->
			<div class="col-sm-12" style="margin-top: 20px;">
				<div class="row">
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3><?= $product_count ?? 0 ?></h3>
								<p><?= ($user_role ?? 'admin') === 'vendor' ? 'My Products' : 'Total Products' ?></p>
							</div>
							<div class="icon"><i class="fa fa-cube"></i></div>
							<a href="<?= base_url('products') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?= $order_pending ?? 0 ?></h3>
								<p>Pending Orders</p>
							</div>
							<div class="icon"><i class="fa fa-clock-o"></i></div>
							<a href="<?= base_url('pur-pro') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-blue">
							<div class="inner">
								<h3><?= $order_shipped ?? 0 ?></h3>
								<p>Shipped Orders</p>
							</div>
							<div class="icon"><i class="fa fa-truck"></i></div>
							<a href="<?= base_url('ship-pro') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-green">
							<div class="inner">
								<h3><?= $order_delivered ?? 0 ?></h3>
								<p>Delivered Orders</p>
							</div>
							<div class="icon"><i class="fa fa-check-circle"></i></div>
							<a href="<?= base_url('del-pro') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<?php if (($user_role ?? 'admin') === 'admin' && isset($vendor_count)): ?>
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-purple">
							<div class="inner">
								<h3><?= $vendor_count ?? 0 ?></h3>
								<p>Total Vendors</p>
							</div>
							<div class="icon"><i class="fa fa-users"></i></div>
							<a href="<?= base_url('users/vendor-list') ?>" class="small-box-footer">Manage <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<?php endif; ?>
					<?php if (($user_role ?? 'admin') === 'admin' && isset($customer_count)): ?>
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-maroon">
							<div class="inner">
								<h3><?= $customer_count ?? 0 ?></h3>
								<p>Customers</p>
							</div>
							<div class="icon"><i class="fa fa-address-book"></i></div>
							<a href="<?= base_url('users/customers') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<?php endif; ?>
					<?php if (($user_role ?? 'admin') === 'admin' && isset($order_cancelled)): ?>
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?= $order_cancelled ?? 0 ?></h3>
								<p>Cancelled Orders</p>
							</div>
							<div class="icon"><i class="fa fa-times-circle"></i></div>
							<a href="<?= base_url('can-pro') ?>" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- Sales Charts (Admin only) -->
			<?php if (($user_role ?? 'admin') === 'admin' && isset($monthly_data)): ?>
			<div class="col-sm-12" style="margin-top: 20px;">
				<div class="row">
					<div class="col-md-8">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-line-chart"></i> Monthly Revenue (Last 6 Months)</h3>
							</div>
							<div class="box-body">
								<canvas id="revenueChart" height="280"></canvas>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-pie-chart"></i> Order Status</h3>
							</div>
							<div class="box-body">
								<canvas id="orderStatusChart" height="280"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>



		</div>
		<!-- /.row -->
		<!-- Main row -->

		<!-- /.row (main row) -->


	</section>

	<!-- /.content -->
</div>

<?php if (($user_role ?? 'admin') === 'admin' && isset($monthly_data)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var monthlyData = <?= $monthly_data ?>;
	var labels = monthlyData.map(function(d) { return d.label; });
	var revenues = monthlyData.map(function(d) { return d.revenue; });
	var orders = monthlyData.map(function(d) { return d.orders; });

	// Revenue Line Chart
	var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
	new Chart(ctxRevenue, {
		type: 'line',
		data: {
			labels: labels,
			datasets: [{
				label: 'Revenue (₹)',
				data: revenues,
				borderColor: '#3c8dbc',
				backgroundColor: 'rgba(60,141,188,0.15)',
				fill: true,
				tension: 0.3,
				pointRadius: 5,
				pointBackgroundColor: '#3c8dbc'
			}, {
				label: 'Orders',
				data: orders,
				borderColor: '#00a65a',
				backgroundColor: 'rgba(0,166,90,0.1)',
				fill: false,
				tension: 0.3,
				pointRadius: 5,
				pointBackgroundColor: '#00a65a',
				yAxisID: 'y1'
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: { legend: { position: 'top' } },
			scales: {
				y: { beginAtZero: true, title: { display: true, text: 'Revenue (₹)' } },
				y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Orders' }, grid: { drawOnChartArea: false } }
			}
		}
	});

	// Order Status Doughnut Chart
	var ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
	new Chart(ctxStatus, {
		type: 'doughnut',
		data: {
			labels: ['Pending', 'Shipped', 'Delivered', 'Cancelled'],
			datasets: [{
				data: [
					<?= $order_pending ?? 0 ?>,
					<?= $order_shipped ?? 0 ?>,
					<?= $order_delivered ?? 0 ?>,
					<?= $order_cancelled ?? 0 ?>
				],
				backgroundColor: ['#f39c12', '#3c8dbc', '#00a65a', '#dd4b39'],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: { position: 'bottom' }
			}
		}
	});
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
