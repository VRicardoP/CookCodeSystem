<?php 
    include_once $_SERVER["DOCUMENT_ROOT"] . '/restaurant/php/verifyLogged.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="./css/dashboard.css">
  
     <link rel="stylesheet" href="./css/tickets.css">
</head>
<body>
    <nav>
        <div>
            <img src="./img/ccsLogoWhite.png" alt="Cook Code System Logo">
            <button>III</button>
        </div>
        <ul>
            <a href="dashboard.php"><li> <object data="./svg/dashboard.svg" type="image/svg+xml"></object>Dashboard</li></a>
            <a href="analytics.php"><li> <object data="./svg/graph.svg" type=""></object>Analytics</li></a>
            <a href="stock.php"><li> <object data="./svg/stock.svg" type=""></object>Stock</li></a>
            <a href="receipts.php"><li> <object data="./svg/receipt.svg" type=""></object>Pre-prepareds</li></a>
           
            <a href="dishes.php"><li> <object data="./svg/receipt.svg" type=""></object>Dishes</li></a>
            <a href="orders.php"><li> <object data="./svg/orders.svg" type=""></object>Orders</li></a>
            <a href="/restaurant/TPV/mesas.html"><li> <object data="./svg/tpv.svg" type=""></object>TPV</li></a>
             <a href="tickets.php"><li> <object data="./svg/historial_tickets.svg" type=""></object>Tickets</li></a>
            <a href="settings.php"><li> <object data="./svg/settings.svg" type=""></object>Settings</li></a>   
        </ul>
    </nav>

    <main>
        <section id="historial-comandas">
    <h2>Historial de Tickets</h2>
    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Mesa</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody id="tablaHistorial">
            <!-- Aquí se cargan dinámicamente los tickets -->
        </tbody>
    </table>
</section>
       
    </main>

    <script src="js/mostrarTickets.js" ></script>
</body>
</html>