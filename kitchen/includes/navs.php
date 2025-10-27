<?php

require_once __DIR__ . '/../config.php';


// Definir opciones de menú y permisos
$menuOptions = [
	'dashboard' => ['url' => '../dashboard', 'icon' => './../../svg/dashboard.svg', 'text' => 'Dashboard'],
	'users' => ['url' => '../users', 'icon' => './../../svg/user.svg', 'text' => 'Users/groups'],
//	'qr' => ['url' => '../qrcode', 'icon' => './../../svg/qr_code.svg', 'text' => 'QR'],
	'ecommerce' => ['url' =>  BASE_URL . '/ecommerce', 'icon' => './../../svg/tpv.svg', 'text' => 'E-commerce'],
	'restaurant' => ['url' => BASE_URL . '/restaurant/public/restaurants.html', 'icon' => './../../svg/restaurant.svg', 'text' => 'Restaurant'],
	'elaborations' => ['url' => '../elaborations', 'icon' => './../../svg/recipes.svg', 'text' => 'Elaborations'],
	'Ing/Recipes' => ['url' => '../ing_recetas', 'icon' => './../../svg/recipe_white.svg', 'text' => 'Ing/Recipes'],
	'Plating' => ['url' => '../plating', 'icon' => './../../svg/plato_blanco.svg', 'text' => 'Plating up'],
	'Mise en Place' => ['url' => '../miseEnPlace', 'icon' => './../../svg/miseEnPlace.svg', 'text' => 'Mise en Place'],
	'stock' => ['url' => '../stock', 'icon' => './../../svg/stock.svg', 'text' => 'Order tracking'],
	'suppliers' => ['url' => '../suppliers', 'icon' => './../../svg/orders.svg', 'text' => 'Suppliers'],
	'economic' => ['url' => '#', 'icon' => './../../svg/graph.svg', 'text' => 'Economic'],
];

$permisosMenu = [
	'shopWeb' => ['dashboard', 'ecommerce', 'stock'],
	'systemTag' => ['dashboard', 'users', 'qr', 'elaborations', 'stock', 'suppliers', 'Ing/Recipes'],
	'dashboardProd' => ['dashboard', 'restaurant'],
	'restaurant' => ['restaurant', 'elaborations', 'stock', 'Ing/Recipes'],
	'dashboardGen' => ['dashboard', 'users', 'qr', 'ecommerce', 'restaurant', 'elaborations', 'stock', 'suppliers', 'economic', 'Ing/Recipes', 'Plating'],
	'shopBackoffice' => ['dashboard', 'suppliers', 'economic'],
	'root' => ['dashboard', 'users', 'qr', 'ecommerce', 'restaurant', 'elaborations', 'stock', 'suppliers', 'economic', 'Ing/Recipes', 'Mise en Place', 'Plating'],
];


$currentGroupId = isset($grupoSession) ? $grupoSession->getId() : 0;


$idPermisos = array();

$idPermisos = GrupoPermisoDao::getPermisosByGroupId($currentGroupId);

$nombresPermiso = array();

foreach ($idPermisos as $idPermiso) {

	$nombresPermiso[] = PermisoDao::getPermisoNombreById($idPermiso);
}


$currentGroupPermissions = [];
foreach ($permisosMenu as $grupo => $grupoPermisos) {
	if (in_array($grupo, $nombresPermiso)) {
		$currentGroupPermissions = array_merge($currentGroupPermissions, $grupoPermisos);
	}
}

$currentGroupPermissions = array_unique($currentGroupPermissions);



?>



<nav id="headerNav" class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow ">
	<!-- Mobile button-->
	<button id="sidebarToggleTop" name="boto_burguer" class="btn btn-link d-md-none rounded-circle mr-3">
		<i class="fa fa-bars"></i>
	</button>
	<!-- Topbar Navbar -->
	<ul class="navbar-nav ml-auto">
		<!-- Search Dropdown (Visible Only XS) -->
		<li class="nav-item dropdown no-arrow d-sm-none">
			<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-search fa-fw"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
				<form class="form-inline mr-auto w-100 navbar-search">
					<div class="input-group">
						<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-primary" type="button">
								<i class="fas fa-search fa-sm"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</li>

		<div id="container">

		</div>

		<!-- Alerts Dropdown -->
		<li class="nav-item dropdown no-arrow mx-1">
			<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-bell fa-fw"></i>
				<!- Counter - Alerts ->
					<span class="badge badge-danger badge-counter">3+</span>
			</a>
			<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
				<h6 class="dropdown-header">
					Alerts Center
				</h6>
				<a class="dropdown-item d-flex align-items-center" href="#">
					<div class="mr-3">
						<div class="icon-circle bg-primary">
							<i class="fas fa-file-alt text-white"></i>
						</div>
					</div>
					<div>
						<div class="small text-gray-500">August 20, 2023</div>
						<span class="font-weight-bold">Bolognese tags level i slow</span>
					</div>
				</a>
				<a class="dropdown-item d-flex align-items-center" href="#">
					<div class="mr-3">
						<div class="icon-circle bg-success">
							<i class="fas fa-donate text-white"></i>
						</div>
					</div>
					<div>
						<div class="small text-gray-500">August 15, 2023</div>
						Some falafel is expired
					</div>
				</a>
				<a class="dropdown-item d-flex align-items-center" href="#">
					<div class="mr-3">
						<div class="icon-circle bg-warning">
							<i class="fas fa-exclamation-triangle text-white"></i>
						</div>
					</div>
					<div>
						<div class="small text-gray-500">August 10, 2023</div>
						Order without stock of Meat balls
					</div>
				</a>
				<a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
			</div>
		</li>
		<!-- User Information Dropdown-->
		<li class="nav-item dropdown no-arrow">
			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= isset($userSession) ? $userSession->getName() . '(' . $grupoSession->getNombre() . ')' : "" ?></span>
				<img class="img-profile rounded-circle" src="<?= isset($imgProfile) ? $imgProfile : "./../../img/undraw_profile.svg" ?>">
			</a>
			<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
				<a class="dropdown-item" href="#">
					<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
					Profile
				</a>
				<a class="dropdown-item" href="#">
					<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
					Settings
				</a>
				<a class="dropdown-item" href="#">
					<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
					Activity Log
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="<?= isset($pathLogout) ? $pathLogout : "./../login/logout.php" ?>">
					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
					Logout
				</a>
			</div>
		</li>
	</ul>
</nav>
<!-- Sidebar -->
<nav id="sidebar" class="nav sidebar sidebar-dark  accordion">

	<div style="padding: 5px;">
		<a href="<?= isset($pathDashboard) ? $pathDashboard : "../dashboard" ?>">
			<img src="<?= isset($pathLogo) ? $pathLogo : "../../img/ccsLogoWhite.png" ?>" id="imgLogo" alt="Cook Code System Logo">
		</a>
	</div>
	<ul class="navbar-nav bg-primary " id="accordionSidebar">





		<?php if (isset($menu_options)) {

			// Filtrar opciones de menú basadas en permisos del grupo actual
			$filtered_menu_options = array_filter($menu_options, function ($key) use ($currentGroupPermissions) {
				return in_array($key, $currentGroupPermissions);
			}, ARRAY_FILTER_USE_KEY);




			echo renderMenu($filtered_menu_options);
		} else {


			// Filtrar opciones de menú basadas en permisos del grupo actual
			$filtered_menu_options = array_filter($menuOptions, function ($key) use ($currentGroupPermissions) {
				return in_array($key, $currentGroupPermissions);
			}, ARRAY_FILTER_USE_KEY);



			echo renderMenu($filtered_menu_options);
		}
		?>


		<hr class="sidebar-divider d-none d-md-block">

		<div class="text-center d-none d-md-inline" id="toggleWrapper">

		<!--	
			<button class="rounded-circle border-0" id="sidebarToggle">



			</button>
-->
		</div>

	</ul>
</nav>
<script>

</script>

<?php
function renderMenu($menu_options)
{
	$html = '';
	foreach ($menu_options as $option) {
		$html .= "<li class='nav-item'>";
		$html .= "<a class='nav-link' href='" . htmlspecialchars($option['url'], ENT_QUOTES, 'UTF-8') . "'>";
		$html .= "<img src='" . htmlspecialchars($option['icon'], ENT_QUOTES, 'UTF-8') . "' type='image/svg+xml' style='width: 24px; height: 24px; margin-right: 5px;'></object>";
		$html .= "<p>" . htmlspecialchars($option['text'], ENT_QUOTES, 'UTF-8') . "</p>";
		$html .= "</a>";
		$html .= "</li>";
	}
	return $html;
}


function insertarTopNav($link, $url, $name)
{
?>
	<script>
		// Definir la función JavaScript para agregar un NavLink
		function agregarNavLink(link, url, name) {
			let contenedor = document.querySelector('#headerNav');
			let navLink = document.createElement('a');
			let img = document.createElement('img'); // Cambié "object" a "img"
			let p = document.createElement('p');

			navLink.style.display = 'flex';
			navLink.style.alignItems = 'center';
			navLink.style.flexWrap = 'nowrap';
			navLink.style.minWidth = '130px';
			navLink.style.color = '#5a5c69';
			navLink.classList.add('nav-link');
			navLink.setAttribute('href', link);

			navLink.addEventListener('mouseover', function() {
				navLink.style.fontWeight = 'bold';
			});

			navLink.addEventListener('mouseout', function() {
				navLink.style.fontWeight = '400';
			});

			img.setAttribute('src', url); // Usar "src" en lugar de "data"
			img.setAttribute('alt', name); // Añadir texto alternativo para accesibilidad
			img.style.minWidth = "25px"; // Ajustar el tamaño si es necesario

			p.innerText = name;
			p.style.margin = 'auto';

			navLink.appendChild(img); // Añadir img al navLink
			navLink.appendChild(p);
			contenedor.prepend(navLink);
		}


		// Llamar a la función para agregar el NavLink con los parámetros proporcionados
		agregarNavLink("<?php echo $link ?>", "<?php echo $url ?>", "<?php echo $name ?>");
	</script>
<?php
}
?>
<script>
	const toggleWrapper = document.getElementById('toggleWrapper');
	const nav2 = document.getElementById('sidebar');
	const logo = document.getElementById('imgLogo');
	const aS = nav2.querySelectorAll('a');
	const ps = nav2.querySelectorAll('p');

	function updateSidebarWidth() {
		const ventanaAncho = window.innerWidth;
		if (ventanaAncho < 768) {
			nav2.style.width = '100px';
		} else {
			nav2.style.width = '220px';
		}
	}

	updateSidebarWidth();
	window.addEventListener('resize', updateSidebarWidth);

	let isExpanded = true; // Default state

	toggleWrapper.addEventListener('click', function() {
		isExpanded = !isExpanded;

		if (isExpanded || window.innerWidth > 768) {
			//nav2.style.width = '220px';
			ps.forEach(p => {
				p.style.display = 'block';
			});
			aS.forEach(a => {
				a.style.justifyContent = 'flex-start';
				a.style.marginLeft = '0px';
			});
			logo.style.width = '220px';
		} else if(!isExpanded || window.innerWidth < 768){
		//	nav2.style.width = '100px';
			ps.forEach(p => {
				p.style.display = 'none';
			});
			aS.forEach(a => {
				a.style.justifyContent = 'center';
				a.style.marginLeft = '15px';
			});
			logo.style.width = '60px';
		}

		logo.style.marginLeft = '0px';
	});

	


	// Function to return if the sidebar is expanded or not
	function isSidebarExpanded() {
		return isExpanded;
	}
</script>

