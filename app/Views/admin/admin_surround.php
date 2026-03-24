
<!DOCTYPE html>
<html>

<head>


	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Cllit Admin CPanel</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="shortcut icon" href="<?= getenv('app_baseURL') ?>assets/img/logo.png">


	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/imag_privew/neon-forms.css">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/bootstrap.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/language/l_one.css">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/data_table/dataTables.bootstrap.css">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/datepicker/datepicker3.css"> <!-- need -->
	<!-- bootstrap datepicker -->

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/fullcalendar/fullcalendar.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/fullcalendar/fullcalendar.print.css" media="print">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/select2/select2.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/dist/AdminLTE.min.css">

	<link rel="stylesheet" href="<?= base_url() ?>/assets/css/tholi/_all-skins.min.css">
	<script src="<?= base_url() ?>/assets/js/jQuery/jquery-2.2.3.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<header class="main-header">
			<!-- Logo -->
			<a href="index.php" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><b>CMS</b></span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg" style="font-size:13px;">Cllit Management System</span><br>

			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Navigation</span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- Messages: style can be found in dropdown.less-->






						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?=  base_url('asset/images/ajwyn_logo.png') ?>" class="user-image" alt="User Image">
								<span class="hidden-xs"><?= $userdetails[0]->name ?? '' ?></span>
							</a>
							<ul class="dropdown-menu">
								<!-- User image -->
								<li class="user-header">
									<img src="<?=  base_url('asset/images/ajwyn_logo.png') ?>" class="img-circle" alt="User Image">

									<p style="font-size:14px; font-weight:100;">
									<?= $userdetails[0]->name ?? '' ?>										<small style="font-size:12px; font-weight:100;">Member since Feb ,2023</small>
									</p>
								</li>
								<!-- Menu Body -->
								<li class="user-body">
									<div class="row">
										<div class="col-xs-4 text-center">
											<a href="<?= getenv('app_baseURL') ?>users/user-profile">Profile</a>
										</div>
										<div class="col-xs-4 text-center">

										</div>
										<div class="col-xs-4 text-center">

										</div>
									</div>
									<!-- /.row -->
								</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?= getenv('app_baseURL') ?>users/user-profile" class="btn btn-default btn-flat"><i class="fa fa-user margin-r-8"></i> &nbsp;Profile</a>
									</div>
									<div class="pull-right">
										<a href="<?= getenv('app_baseURL') ?>logoutadm" class="btn btn-default btn-flat">Sign out &nbsp;<i class="fa fa-sign-out margin-r-8"></i></a>
									</div>
								</li>
							</ul>
						</li>

						<li>
							<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<aside class="main-sidebar">

			<section class="sidebar">
				<!-- Sidebar user panel -->
				<div class="user-panel">
					<div class="pull-left image">
						<img src="<?=  base_url('asset/images/ajwyn_logo.png') ?>" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p><?= $userdetails[0]->name ?? '' ?></p>
						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<!-- search form -->
				<form action="#" method="get" class="sidebar-form">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</form>
				<!-- /.search form -->

				<?php $currentRole = $user_role ?? 'admin'; ?>
				<ul class="sidebar-menu">
				<li class="treeview" style="height: auto;">
						<a href="<?php echo base_url(); ?>users/user-dashboard">
							<i class="fa fa-home"></i> <span>Dashboard</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>

					</li>

					<?php if ($currentRole === 'vendor'): ?>
					<!-- ========= VENDOR MENU ========= -->
					<li class="treeview <?= ($main ?? 0) == 10 ? 'active' : '' ?>">
						<a href="<?= base_url('products') ?>">
							<i class="fa fa-cube"></i> <span>My Products</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="fa fa-shopping-cart"></i> <span>Orders</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?= base_url('users/pur-pro') ?>"><i class="fa fa-circle-o"></i> Purchased</a></li>
							<li><a href="<?= base_url('users/ship-pro') ?>"><i class="fa fa-circle-o"></i> Shipped</a></li>
							<li><a href="<?= base_url('users/del-pro') ?>"><i class="fa fa-circle-o"></i> Delivered</a></li>
							<li><a href="<?= base_url('users/can-pro') ?>"><i class="fa fa-circle-o"></i> Cancelled</a></li>
						</ul>
					</li>

					<?php else: ?>
					<!-- ========= ADMIN MENU ========= -->

					<!-- Vendor Management (Admin only) -->
					<li class="treeview">
						<a href="#">
							<i class="fa fa-users"></i> <span>Vendors</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?= base_url('users/vendor-list') ?>"><i class="fa fa-circle-o"></i> All Vendors</a></li>
							<li><a href="<?= base_url('users/vendor-add') ?>"><i class="fa fa-circle-o"></i> Add Vendor</a></li>
						</ul>
					</li>

					<!-- Customers (Admin only) -->
					<li class="treeview <?= ($main ?? 0) == 50 ? 'active' : '' ?>">
						<a href="<?= base_url('users/customers') ?>">
							<i class="fa fa-group"></i> <span>Customers</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
					</li>

					<!-- Sales Report (Admin only) -->
					<li class="treeview <?= ($main ?? 0) == 51 ? 'active' : '' ?>">
						<a href="<?= base_url('users/sales-report') ?>">
							<i class="fa fa-line-chart"></i> <span>Sales Report</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
					</li>

					<?php
					// Dynamic menus from DB (admin sees all)
					if(!empty($menus))
					{
						$mainarr = array_filter($menus,fn($values) => $values->master_id == 0);

						foreach ($mainarr as $key => $me) {
							$subarr = array_filter($menus,fn($values) => $values->master_id == $me->id);
							?>
								<li class="treeview <?=$main == $me->id ? 'active' : ''?>" >
						<a href="<?=empty($subarr) ? base_url().$me->url: '#'?>">
							<i class="<?=$me->icon?>"></i> <span><?=$me->menu?></span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						   <?php if(!empty($subarr)):?>
												<ul class="treeview-menu">
													<?php
													foreach ($subarr as  $s) {
														?>
														  <li class="<?=$s->id == $sub ? 'active' : ''?>"><a href="<?= base_url() ?><?=$s->url?>"><i class="fa fa-circle-o"></i> <?=$s->menu?></a></li>
														<?php
													}

												?>


		</ul>
		<?php endif;?>
							</li>
							<?php
						}
					}
					?>
					<?php endif; ?>

					<li class="header">LABELS</li>
					<li><a href="<?= getenv('app_baseURL') ?>logoutadm"><i class="fa fa-circle-o text-red"></i> <span>Logout</span></a></li>

				</ul>
			</section>
			<!-- /.sidebar -->
		</aside>


		<?= $this->renderSection('property') ?>


		<footer class="main-footer">
			<div class="pull-right hidden-xs">
				<b>Version</b> 2.2.1
			</div>
			<strong>Copyright &copy; 2022-2023<a href="http://cllit.com">Carmellight</a>.</strong> All rights
			reserved.
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Create the tabs -->
			<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
				<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
				<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<!-- Home tab content -->
				<div class="tab-pane" id="control-sidebar-home-tab">
					<h3 class="control-sidebar-heading">Recent Activity</h3>
					<ul class="control-sidebar-menu">
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-birthday-cake bg-red"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

									<p>Will be 23 on April 24th</p>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-user bg-yellow"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

									<p>New phone +1(800)555-1234</p>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

									<p>nora@example.com</p>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-file-code-o bg-green"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

									<p>Execution time 5 seconds</p>
								</div>
							</a>
						</li>
					</ul>
					<!-- /.control-sidebar-menu -->

					<h3 class="control-sidebar-heading">Tasks Progress</h3>
					<ul class="control-sidebar-menu">
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Custom Template Design
									<span class="label label-danger pull-right">70%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Update Resume
									<span class="label label-success pull-right">95%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-success" style="width: 95%"></div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Laravel Integration
									<span class="label label-warning pull-right">50%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-warning" style="width: 50%"></div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Back End Framework
									<span class="label label-primary pull-right">68%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-primary" style="width: 68%"></div>
								</div>
							</a>
						</li>
					</ul>
					<!-- /.control-sidebar-menu -->

				</div>
				<!-- /.tab-pane -->
				<!-- Stats tab content -->
				<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
				<!-- /.tab-pane -->
				<!-- Settings tab content -->
				<div class="tab-pane" id="control-sidebar-settings-tab">
					<form method="post">
						<h3 class="control-sidebar-heading">General Settings</h3>

						<div class="form-group">
							<label class="control-sidebar-subheading">
								Report panel usage
								<input type="checkbox" class="pull-right" checked>
							</label>

							<p>
								Some information about this general settings option
							</p>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading">
								Allow mail redirect
								<input type="checkbox" class="pull-right" checked>
							</label>

							<p>
								Other sets of options are available
							</p>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading">
								Expose author name in posts
								<input type="checkbox" class="pull-right" checked>
							</label>

							<p>
								Allow the user to show his name in blog posts
							</p>
						</div>
						<!-- /.form-group -->

						<h3 class="control-sidebar-heading">Chat Settings</h3>

						<div class="form-group">
							<label class="control-sidebar-subheading">
								Show me as online
								<input type="checkbox" class="pull-right" checked>
							</label>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading">
								Turn off notifications
								<input type="checkbox" class="pull-right">
							</label>
						</div>
						<!-- /.form-group -->

						<div class="form-group">
							<label class="control-sidebar-subheading">
								Delete chat history
								<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
							</label>
						</div>
						<!-- /.form-group -->
					</form>
				</div>
				<!-- /.tab-pane -->
			</div>
		</aside>
		<div class="control-sidebar-bg"></div>
	</div>

	<!-- Bootstrap 3.3.6 -->
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>


	<!-- DataTables start-->
	<script src="<?php echo base_url(); ?>assets/js/data_table/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/data_table/dataTables.bootstrap.min.js"></script>
	<!-- DataTables end-->


	<script src="<?php echo base_url(); ?>assets/js/imag_privew/fileinput.js"></script> <!-- image view -->

	<script src="<?php echo base_url(); ?>assets/js/input-mask/jquery.inputmask.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/moment/moment.min.js"></script>



	<script src="<?php echo base_url(); ?>assets/js/daterangepicker/daterangepicker.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/datepicker/bootstrap-datepicker.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/select2/select2.full.min.js"></script>



	<!-- Slimscroll -->
	<script src="<?php echo base_url(); ?>assets/js/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="<?php echo base_url(); ?>assets/js/fastclick/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url(); ?>assets/js/js/app.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?php echo base_url(); ?>assets/js/js/demo.js"></script>
	<!-- fullCalendar 2.2.5 -->

	<script src="<?php echo base_url(); ?>assets/js/fullcalendar/fullcalendar.min.js"></script>
	<!-- Page specific script -->


	<script>
		$(document).ready(function() {

			$("#vidSrc").hide();
			$(".url").click(function() {


				var title = $(this).data("title");
				if (title) {
					$("#vidSrc").show();

					jQuery('#vidSrc').attr('src', title);


				} else {
					$("#vidSrc").hide();
				}
			});


		});
	</script>


	<script>
		$(function() {

			/* initialize the external events
			 -----------------------------------------------------------------*/
			function ini_events(ele) {
				ele.each(function() {


					// it doesn't need to have a start or end
					var eventObject = {
						title: $.trim($(this).text()) // use the element's text as the event title
					};

					// store the Event Object in the DOM element so we can get to it later
					$(this).data('eventObject', eventObject);

					// make the event draggable using jQuery UI
					$(this).draggable({
						zIndex: 1070,
						revert: true, // will cause the event to go back to its
						revertDuration: 0 //  original position after the drag
					});

				});
			}

			ini_events($('#external-events div.external-event'));

			/* initialize the calendar
			 -----------------------------------------------------------------*/
			//Date for the calendar events (dummy data)
			var date = new Date();
			var d = date.getDate(),
				m = date.getMonth(),
				y = date.getFullYear();
			$('#calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				buttonText: {
					today: 'today',
					month: 'month',
					week: 'week',
					day: 'day'
				},
				//Random default events
				events: [{
						title: 'All Day Event',
						start: new Date(y, m, 1),
						backgroundColor: "#f56954", //red
						borderColor: "#f56954" //red
					},
					{
						title: 'Long Event',
						start: new Date(y, m, d - 5),
						end: new Date(y, m, d - 2),
						backgroundColor: "#f39c12", //yellow
						borderColor: "#f39c12" //yellow
					},
					{
						title: 'Meeting',
						start: new Date(y, m, d, 10, 30),
						allDay: false,
						backgroundColor: "#0073b7", //Blue
						borderColor: "#0073b7" //Blue
					},
					{
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false,
						backgroundColor: "#00c0ef", //Info (aqua)
						borderColor: "#00c0ef" //Info (aqua)
					},
					{
						title: 'Birthday Party',
						start: new Date(y, m, d + 1, 19, 0),
						end: new Date(y, m, d + 1, 22, 30),
						allDay: false,
						backgroundColor: "#00a65a", //Success (green)
						borderColor: "#00a65a" //Success (green)
					},
					{
						title: 'Click for Google',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						url: 'http://google.com/',
						backgroundColor: "#3c8dbc", //Primary (light-blue)
						borderColor: "#3c8dbc" //Primary (light-blue)
					}
				],
				editable: true,
				droppable: true, // this allows things to be dropped onto the calendar !!!
				drop: function(date, allDay) { // this function is called when something is dropped

					// retrieve the dropped element's stored Event Object
					var originalEventObject = $(this).data('eventObject');

					// we need to copy it, so that multiple events don't have a reference to the same object
					var copiedEventObject = $.extend({}, originalEventObject);

					// assign it the date that was reported
					copiedEventObject.start = date;
					copiedEventObject.allDay = allDay;
					copiedEventObject.backgroundColor = $(this).css("background-color");
					copiedEventObject.borderColor = $(this).css("border-color");

					// render the event on the calendar
					// the last `true` argument determines if the event "sticks"
					$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

					// is the "remove after drop" checkbox checked?
					if ($('#drop-remove').is(':checked')) {
						// if so, remove the element from the "Draggable Events" list
						$(this).remove();
					}

				}
			});

			/* ADDING EVENTS */
			var currColor = "#3c8dbc"; //Red by default
			//Color chooser button
			var colorChooser = $("#color-chooser-btn");
			$("#color-chooser > li > a").click(function(e) {
				e.preventDefault();
				//Save color
				currColor = $(this).css("color");
				//Add color effect to button
				$('#add-new-event').css({
					"background-color": currColor,
					"border-color": currColor
				});
			});
			$("#add-new-event").click(function(e) {
				e.preventDefault();
				//Get value and make sure it is not null
				var val = $("#new-event").val();
				if (val.length == 0) {
					return;
				}

				//Create events
				var event = $("<div />");
				event.css({
					"background-color": currColor,
					"border-color": currColor,
					"color": "#fff"
				}).addClass("external-event");
				event.html(val);
				$('#external-events').prepend(event);

				//Add draggable funtionality
				ini_events(event);

				//Remove event from text input
				$("#new-event").val("");
			});
		});
	</script>




	<!--for data table part abey-->

	<script>
		$(function() {
			$("#example1").DataTable();
			$('#example2').DataTable({
				"paging": true,
				"lengthChange": false,
				"searching": false,
				"ordering": true,
				"info": true,
				"autoWidth": false
			});
		});
	</script>

	<!--for data table part abey-->




	<script>
		$(function() {
			//Initialize Select2 Elements
			$(".select2").select2();

			//Datemask dd/mm/yyyy
			$("#datemask").inputmask("dd/mm/yyyy", {
				"placeholder": "dd/mm/yyyy"
			});
			//Datemask2 mm/dd/yyyy
			$("#datemask2").inputmask("mm/dd/yyyy", {
				"placeholder": "mm/dd/yyyy"
			});
			//Money Euro
			$("[data-mask]").inputmask();

			//Date range picker
			$('#reservation').daterangepicker();
			//Date range picker with time picker
			$('#reservationtime').daterangepicker({
				timePicker: true,
				timePickerIncrement: 30,
				format: 'YYYY/MM/DD h:mm A'
			});
			$('#datepicker').datepicker({
				datepicker: true,
				timePickerIncrement: 30,
				format: 'yyyy-mm-dd'
			});
			$('#datepicker2').datepicker({
				datepicker: true,
				timePickerIncrement: 30,
				format: 'yyyy-mm-dd'
			});
			$('#datepicker3').datepicker({
				datepicker: true,
				timePickerIncrement: 30,
				format: 'yyyy-mm-dd'
			});
			$('#datepicker4').datepicker({
				datepicker: true,
				timePickerIncrement: 30,
				format: 'yyyy-mm-dd'
			});
			$('#datepicker5').datepicker({
				datepicker: true,
				timePickerIncrement: 30,
				format: 'yyyy-mm-dd'
			});
			$('#datepicker6').datepicker({
				datepicker: true,
				timePickerIncrement: 30,
				format: 'yyyy-mm-dd'
			});
			$('.dp').datepicker({
				datepicker: true,
				timePickerIncrement: 30,
				format: 'yyyy-mm-dd'
			});
			//Date range as a button
			$('#daterange-btn').daterangepicker({
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					startDate: moment().subtract(29, 'days'),
					endDate: moment()
				},
				function(start, end) {
					$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));


				}
			);

			//Date picker
			$('#datepicker').datepicker({

				autoclose: true
			});

			//iCheck for checkbox and radio inputs
			$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
				checkboxClass: 'icheckbox_minimal-blue',
				radioClass: 'iradio_minimal-blue'
			});
			//Red color scheme for iCheck
			$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
				checkboxClass: 'icheckbox_minimal-red',
				radioClass: 'iradio_minimal-red'
			});
			//Flat red color scheme for iCheck
			$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
				checkboxClass: 'icheckbox_flat-green',
				radioClass: 'iradio_flat-green'
			});

			//Colorpicker
			$(".my-colorpicker1").colorpicker();
			//color picker with addon
			$(".my-colorpicker2").colorpicker();

			//Timepicker
			$(".timepicker").timepicker({
				showInputs: false
			});
		});
	</script>







</body>

</html>

