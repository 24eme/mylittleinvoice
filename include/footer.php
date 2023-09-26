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
?> <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2019.1
        </div>
        <strong>Facturation 2019 pour l'<a href="http://www.at86.fr" target=_blank>Agence des Territoires de la Vienne</a>.</strong> 
      </footer>
      
      <!-- Control Sidebar -->      
      <aside class="control-sidebar control-sidebar-dark">                
        <!-- Create the tabs -->
        
        <!-- Tab panes -->
        <div class="tab-content">
       
          <!-- Stats tab content -->
          <div class="tab-pane" id="control-sidebar-stats-tab">Options</div><!-- /.tab-pane -->
          <!-- Settings tab content -->
          <h4><?php echo $lang['settings']; ?></h4>
          <hr />
          <div class="form-group">
	         <ul class="sidebar-menu">
		      	<!-- <li>
	              <a href="options-general.php" style="color:#b8c7ce;">
	                <i class="fa fa-sliders"></i> <span><?php echo $lang['general']; ?></span>    
	              </a>
	            </li>
	            <li>
	              <a href="options-contact.php" style="color:#b8c7ce;">
	                <i class="fa fa-crosshairs"></i> <span><?php echo $lang['coordinated']; ?></span>    
	              </a>
	            </li>
	            <li>
	              <a href="options-file.php" style="color:#b8c7ce;">
	                <i class="fa fa-file"></i> <span><?php echo $lang['documents']; ?></span>    
	              </a>
	            </li>-->
	            <li> 
	              <a href="options-users.php" style="color:#b8c7ce;">
	                <i class="fa fa-users"></i> <span><?php echo $lang['users']; ?></span>    
	              </a>
	            </li>
	            <li> 
	              <a href="options-config.php" style="color:#b8c7ce;">
	                <i class="fa fa-file"></i> <span><?php echo $lang['config']; ?></span>    
	              </a>
	            </li>
	            <li> 
	              <a href="options-confignp.php" style="color:#b8c7ce;">
	                <i class="fa fa-file"></i> <span><?php echo $lang['confignp']; ?></span>    
	              </a>
	            </li>
	            <li> 
	              <a href="options-analytique.php" style="color:#b8c7ce;">
	                <i class="fa fa-file"></i> <span><?php echo $lang['configana']; ?></span>    
	              </a>
	            </li>
	         </ul>
           </div><!-- /.form-group -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->

    <!-- daterangepicker -->
    <script src="dist/moment-2.10.2/moment.min.js" type="text/javascript"></script>
    <script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js" type="text/javascript"></script>
    <!-- Morris.js charts -->
    <script src="dist/raphael-2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js" type="text/javascript"></script>
  </body>
</html>
