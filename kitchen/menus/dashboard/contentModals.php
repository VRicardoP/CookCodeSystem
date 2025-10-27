<div class="modal fade" id="costosModal" tabindex="-1" role="dialog" aria-labelledby="costosModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="costosModalLabel">Select Dishes for Costs</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Filtros de tiempo -->
                            <div class="form-group">
                                <label for="filtroCostos">Filtrar por:</label>
                                <select class="form-control" id="filtroCostos">
                                    <option value="todos">Todos</option>
                                    <option value="semana">Semana</option>
                                    <option value="mes">Mes</option>
                                    <option value="año">Año</option>
                                </select>
                            </div>
                            <div id="checkboxContainerCostos">


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="consultarCostosBtn" class="btn btn-primary">Consultar Selección</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para seleccionar filtros de Beneficios -->
            <div class="modal fade" id="beneficiosModal" tabindex="-1" role="dialog" aria-labelledby="beneficiosModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="beneficiosModalLabel">Select Dishes</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Filtros de tiempo -->
                            <div class="form-group">
                                <label for="filtroBeneficios">Filtrar por:</label>
                                <select class="form-control" id="filtroBeneficios">
                                    <option value="todos">Todos</option>
                                    <option value="semana">Semana</option>
                                    <option value="mes">Mes</option>
                                    <option value="año">Año</option>
                                </select>
                            </div>
                            <div id="checkboxContainerBeneficios"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="consultarBeneficiosBtn">Consultar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para seleccionar filtros de Ventas -->
            <div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="stockModalLabel">Select Dishes</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Filtros de tiempo -->

                            <div id="checkboxContainerStock">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="consultarStockBtn" class="btn btn-primary">Consultar Selección</button>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal para seleccionar filtros de Autoconsumo -->
            <div class="modal fade" id="autoconsumoModal" tabindex="-1" role="dialog" aria-labelledby="autoconsumoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="autoconsumoModalLabel">Select Dishes </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Filtros de tiempo -->

                            <div id="checkboxContainerAutoconsumo">


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="consultarAutoconsumoBtn" class="btn btn-primary">Consultar Selección</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para mostrar productos en pie -->
            <div class="modal fade" id="pieModal" tabindex="-1" role="dialog" aria-labelledby="pieModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="width: 130%; color:#000">
                        <div class="modal-header" id="headerPie">
                            <h5 class="modal-title" id="pieModalLabel"> Products </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">


                            <div id="containerPie" style=" color:#000">


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para mostrar producto en status -->
            <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="width: 110%; color:#000">
                        <div class="modal-header" id="headerStatus">
                            <h5 class="modal-title" id="statusModalLabel"> Products </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div id="containerstatus">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                        </div>
                    </div>
                </div>
            </div>
