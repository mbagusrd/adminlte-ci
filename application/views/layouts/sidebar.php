<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?= base_url() ?>" class="brand-link">
		<img src="<?= base_url('assets/adminlte/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo"
			class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">AdminLTE 3</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?= base_url('assets/adminlte/img/user2-160x160.jpg') ?>" class="img-circle elevation-2"
					alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block"><?= isset($user_name) ? html_escape($user_name) : 'User' ?></a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<?php
			$ci_uri_1 = $this->uri->segment(1, '');
			$ci_uri_2 = $this->uri->segment(2, '');
			?>
			<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?= site_url() ?>" class="nav-link <?= empty($ci_uri_1) ? 'active' : '' ?>">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= site_url('groups') ?>" class="nav-link <?= ($ci_uri_1 === 'groups') ? 'active' : '' ?>">
						<i class="nav-icon fas fa-users"></i>
						<p>Groups</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?= site_url('blank') ?>"
						class="nav-link <?= ($ci_uri_1 === 'blank' && empty($ci_uri_2)) ? 'active' : '' ?>">
						<i class="nav-icon far fa-file"></i>
						<p>Blank Page</p>
					</a>
				</li>
				<!-- Multilevel Menu Blank -->
				<li class="nav-item <?= ($ci_uri_1 === 'blank' && !empty($ci_uri_2)) ? 'menu-open' : '' ?>">
					<a href="#" class="nav-link <?= ($ci_uri_1 === 'blank' && !empty($ci_uri_2)) ? 'active' : '' ?>">
						<i class="nav-icon fas fa-circle"></i>
						<p>
							Multilevel Blank
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= site_url('blank/level2_1') ?>"
								class="nav-link <?= ($ci_uri_1 === 'blank' && $ci_uri_2 === 'level2_1') ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Level 2 (1)</p>
							</a>
						</li>
						<li
							class="nav-item <?= ($ci_uri_1 === 'blank' && in_array($ci_uri_2, ['level3_1', 'level3_2'])) ? 'menu-open' : '' ?>">
							<a href="#"
								class="nav-link <?= ($ci_uri_1 === 'blank' && in_array($ci_uri_2, ['level3_1', 'level3_2'])) ? 'active' : '' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>
									Level 2 (2)
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?= site_url('blank/level3_1') ?>"
										class="nav-link <?= ($ci_uri_1 === 'blank' && $ci_uri_2 === 'level3_1') ? 'active' : '' ?>">
										<i class="far fa-dot-circle nav-icon"></i>
										<p>Level 3 (1)</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= site_url('blank/level3_2') ?>"
										class="nav-link <?= ($ci_uri_1 === 'blank' && $ci_uri_2 === 'level3_2') ? 'active' : '' ?>">
										<i class="far fa-dot-circle nav-icon"></i>
										<p>Level 3 (2)</p>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</aside>
