<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sistem Pendukung Keputusan | SAW - Pembagian Project</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>bower_components/bootstrap-star-rating/css/star-rating.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte/') ?>dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/') ?>SweetAlert2/dist/sweetalert2.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/') ?>DataTables/datatables.min.css">

	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/RatingStar/') ?>star-rating.min.css" media="all" type="text/css"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/RatingStar/') ?>theme.min.css" media="all" type="text/css"/>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<style type="text/css">
	.help-block.error {
		color: red;
	}

	.user-panel > .image > img {
		width: 45px;
		height: 45px;
		/*height: auto;*/
	}

	.swal2-popup { font-size: 1.6rem !important; }
	</style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini skin-blue fixed">
<div class="wrapper">

	<!-- Main Header -->
	<header class="main-header">

		<!-- Logo -->
		<a href="<?php echo base_url() ?>" target="_blank" class="logo">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><b>S</b>PK</span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><b>SPK</b> - SAW</span>
		</a>

		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<!-- Navbar Right Menu -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<!-- Notifications Menu -->
					<li class="dropdown notifications-menu">
						<!-- Menu toggle button -->
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-bell-o"></i>
							<span class="label label-warning"><?php echo $notification->num_rows(); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="header">You have <?php echo $notification->num_rows(); ?> notifications</li>
							<li>
								<!-- Inner Menu: contains the notifications -->
								<ul class="menu">
									<?php foreach ($notification->result() as $value) : ?>
									<li><!-- start notification -->
										<a href="<?php echo base_url($this->router->fetch_class().$value->uri) ?>"><i class="fa fa-info text-aqua"></i>
										<?php
										$url = base_url($this->router->fetch_class().$value->uri);
										$parse_url = parse_url($url);
										parse_str($parse_url['query'], $query);
										if (isset($query['received']))
										{
											$project = $this->project->read(array('id' => str_replace('?received=true', '', explode('/', $value->uri)[3])));
											if ($project->num_rows() >= 1)
											{
												echo '<b>Project Diterima Freelancer</b><br>';
												echo $project->row()->name;
											}
										}
										?>
										</a>
									</li>
									<?php endforeach; ?>
									<!-- end notification -->
								</ul>
							</li>
							<li class="footer"><a href="#">View all</a></li>
						</ul>
					</li>
					<!-- User Account Menu -->
					<li class="dropdown user user-menu">
						<!-- Menu Toggle Button -->
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<!-- The user image in the navbar-->
							<img src="<?php echo (!empty($user->photo))?base_url('uploads/'.$user->photo):base_url('assets/adminlte/dist/img/user2-160x160.jpg') ?>" class="user-image" alt="User Image">
							<!-- hidden-xs hides the username on small devices so only the image appears. -->
							<span class="hidden-xs"><?php echo $user->full_name ?></span>
						</a>
						<ul class="dropdown-menu">
							<!-- The user image in the menu -->
							<li class="user-header">
								<img src="<?php echo (!empty($user->photo))?base_url('uploads/'.$user->photo):base_url('assets/adminlte/dist/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image">
								<p><?php echo $user->full_name ?> - STUDIO</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="<?php echo base_url($this->router->fetch_class().'/profile') ?>" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="<?php echo base_url($this->router->fetch_class().'/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>
					<!-- Control Sidebar Toggle Button -->
					<li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li>
				</ul>
			</div>
		</nav>
	</header>
	<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">

		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">

			<!-- Sidebar user panel (optional) -->
			<div class="user-panel">
				<div class="pull-left image">
					<img src="<?php echo (!empty($user->photo))?base_url('uploads/'.$user->photo):base_url('assets/adminlte/dist/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image" style="max-height: 45px;">
				</div>
				<div class="pull-left info">
					<p><?php echo $user->full_name ?></p>
					<!-- Status -->
					<a href="#"><i class="fa fa-circle text-success"></i> online</a>
				</div>
			</div>

			<!-- Sidebar Menu -->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="header">KAMI SPACE</li>
				<!-- Optionally, you can add icons to the links -->
				<li class="<?php echo $this->router->fetch_method() == 'index'?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class()) ?>"><i class="fa fa-home"></i> <span>Home</span></a></li>
				<li class="<?php echo (in_array($this->router->fetch_method(), ['project', 'saw_freelance']))?'active':'' ?>"><a href="<?php echo base_url($this->router->fetch_class().'/project') ?>"><i class="fa fa-map"></i> <span>Project</span></a></li>
			</ul>
			<!-- /.sidebar-menu -->
		</section>
		<!-- /.sidebar -->
	</aside>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<?php echo $page ?>
	</div>
	<!-- /.content-wrapper -->

	<!-- Main Footer -->
	<footer class="main-footer">
		<!-- To the right -->
		<div class="pull-right hidden-xs">
			Skripsi
		</div>
		<!-- Default to the left -->
		<strong>Copyright &copy; <?php echo date('Y') ?> <a href="#">Kamispace</a>.</strong> All rights reserved.
	</footer>

	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
		<!-- Create the tabs -->
		<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
			<li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
			<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<!-- Home tab content -->
			<div class="tab-pane active" id="control-sidebar-home-tab">
				<h3 class="control-sidebar-heading">Recent Activity</h3>
				<ul class="control-sidebar-menu">
					<li>
						<a href="javascript:;">
							<i class="menu-icon fa fa-birthday-cake bg-red"></i>
							<div class="menu-info">
								<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
								<p>Will be 23 on April 24th</p>
							</div>
						</a>
					</li>
				</ul>
				<!-- /.control-sidebar-menu -->

				<h3 class="control-sidebar-heading">Tasks Progress</h3>
				<ul class="control-sidebar-menu">
					<li>
						<a href="javascript:;">
							<h4 class="control-sidebar-subheading">
								Custom Template Design
								<span class="pull-right-container"><span class="label label-danger pull-right">70%</span></span>
							</h4>

							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
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
						<p>Some information about this general settings option</p>
					</div>
					<!-- /.form-group -->
				</form>
			</div>
			<!-- /.tab-pane -->
		</div>
	</aside>
	<!-- /.control-sidebar -->
	<!-- Add the sidebar's background. This div must be placed
	immediately after the control sidebar -->
	<div class="control-sidebar-bg"></div>

	<!-- modal add project category -->
	<div class="modal fade" id="modal-category">
		<div class="modal-dialog">
			<form id="form-category">
				<input type="hidden" id="category-method">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="modal-title-category">Add Category</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label>Name</label>
									<input type="text" class="form-control" name="name" placeholder="Category Name" id="category-name">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- /modal -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?php echo base_url('assets/adminlte/') ?>bower_components/jquery/dist/jquery.min.js"></script>

<script src="<?php echo base_url('assets/plugins/RatingStar/') ?>star-rating.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/RatingStar/') ?>theme.min.js" type="text/javascript"></script>

<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/adminlte/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- JQuery InputMask -->
<script src="<?php echo base_url('assets/adminlte/') ?>plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url('assets/adminlte/') ?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo base_url('assets/adminlte/') ?>plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- SweetAlert2 -->
<script src="<?php echo base_url('assets/plugins/') ?>SweetAlert2/dist/sweetalert2.all.min.js"></script>

<!-- DataTables -->
<script src="<?php echo base_url('assets/plugins/') ?>DataTables/datatables.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/adminlte/') ?>dist/js/adminlte.min.js"></script>

<script type="text/javascript">
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#profile-upload-preview').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$(document).ready(function() {
	$('.rating').rating('update', $('input[name="rating"]').val());
	$('.rating-view').rating('update', $('input[name="rating"]').val());
});

$('.rating-view').rating({
	size:'xs',
	min: 0, max: 5, step: 1, stars: 5,
	starCaptions: {1: 'Very Poor', 2: 'Poor', 3: 'Ok', 4: 'Good', 5: 'Very Good'},
	hoverEnabled: false,
	displayOnly: true,
	theme: 'krajee-svg',
	filledStar: '<span class="krajee-icon krajee-icon-star"></span>',
	emptyStar: '<span class="krajee-icon krajee-icon-star"></span>',
	showCaption: false,
	showClear:false
});

$('.rating').rating({
	min: 0, max: 5, step: 1, stars: 5,
	starCaptions: {1: 'Very Poor', 2: 'Poor', 3: 'Ok', 4: 'Good', 5: 'Very Good'},
	theme: 'krajee-svg',
	filledStar: '<span class="krajee-icon krajee-icon-star"></span>',
	emptyStar: '<span class="krajee-icon krajee-icon-star"></span>',
	showCaption: true,
	showClear:false
});

$('.rating,.kv-svg').on('change', function (){
	$('input[name="rating"]').val($(this).val());
});

$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
$('.datatable').DataTable({
	responsive: true
});

/**
 * ----------------------------------------
 * Project Category
 * ----------------------------------------
 */
function load_project_category() {
	$.ajax({
		url: '<?php echo base_url($this->router->fetch_class().'/project_category') ?>',
		type: 'get',
		success: function (data) {
			$('#project-category-list').empty();
			data.data.forEach((el, index) => {
				$('#project-category-list').append(
				'<tr>'+
					'<td>'+el.name+'</td>'+
					'<td>'+
						'<button class="btn btn-xs btn-default modal-category-edit" onclick="category_edit('+el.id+')"  data-id="'+el.id+'" data-toggle="modal" data-target="#modal-category" ><i class="fa fa-edit"></i></button>'+
						"&nbsp;&nbsp;&nbsp;&nbsp;"+
						'<button class="btn btn-xs btn-danger modal-category-delete" onclick="category_delete('+el.id+')"  data-id="'+el.id+'"><i class="fa fa-trash-o"></i></button>'+
					'</td>'+
				'</tr>'
				);
			});
		}
	});
}

function category_edit(id) {
	$('#modal-title-category').text('Edit Category');
	$('#category-method').val('edit').attr('data-id', id);
	$.ajax({
		url: '<?php echo base_url($this->router->fetch_class().'/project_category/view/') ?>'+id,
		type: 'get',
		success: function (data) {
			$('#category-name').val(data.data.name);
		},
		error: function(error) {
			console.log(error)
		}
	});
}

function category_delete(id) {
	Swal.fire({
		title: 'Do you want to delete?',
		showDenyButton: false,
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		denyButtonText: `Don't save`,
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: '<?php echo base_url($this->router->fetch_class().'/project_category/delete/') ?>'+id,
				type: 'get',
				success: function (data) {
					Swal.fire('Project category deleted!', '', 'success');
					load_project_category();
				},
				error: function(error) {
					console.log(error)
				}
			});
		} else if (result.isDenied) {
			Swal.fire('Changes are not saved', '', 'info')
		}
	});
}

$('#modal-category-add').on('click', function () {
	$('#modal-title-category').text('Add Category');
	$('#category-method').val('add').attr('data-id', null);
	$('#category-name').val('');
});


$('#form-category').on('submit', (function(e) {
	e.preventDefault();
	var id = $('#category-method').attr('data-id');
	if ($('#category-method').val() == 'add')
	{
		$.ajax({
			url: '<?php echo base_url($this->router->fetch_class().'/project_category') ?>',
			type: 'post',
			data: {
				name: $('#category-name').val()
			},
			success: function (data) {
				load_project_category();
			},
			error: function(error) {
				console.log(error)
			}
		});
	}
	else
	{
		$.ajax({
			url: '<?php echo base_url($this->router->fetch_class().'/project_category/edit/') ?>'+id,
			type: 'post',
			data: {
				name: $('#category-name').val()
			},
			success: function (data) {
				load_project_category();
			},
			error: function(error) {
				console.log(error)
			}
		});
	}

	$('#modal-category').modal('hide');
}));


$("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});


$("input[data-type='meter']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});

$("#select-update-project-status").on('change', function() {
	if (this.value == 'not-completed') {
		$('input[name="percent_progress"]').removeAttr('disabled');
	} else {
		$('input[name="percent_progress"]').attr('disabled', 'disabled');
	}
})

function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  
  // get input value
  var input_val = input.val();
  
  // don't validate empty input
  if (input_val === "") { return; }
  
  // original length
  var original_len = input_val.length;

  // initial caret position 
  var caret_pos = input.prop("selectionStart");
    
  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);
    
    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }
    
    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;
    
    // final formatting
    if (blur === "blur") {
      input_val += ".00";
    }
  }
  
  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

</script>
</body>
</html>
