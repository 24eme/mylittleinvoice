<?php
	/*
		This file is part of MyLittleInvoice.

    MyLittleInvoice is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License.

    MyLittleInvoice is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>
		*/
		global $lang;
	?><aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel 
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p>Alexander Pierce</p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          -->
          <!-- search form
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form> -->
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
	      	<li>
              <a href="index.php">
                <i class="fa fa-dashboard"></i> <span><?php echo $lang['dashboard']; ?></span>    
              </a>
            </li>
			<li class="treeview">
              <a href="#">
                <i class="fa fa-cubes"></i>
                <span><?php echo $lang['invoicing']; ?></span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="facturationat86.php"><i class="fa ion-ios-plus-empty"></i> <?php echo $lang['global_invoicing']; ?></a></li>
		<li><a href="etat-y-factat86.php"><i class="fa ion-ios-plus-empty"></i> <?php echo $lang['state_invoicing']; ?></a></li>
                <li><a href="facturationurba.php"><i class="fa ion-ios-plus-empty"></i> <?php echo $lang['urba_invoicing']; ?></a></li>
                <li><a href="etat-y-facturba.php"><i class="fa ion-ios-plus-empty"></i> <?php echo $lang['statu_invoicing']; ?></a></li>
              </ul>
            </li>		
            <li>
            <a href="#">
              <i class="fa fa-area-chart"></i>
              <span><?php echo $lang['global_simulation']; ?></span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
              <ul class="treeview-menu">
                <li><a href="gosimul.php"><i class="fa ion-ios-plus-empty"></i> <?php echo $lang['global_np_simulation']; ?></a></li>
              </ul>
            </li>       
<!-- A implementer
            <li class="treeview">
             <a href="#">
                <i class="fa fa-bar-chart-o"></i>
                <span><?php echo $lang['reports']; ?></span>
                <i class="fa fa-angle-left pull-right"></i>
             </a>
             <ul class="treeview-menu">
                <li><a href="#"><i class="fa ion-ios-plus-empty"></i> <?php echo $lang['report_month']; ?></a></li>
                <li><a href="#"><i class="fa ion-ios-plus-empty"></i> <?php echo $lang['report_year']; ?></a></li>
             </ul>
            </li>
-->
        </section>
        <!-- /.sidebar -->
      </aside>
