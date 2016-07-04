<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel" style="height:45px;">
            <div class="pull-left info">
                <p>Welcome <strong><?php print $page_member_name; ?></strong><br /></p>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php if ($page == 'member') { ?>
        <ul class="sidebar-menu">
            <li class="active"><a href="<?php print base_url('index.php/main/member'); ?>"><i class="fa fa-calendar"></i> <span>Dashboard</span></a></li>
            <li class="treeview"> 
                <a href="#">
                    <i class="fa fa-calendar-check-o"></i> <span>Leave</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a id="link_leave_application" href="#"><i class="fa fa-calendar-plus-o"></i> Application</a></li>
                    <li><a id="link_leave_listing" href="#"><i class="fa fa-calendar-check-o"></i> Leave Listing</a></li>
                    <li><a id="link_leave_history" href="#"><i class="fa fa-calendar-o"></i> Leave History</a></li>
                </ul>
            </li>
            <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
            <li><a id="link_approve_application" href="#"><i class="fa fa-check-square-o"></i> <span>Approve Leave Application</span></a></li>
            <?php } ?>
            <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
            <li><a href="<?php print base_url() ?>index.php/main/user"><i class="fa fa-users"></i> <span>User Management</span></a></li>
            <?php } ?>
            <li><a href="<?php print base_url('index.php/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
        </ul>
        <?php } ?>
        <?php if ($page == 'user') { ?>
        <ul class="sidebar-menu">
            <li><a href="<?php print base_url('index.php/main/member'); ?>"><i class="fa fa-calendar"></i> <span>Dashboard</span></a></li>
            <li><a href="<?php print base_url() ?>index.php/main/member"><i class="fa fa-check-square-o"></i> <span>Leave Section</span></a></li>
            <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
            <li class="active"><a href="<?php print base_url() ?>index.php/main/user"><i class="fa fa-users"></i> <span>User Management</span></a></li>
            <?php } ?>
            <li><a href="<?php print base_url('index.php/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
        </ul>
        <?php } ?>
    </section>
    <!-- /.sidebar -->
</aside>