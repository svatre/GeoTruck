
<?php
$wsdl='http://geotruckservice.cloudapp.net/GeoTruckService.svc?wsdl';
$client = new soapclient($wsdl);
$temp = $client->GetPositions();
$table_positions = json_decode($temp->GetPositionsResult,true,512);
$count = count($table_positions);

$delai = 5;
header("Refresh:$delai;");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>GeoTruck | Dashboard</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-fr_regions-mill-en.js" type="text/javascript"></script>
	<script>
	//Affichage des points sur la map
	jQuery(function($) {
		// Création du tableau de marqueurs
		var camions = [
		<?php 
				// Comptage du nombre de positions données
				for ($i = 0; $i < $count; $i++) {
					//définition de la couleur du marqueur
					if($table_positions['0']['etat']['gravite'] == "4"){
						$etat = "red";
					} else if ($table_positions['0']['etat']['gravite'] == "3") {
						$etat = "orange";
					} else if ($table_positions['0']['etat']['gravite'] == "2") {
						$etat = "grey";
					} else {
						$etat = "green";
					} 
					
					// Parsage de la date de l'alerte
					$date_array = date_parse($table_positions['0']['etat']['date']);
					if($date_array[minute] >= 0 && $date_array[minute] <= 9){
						$date_array[minute] = "0" . $date_array[minute];
					}
					$date_alerte = $date_array[hour] . "h" .$date_array[minute];
					
					// Affichage des marqueurs et de leurs données
					$marqueur = "{latLng: [" . $table_positions['0']['localisation']['latitude'] . "," . $table_positions['0']['localisation']['longitude'] . "]";
					$marqueur .= ", marque: '". $table_positions['0']['camion']['Marque'] . "', immatriculation:'" . $table_positions['0']['camion']['Immatriculation'] ."'";
					$marqueur .= ", date_alerte: '". $date_alerte ."', style: {fill: '". $etat ."'}, commentaire_alerte: '". $table_positions['0']['etat']['commentaire'] ."'";
					$marqueur .= ", chauffeur: '" . $table_positions['0']['chauffeur']['Nom'] . " " . $table_positions['0']['chauffeur']['Prenom'] . "'}, ";
					
					echo $marqueur;
				} 
			?>
		];
      $('#map').vectorMap({
      	map: 'fr_regions_mill_en',
      	markers: camions,
      	markerLabelStyle: {
	        hover: {
		        fill: 'red'
	        }
        },
      	onMarkerLabelShow: function(event, label, index) {
	      	label.html('<span style="font-size:16px">' + camions[index].marque + ' [' + camions[index].immatriculation + ']</span><br/><span style="font-size:16px">' + camions[index].chauffeur + '</span><br/><span style="font-size:16px">' + camions[index].date_alerte + ' : ' + camions[index].commentaire_alerte + '</span>');                
    },
		backgroundColor: '#383f47'
	  });
    });
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="index2.html" class="logo"><b>GEO</b>Truck</a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">              
              <!-- Notifications: style can be found in dropdown.less -->
	              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="dist/img/Default_Profile.jpg" class="user-image" alt="User Image"/>
                  <span class="hidden-xs">Admin</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="dist/img/Default_Profile.jpg" class="img-circle" alt="User Image" />
                    <p>
                      Admin
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Réglages</a>
                    </div>
                    <div class="pull-right">
                      <a href="#" class="btn btn-default btn-flat">Se déconnecter</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Rechercher..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">Menu</li>

            <li>
              <a href="index.php">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="alertes.php">
                <i class="fa fa-table"></i> <span>Toutes les alertes</span> 
              </a>
            </li>
            <li>
              <a href="creation_chauffeur.php">
                <i class="fa fa-edit"></i> <span>Ajouter un chauffeur</span> 
              </a>
            </li>
            <li>
              <a href="fiche_chauffeur.php">
                <i class="fa fa-folder"></i> <span>Chercher un chauffeur</span> 
              </a>
            </li>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            <small>Version 1.2</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
            <li class="active">Toutes les alertes</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->      
          <div class="row">
            <div class="col-md-12">
              <!-- TABLE: LATEST ORDERS -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Toutes les alertes</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin" >
                      <thead>
                        <tr>
                          <th>Marque Camion</th>
                          <th>Immatriculation</th>
                          <th>Nom Chauffeur</th>
                          <th>Type d'alerte</th>
                          <th>Gravité</th>
                          <th>Commentaire</th>
                        </tr>
                      </thead>
                      <tbody>
                      	<?php   		
                      		for ($i = 0; $i < $count; $i++) {
                      		
                      			if($table_positions['0']['etat']['gravite'] == "4"){
									$class = "label label-danger";
								} else if ($table_positions['0']['etat']['gravite'] == "3") {
									$class = "label label-warning";
								} else if ($table_positions['0']['etat']['gravite'] == "2") {
									$class = "label label-default";
								} else {
									$class = "label label-success";
								} 
                      		
                      			$tr = "<tr>";
	                      		$tr .= "<td><a href='#'>" . $table_positions['0']['camion']['Marque'] . "</a></td>";
	                      		$tr .= "<td>" . $table_positions['0']['camion']['Immatriculation'] . "</td>";
	                      		$tr .= "<td>" . $table_positions['0']['chauffeur']['Prenom'] . " " . $table_positions['0']['chauffeur']['Nom'] . "</td>";
	                      		$tr .= "<td>" . $table_positions['0']['etat']['type'] . "</td>";
	                      		$tr .= "<td><span class='". $class ."'>" . $table_positions['0']['etat']['gravite'] . "</span></td>";
	                      		$tr .= "<td>" . $table_positions['0']['etat']['commentaire'] . "</td>";
	                      		$tr .= "</tr>";
	                      		 
	                      		echo $tr;
	                      	}
	                      		
                      	?>
                       </tbody>
                    </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->
            </div><!-- /.col -->
           </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.2
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="#">Geotruck</a>.</strong> Tous droits réservés.
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>

    <!-- daterangepicker -->
    <script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js" type="text/javascript"></script>

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard2.js" type="text/javascript"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js" type="text/javascript"></script>
  </body>
</html>