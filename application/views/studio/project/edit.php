<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Studio<small>Project</small></h1>
</section>

<!-- Main content -->
<section class="content container-fluid">
	<div class="row">
		<form method="post" action="<?php echo base_url($this->router->fetch_class().'/project/edit/'.$project->id) ?>">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Project : <?php echo (set_value('name', $project->name) == $project->name)?$project->name:$project->name.' --- '.set_value('name', $project->name) ?></h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" name="name" placeholder="Project Name" value="<?php echo set_value('name', $project->name) ?>">
							<?php echo form_error('name', '<span class="help-block error">', '</span>'); ?>
						</div>
						<div class="form-group">
							<label>Category</label>
							<select name="category" class="form-control">
								<option value="-">- CHOOSE CATEGORY -</option>
								<?php foreach ($projects_category as $category) : ?>
								<option <?php echo set_value('category', $project->category) == $category->id?'selected':'' ?> value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
								<?php endforeach; ?>
							</select>
							<?php echo form_error('category', '<span class="help-block error">', '</span>'); ?>
						</div>
						<div class="form-group">
							<label>Area</label>
							<input type="text" class="form-control" name="area" placeholder="Project Area" value="<?php echo number_format(set_value('area', $project->area), 2) ?>" data-type="meter">
							<?php echo form_error('area', '<span class="help-block error">', '</span>'); ?>
						</div>
						<div class="form-group">
							<label>Budget</label>
							<input type="text" class="form-control" name="budget" placeholder="Project Budget" value="<?php echo number_format(set_value('budget', $project->budget), 2) ?>" data-type="currency">
							<?php echo form_error('budget', '<span class="help-block error">', '</span>'); ?>
						</div>
						<div class="form-group">
							<label>Deadline</label>
							<?php
							$deadline = (!empty($project->deadline))?explode('-', $project->deadline):NULL;
							if (!empty($deadline))
							{
								$deadline = $deadline[2].'/'.$deadline[1].'/'.$deadline[0];
							}
							?>
							<input type="text" class="form-control" name="deadline" placeholder="Project Deadline" id="datemask" value="<?php echo set_value('deadline', $deadline) ?>">
							<?php echo form_error('deadline', '<span class="help-block error">', '</span>'); ?>
						</div>
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="col-lg-3">
								<a href="<?php echo base_url($this->router->fetch_class().'/project') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
							</div>
							<div class="col-lg-3">
								<button type="submit" class="btn btn-block btn-flat btn-success"><i class="fa fa-save"></i> Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>