<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Studio<small>Project</small></h1>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="col-lg-12">
		<?php if ($this->session->has_userdata('project')): ?>
			<?php if ($this->session->userdata('project')['status'] == 'success'): ?>
				<div class="alert alert-dismissible alert-success"><?php echo $this->session->userdata('project')['message']; ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<?php else: ?>
				<div class="alert alert-dismissible alert-danger"><?php echo $this->session->userdata('project')['message']; ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">List of Project</h3>
			</div>
			<div class="box-body">
				<table class="table table-hover table-striped datatable">
					<thead>
						<th>No</th>
						<th>Name</th>
						<th>Freelancer</th>
						<th>Category</th>
						<th>Area</th>
						<th>Budget</th>
						<th>Deadline</th>
						<th>Status</th>
						<th>Option</th>
					</thead>
					<tbody>
						<?php foreach ($projects as $key => $project): ?>
						<tr>
							<td><?php echo $key+1 ?></td>
							<td><?php echo $project->name ?></td>
							<td>
								<?php
								$freelancer_project = $this->freelancer_project->read(array('project_id' => $project->id));
								if ($freelancer_project->num_rows() >= 1)
								{
									$freelancer_project = $freelancer_project->row();
									$freelancer = $this->user->read(array('id' => $freelancer_project->user_id));
									if ($freelancer->num_rows() >= 1)
									{
										$freelancer = $freelancer->row();
										echo $freelancer->full_name;
									}
									else
									{
										echo 'Tidak Ditemukan';
									}
								}
								else
								{
									echo 'Tidak Ditemukan';
								}
								?>
							</td>
							<td>
								<?php
								$project_category = $this->project_category->read(array('id' => $project->category));

								echo ($project_category->num_rows() >= 1)?$project_category->row()->name:'-';
								?>
							</td>
							<td><?php echo $project->area ?>m²</td>
							<td>Rp.<?php echo number_format($project->budget, 2) ?></td>
							<td>
								<?php
								if (!empty($project->deadline))
								{
									$deadline = explode('-', $project->deadline);
									echo $deadline[2].'-'.$deadline[1].'-'.$deadline[0];
								}
								else
								{
									echo '-';
								}
								?>
							</td>
							<td>
								<?php switch ($project->status) {
									case 'search-freelance':
										?>
										<a href="<?php echo base_url($this->router->fetch_class().'/saw_freelance/'.$project->id) ?>" class="btn btn-block btn-flat btn-xs btn-primary">Cari Pekerja</a>
										<?php
									break;

									case 'pending':
										?>
										<a href="#" class="btn btn-block btn-flat btn-xs btn-warning">Menunggu Konfirmasi</a>
										<?php
									break;

									case 'in-progress':
										?>
										<a href="#" class="btn btn-block btn-flat btn-xs bg-navy">Dalam Proses</a>
										<?php
									break;

									case 'not-completed':
										?>
										<a href="#" class="btn btn-block btn-flat btn-xs btn-warning">Tidak Selesai</a>
										<?php
									break;

									case 'canceled':
										?>
										<a href="#" class="btn btn-block btn-flat btn-xs btn-danger">Dibatalkan</a>
										<?php
									break;
									
									// finished
									default:
										?>
										<a href="#" class="btn btn-block btn-flat btn-xs btn-success">Selesai</a>
										<?php
									break;
								} ?>
							</td>
							<td>
								<?php if (in_array($project->status, ['search-freelance'])) : ?>
									<a href="<?php echo base_url($this->router->fetch_class().'/project/edit/'.$project->id) ?>" class="btn btn-flat btn-xs btn-default">Sunting</a>
									<a href="<?php echo base_url($this->router->fetch_class().'/set_project_status/'.$project->id.'/canceled') ?>" class="btn btn-flat btn-xs btn-warning">Batalkan</a>
								<?php endif; ?>
								<?php switch ($project->status) {
									case 'search-freelance':
										?>
										<a href="<?php echo base_url($this->router->fetch_class().'/project/delete/'.$project->id) ?>" class="btn btn-flat btn-xs btn-danger">Hapus</a>
										<?php
									break;

									case 'in-progress':
										?>
										<a href="<?php echo base_url($this->router->fetch_class().'/project/change_status/'.$project->id) ?>" class="btn btn-flat btn-xs bg-maroon">Ubah Status</a>
										<?php
									break;
									
									// finished
									default:
										?>
										<a href="<?php echo base_url($this->router->fetch_class().'/project/detail/'.$project->id) ?>" class="btn btn-flat btn-xs btn-info">Detail</a>
										<?php
									break;
								} ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-lg-3">
						<a href="<?php echo base_url($this->router->fetch_class().'/project/add') ?>" class="btn btn-block btn-flat btn-success"><i class="fa fa-plus"></i> Add Project</a>
					</div>
					<div class="col-lg-3 pull-right">
						<a class="btn bg-navy" href="<?= base_url('report/index/'.$this->router->fetch_class().'/'.$this->session->userdata($this->router->fetch_class())) ?>"><i class="fa fa-print"></i> Cetak Laporan</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Project Category</h3>
			</div>
			<div class="box-body">
				<table class="table table-hover table-striped table-condensed table-bordered">
					<thead>
						<th>Name</th>
						<th>Option</th>
					</thead>
					<tbody id="project-category-list">
						<?php foreach ($projects_category as $project_category): ?>
						<tr>
							<td><?php echo $project_category->name ?></td>
							<td>
								<button class="btn btn-xs btn-default modal-category-edit" onclick="category_edit(<?php echo $project_category->id ?>)" data-id="<?php echo $project_category->id ?>" data-toggle="modal" data-target="#modal-category"><i class="fa fa-edit"></i></button>
								&nbsp;&nbsp;
								<button class="btn btn-xs btn-danger modal-category-delete" onclick="category_delete(<?php echo $project_category->id ?>)" data-id="<?php echo $project_category->id ?>"><i class="fa fa-trash-o"></i></button>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="box-footer">
				<button class="btn btn-primary btn-flat btn-block" data-toggle="modal" data-target="#modal-category" id="modal-category-add"><i class="fa fa-plus"></i> Add Category</button>
			</div>
		</div>
	</div>
</section>
