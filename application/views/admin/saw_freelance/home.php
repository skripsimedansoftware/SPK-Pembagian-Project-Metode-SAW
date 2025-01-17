<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Studio<small>Project</small></h1>
</section>

<style type="text/css">
label.freelancer-info {
	width: 20%;
}
</style>

<!-- Main content -->
<section class="content container-fluid">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">SAW (Simple Additive Weighting) - Freelance</h3>
		</div>
		<div class="box-body">
			<?php
			$saw = new Algorithm\SAW;

			foreach ($this->criteria->read()->result() as $criteria_key => $criteria) :
				$saw->addCriteria($criteria->attribute, $criteria->weight, $criteria->name);
			endforeach;

			$saw->addCriteria('cost', 25, 'Banyaknya proyek yang dikerjakan');

			foreach ($this->user->read(array('role' => 'freelancer'))->result() as $user) :
				$user_criteria = array();
				foreach ($this->criteria->read()->result() as $criteria) :
					array_push($user_criteria, $this->alternative_data->read(array('user_id' => $user->id, 'criteria_id' => $criteria->id))->row()->weight);
				endforeach;
				$user_project = $this->freelancer_project->count(array('user_id' => $user->id, 'rating' => NULL));

				if ($user_project == 0)
				{
					$user_project = 1;
				}

				array_push($user_criteria, $user_project);
				$saw->addAlternative((array) $user, $user_criteria);
			endforeach;


			$matrix_x = array(
				'assigned' => array(),
				'unassigned' => array()
			);

			$normalized_r = array(
				'matrix' => array(),
				'preference' => array(),
				'criteria' => array()
			);


			foreach ($saw->getCriteria()->get() as $criteria_key => $criteria)
			{
				$matrix_x['assigned'][$criteria_key] = array();
				$matrix_x['unassigned'] = array();

				$normalized_r['matrix'][$criteria_key] = array();

				foreach ($saw->getAlternative()->get() as $alternative_key => $alternative)
				{
					// unassigned alternative to criteria
					array_push($matrix_x['unassigned'], $alternative['criteria']);

					// assign alternative to criteria
					array_push($matrix_x['assigned'][$criteria_key], $saw->getAlternative()->get($alternative_key)['criteria'][$criteria_key]);
				}

				foreach ($matrix_x['assigned'][$criteria_key] as $row_x)
				{
					// benefit = max size
					if ($criteria['attribute'] == 'benefit')
					{
						array_push($normalized_r['matrix'][$criteria_key], round($row_x/max($matrix_x['assigned'][$criteria_key]), 2));
					}
					// cost = min size
					else
					{
						array_push($normalized_r['matrix'][$criteria_key], round(min($matrix_x['assigned'][$criteria_key])/$row_x, 2));
					}
				}
			}

			foreach ($saw->getAlternative()->get() as $alternative_key => $alternative)
			{
				foreach ($normalized_r['matrix'] as $normalized_key => $normalize)
				{
					array_push($normalized_r['preference'], $normalized_r['matrix'][$normalized_key][$alternative_key]);
				}
			}

			$normalized_r['preference'] = array_chunk($normalized_r['preference'], count($saw->getCriteria()->get()));

			foreach ($saw->getCriteria()->get() as $criteria_key => $criteria)
			{
				array_push($normalized_r['criteria'], $criteria['weight']);
			}

			$sum_prefrence = array();

			foreach ($normalized_r['preference'] as $pkey => $preference)
			{
				$sum_prefrence[$pkey] = array();
				foreach ($normalized_r['criteria'] as $ckey => $criteria)
				{
					array_push($sum_prefrence[$pkey], $preference[$ckey]*$criteria);
				}
			}

			for ($pkey = 0; $pkey < count($normalized_r['preference']); $pkey++)
			{
				$sum_prefrence[$pkey] = array();

				for ($ckey = 0; $ckey < count($normalized_r['criteria']); $ckey++)
				{
					array_push($sum_prefrence[$pkey], $normalized_r['preference'][$pkey][$ckey]*$normalized_r['criteria'][$ckey]);
				}
			}

			for ($i = 0; $i < count($sum_prefrence); $i++)
			{
				$sum_prefrence[$i] = array_sum($sum_prefrence[$i]);
			}

			?>

			<h3>Bobot Kriteria</h3>
			<table class="table table-hover table-striped table-bordered datatable">
				<thead>
					<th>Kriteria</th>
					<th>Deskripsi</th>
					<th>Bobot</th>
					<th>Atribut</th>
				</thead>
				<tbody>
					<?php foreach ($saw->getCriteria()->get() as $criteria_key => $criteria) : ?>
					<tr>
						<td>C<?php echo ($criteria_key+1) ?></td>
						<td><?php echo $criteria['name'] ?></td>
						<td><?php echo $criteria['weight'] ?></td>
						<td><?php echo $criteria['attribute'] ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<hr>

			<h3>Kecocokan Freelancer dan Kriteria</h3>
			<table class="table table-hover table-striped table-bordered datatable">
				<thead>
					<th>No</th>
					<th>Freelancer</th>
					<?php
					$i = 1;
					foreach ($saw->getCriteria()->get() as $criteria_key => $criteria) : ?>
					<th>C<?php echo ($criteria_key+1) ?></th>
					<?php endforeach; ?>
				</thead>
				<tbody>
					<?php foreach ($saw->getAlternative()->get() as $alternative) : ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $alternative['data']['full_name'] ?></td>
						<?php foreach ($saw->getCriteria()->get() as $criteria_key => $criteria) : ?>
						<td><?php echo $alternative['criteria'][$criteria_key] ?></td>
						<?php endforeach; ?>
					</tr>
					<?php
					$i++;
					endforeach;
					?>
				</tbody>
			</table>

			<hr>

			<?php
			?>
			<h3>Kecocokan Freelancer dan Kriteria</h3>
			<table class="table table-hover table-striped table-bordered datatable">
				<thead>
					<th>Rank</th>
					<th>Freelancer</th>
					<?php foreach ($saw->getCriteria()->get() as $criteria_key => $criteria) : ?>
					<th>C<?php echo ($criteria_key+1) ?></th>
					<?php endforeach; ?>
					<th>Total</th>
					<th>Option</th>
				</thead>
				<tbody>
				<?php
				$sort_from_big = arsort($sum_prefrence);
				$rank = 1;
				foreach ($sum_prefrence as $key => $value) :
					$freelancer = array_merge($saw->getAlternative()->get($key), array('value' => $value));
					$project_has_rating = $this->freelancer_project->has_rating($freelancer['data']['id']);
					$project_on_going = $this->freelancer_project->on_going($freelancer['data']['id']);
					?>
					<tr>
						<td><?php echo $rank ?></td>
						<td>
							<?php
							echo $freelancer['data']['full_name'];
							?>
						</td>
						<?php foreach ($freelancer['criteria'] as $freelancer_criteria) : ?>
						<td><?php echo $freelancer_criteria; ?></td>
						<?php endforeach; ?>
						<td><?php echo $value; ?></td>
						<td>
							<button class="btn btn-xs btn-info" data-toggle="modal" data-target="#modal-profile-freelancer-<?php echo $freelancer['data']['id'] ?>">Profil Pekerja</button>
							<a href="<?php echo base_url($this->router->fetch_class().'/give_project/'.$freelancer['data']['id'].'/'.$project_id) ?>" class="btn btn-xs btn-primary">Beri Project</a>
						</td>
					</tr>
					<!-- modal add project category -->
					<div class="modal fade" id="modal-profile-freelancer-<?php echo $freelancer['data']['id'] ?>">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="modal-title-category">Profil Freelancer</h4>
								</div>
								<div class="modal-body">
									<label class="freelancer-info">Email</label> : <?php echo $freelancer['data']['email'] ?><br>
									<label class="freelancer-info">Nama Lengkap</label> : <?php echo $freelancer['data']['full_name'] ?><br>
									<label class="freelancer-info">Project Berjalan</label>
									<?php if ($project_on_going->num_rows() > 0): ?>
									:
									<ol>
									<?php foreach ($project_on_going->result() as $project) : ?>
										<?php $project_detail = $this->project->read(array('id' => $project->project_id)); ?>
										<?php if ($project_detail->num_rows() > 0) : ?>
											<?php $project_detail = $project_detail->row() ?>
										<li>
											<ul>
												<li><label class="freelancer-info">Nama Project</label> : <?php echo $project_detail->name ?></li>
												<li><label class="freelancer-info">Deadline</label> : <?php echo nice_date($project_detail->deadline, 'd-m-Y') ?></li>
												<li><label class="freelancer-info">Budget Project</label> : Rp.<?php echo number_format($project_detail->budget, 2) ?></li>
											</ul>
										</li>
										<?php endif; ?>
									<?php endforeach; ?>
									</ol>
									<br>
									<?php else: ?>
									: tidak ada<br>
									<?php endif; ?>

									<label class="freelancer-info">Project Selesai</label>
									<?php if ($project_has_rating->num_rows() > 0): ?>
									:
									<ol>
									<?php foreach ($project_has_rating->result() as $project) : ?>
										<?php $project_detail = $this->project->read(array('id' => $project->project_id)); ?>
										<?php if ($project_detail->num_rows() > 0) : ?>
											<?php $project_detail = $project_detail->row() ?>
										<li>
											<ul>
												<li><label class="freelancer-info">Nama Project</label> : <?php echo $project_detail->name ?></li>
												<li><label class="freelancer-info">Deadline</label> : <?php echo nice_date($project_detail->deadline, 'd-m-Y') ?></li>
												<li><label class="freelancer-info">Budget Project</label> : Rp.<?php echo number_format($project_detail->budget, 2) ?></li>
												<li><label class="freelancer-info">Rating Project</label> : <?php echo $project->rating ?></li>
											</ul>
										</li>
										<?php endif; ?>
									<?php endforeach; ?>
									</ol>
									<br>
									<?php else: ?>
									: tidak ada<br>
									<?php endif; ?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
					<!-- /modal -->
					<?php
					$rank++;
				endforeach;
				?>
				</tbody>
			</table>
		</div>
		<div class="box-footer">
			<a href="<?php echo base_url($this->router->fetch_class().'/project') ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
		</div>
	</div>
</section>
