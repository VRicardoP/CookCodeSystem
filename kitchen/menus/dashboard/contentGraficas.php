<div class="row">

<!-- Area Chart -->
<div class="col-xl-6 col-md-12">
    <div class="card shadow mb-4" style="background-color: #e5e5e5; border-radius: 30px;">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #e5e5e5; border-radius: 30px;">
            <h6 class="m-0 font-weight-bold text-primary"></h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink6">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" id="evolSemana">Week</a>
                    <a class="dropdown-item" id="evolMes">Month</a>
                    <a class="dropdown-item" id="evolAnio">Year</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-area">
                <canvas id="graficaLineas" style=" width: 100%; height: 100%; max-width: 100%; max-height: 100%;"></canvas>
            </div>
            <hr>
        </div>
    </div>
</div>

</div>

<!-- Content Row -->
<div class="row" style="height: 80vh;">

<div class="col-xl-6 col-md-4 d-flex" style="height: 100%;">
    <!-- Bar Chart -->
    <div class="card shadow mb-4 " style="background-color: #e5e5e5; border-radius: 30px;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #e5e5e5; border-radius: 30px;">
            <h6 class="m-0 font-weight-bold text-primary">Cost</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="modal" data-target="#costosModal">
                <i class=""><img src="./../../svg/ajustes.svg" alt="Ajustes" style="cursor: pointer; width: 24px; height: 24px;">
                </i>

                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink1">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" data-toggle="modal" data-target="#costosModal">Filter Costs</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <div class="card-body ">
            <div id="totalmyBarChartCostos" class="mb-3 text-center font-weight-bold"></div> <!-- Contenedor para el total -->
           <!-- Loader -->
        <div id="loaderCostos" class="text-center" style="display: none;">
            <div class="spinner"></div>
        </div>
           
           
            <div class="chart-bar"> <!-- Establecer el tamaño del contenedor -->
                <canvas id="myBarChartCostos" style=" width: 100%; height: 100%; max-width: 100%; max-height: 100%;"></canvas> <!-- Ajustar el tamaño del canvas -->
            </div>
            <hr>

        </div>
    </div>
</div>


<div class="col-xl-6 col-md-4 d-flex" style="height: 100%;">
    <!-- Bar Chart 10 mas beneficio-->
    <div class="card shadow mb-4" style="background-color: #e5e5e5; border-radius: 30px;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #e5e5e5; border-radius: 30px;">
            <h6 class="m-0 font-weight-bold text-primary">Benefits</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink4" data-toggle="modal" data-target="#beneficiosModal" aria-haspopup="true" aria-expanded="false">
                    <i class=""><img src="./../../svg/ajustes.svg" alt="Ajustes" style="cursor: pointer; width: 24px; height: 24px;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink4">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" data-toggle="modal" data-target="#beneficiosModal">Filter</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="totalmyBarChartBeneficios" class="mb-3 text-center font-weight-bold"></div> <!-- Contenedor para el total -->
           
               <!-- Loader -->
        <div id="loaderBeneficios" class="text-center" style="display: none;">
            <div class="spinner"></div>
        </div>
           
            <div class="chart-bar">
                <canvas id="myBarChartBeneficios" style=" width: 100%; height: 100%; max-width: 100%; max-height: 100%;"></canvas>
            </div>
            <hr>

        </div>
    </div>
</div>


<div class="col-xl-6 col-md-4 d-flex" style="height: 100%;">
    <!-- Bar Chart autoconsumo-->
    <div class="card shadow mb-4" style="background-color: #e5e5e5; border-radius: 30px;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #e5e5e5; border-radius: 30px;">
            <h6 class="m-0 font-weight-bold text-primary">Self-consumption</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="modal" data-target="#autoconsumoModal" aria-haspopup="true" aria-expanded="false">
                    <i class=""><img src="./../../svg/ajustes.svg" alt="Ajustes" style="cursor: pointer; width: 24px; height: 24px;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink4">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" data-toggle="modal" data-target="#autoconsumoModal">Filter</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="totalmyBarChartAutoconsumo" class="mb-3 text-center font-weight-bold"></div> <!-- Contenedor para el total -->
           
                <!-- Loader -->
        <div id="loaderAutoconsumo" class="text-center" style="display: none;">
            <div class="spinner"></div>
        </div>
           
            <div class="chart-bar">
                <canvas id="myBarChartAutoconsumo" style=" width: 100%; height: 100%; max-width: 100%; max-height: 100%;"></canvas>
            </div>
            <hr>

        </div>
    </div>
</div>


</div>
<!-- Content Row -->
<div class="row" style="height: 70vh;">

<div class="col-xl-6 col-md-4 d-flex" style="height: 100%;">
    <!-- Bar Chart 10 menos vendidos-->
    <div class="card shadow mb-4" style="background-color: #e5e5e5; border-radius: 30px;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #e5e5e5; border-radius: 30px;">
            <h6 class="m-0 font-weight-bold text-primary">Stock E-commerce</h6>
            <div class="dropdown no-arrow">

               <!--
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink3" data-toggle="modal" data-target="#stockModal" aria-haspopup="true" aria-expanded="false">
                    <i class=""><img src="./../../svg/ajustes.svg" alt="Ajustes" style="cursor: pointer; width: 24px; height: 24px;"></i>
                </a>

                -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink4">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" data-toggle="modal" data-target="#stockModal">Filter</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <div class="card-body">
                 <!-- Loader -->
        <div id="loaderStock" class="text-center" style="display: none;">
            <div class="spinner"></div>
        </div>
            <div class="chart-bar">
                <canvas id="myBarChartStockEcommerce" style=" width: 100%; height: 100%; max-width: 100%; max-height: 100%;"></canvas>
            </div>
            <hr>

        </div>
    </div>
</div>



<!-- Pie Chart -->
<div class="col-xl-4 col-md-4 d-flex" style="height: 100%;">
    <div class="card shadow mb-4" style="background-color: #e5e5e5; border-radius: 30px;">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #e5e5e5; border-radius: 30px;">
            <h6 class="m-0 font-weight-bold text-primary">Expire</h6>

        </div>
        <!-- Card Body -->
        <div class="card-body" style="background-color: #e5e5e5; border-radius: 30px;">


             <!-- Loader -->
             <div id="loaderPie" class="text-center" style="display: none;">
            <div class="spinner"></div>
        </div>
            <div class="chart-pie pt-4 pb-2" style="position: relative; width: 100%; height: 0; padding-bottom: 100%; overflow: hidden;">
                <canvas id="productosCaducidadChart" style=" width: 100%; height: 100%;"></canvas>
            </div>
            <hr>

        </div>
    </div>
</div>


<div class="col-xl-6 col-md-4 d-flex" style="height: 100%;">

    <div class="card shadow mb-4" style="background-color: #e5e5e5; border-radius: 30px;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #e5e5e5; border-radius: 30px;">
            <h6 class="m-0 font-weight-bold text-primary">Expiration status</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class=""><img src="./../../svg/ajustes.svg" alt="Ajustes" style="cursor: pointer; width: 24px; height: 24px;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink5">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item-status" id="filtroCaducados">Expired</a>
                    <a class="dropdown-item-status" id="filtroA7Dias">7 days from expiration</a>
                    <a class="dropdown-item-status" id="filtroA15Dias">15 days from expiration</a>
                    <a class="dropdown-item-status" id="filtroMas15Dias">More than 15 days from expiration</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <div class="card-body">

             <!-- Loader -->
             <div id="loaderCaducidad" class="text-center" style="display: none;">
            <div class="spinner"></div>
        </div>
            <div class="chart-bar">
                <canvas id="estadoCaducidadChart" style=" width: 100%; height: 100%; max-width: 100%; max-height: 100%;"></canvas>
            </div>
            <hr>

        </div>
    </div>
</div>

</div>