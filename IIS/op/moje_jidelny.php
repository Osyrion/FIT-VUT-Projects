<?php 
session_start();
include '../functions.php';
if(isset($_SESSION['jmeno']) && isset($_SESSION['prava']) && isset($_SESSION['id'])){
    if($_SESSION['prava'] > 2){
        ?><script>
            window.location = "../index.php";
        </script><?php
    }
}else{
    ?><script>
        window.location = "../index.php";
    </script><?php
}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- META TAGS -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	    <meta name="Jidelna" content="IIS Project Jidelna" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous" />

        <!-- REGULAR CSS -->
        <link rel="stylesheet" href="../styles/styles.css" />

        <!-- FAVICON -->
		<link rel="icon" href="../pic/ico.ico" type="image/x-icon" />
        
        <!-- TITLE -->
        <title>Jídelny lístek | Jidelna IS</title>
	</head>
	<body>
        <nav class='mb-4 navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container'>
                <a class='navbar-brand' href='../index.php'><img src='../pic/logo/logo.png' /></a>
                    <button class='navbar-toggler' type='button' data-togle='collapse' data-target='#navbarSupportedContent-4' aria-controls='navbarSupportedContent-4' aria-expanded='false' aria-label='Toggle navigation'>
                        <span class='navbar-toggler-icon'></span>
                    </button>
                    <div class='collapse navbar-collapse' id='navbarSupportedContent-4'>
                        <ul class='navbar-nav ml-auto'>
                        <?php
                            if(isset($_SESSION['jmeno'])){
                                echo "
                                <li class='nav-link dropdown'>       
                                    <span class='nav-link dropdown-toggle' id='navbarDropdownMenuLink-4' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Přihlášen jako: 
                                    <b>".$_SESSION['jmeno']."</b>
                                    </span>    
                                    <div class='dropdown-menu dropdown-menu-right' aria-labelledby='navbarDropdownMenuLink-4'>
                                    ";
                                echo "<a class='dropdown-item' href=\"../account/user.php?user=".$_SESSION['id']."\">Můj účet</a>";
                                echo "<a class='dropdown-item' href='../account/moje_objednavky.php'>Moje objednávky</a>";
                            if($_SESSION['prava'] == 2){
                                echo "<a class='dropdown-item' href='../op/moje_jidelny.php'>Moje jídelny</a>";
                                echo "<a class='dropdown-item' href='../op/dat_zakazky.php'>Nové zakázky</a>";
                            }
                            if($_SESSION['prava'] <= 2){
                                echo "<a class='dropdown-item' href='../op/add_jidlo.php'>Vložení jídla do DB</a>";
                                if($_SESSION['prava'] == 1){
                                    echo "<a class='dropdown-item' href='../admin/accounts.php'>Účty</a>";
                                    echo "<a class='dropdown-item' href='../admin/add_jidelna.php'>Vložení jídelny do DB</a>";
                                    echo "<a class='dropdown-item' href='../admin/jidelny.php'>Jídelny</a>";
                                }
                            }
                            if($_SESSION['prava'] == 3) {
                                echo "<a class='dropdown-item' href='../ridic/zakazky.php?search=&f_akt=akt'>Zakázky</a>";
                            }
                            echo "<div class='dropdown-divider'></div>";
                            echo "<a class='dropdown-item' href='../account/login.php?action=off'>Odhlásit se</a>";
                            echo "</div></nav>";
                            }else{
                            echo "
                                <li class='nav-item'>
                                    <a class='nav-link' href='../account/register.php'><button class='btn btn-outline-info'>Registrace</button></a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='../account/login.php'><button class='btn btn-outline-warning'>Přihlášení</button></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                ";
            }
        ?>
    <main class="container">
        <section class="row justify-content-md-center">
            <div class="col col-md-12">
                <div class="card shadow-lg border-dark">
                    <h5 class="card-header">Výpis provozoven</h5>
                    <div class="col col-md-5 mt-4">
                        <a class="float-left badge badge-light" href="./moje_jidelny.php">Všechny jídelny</a>
                        <br>
                        <form method="get" class="mb-5" action="./moje_jidelny.php" enctype="multipart/form-data">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" id="srch" placeholder="Zadejte název provozovny" />
                                <div class="input-group-append">
                                    <input type="submit" value="Hledat" class="btn btn-primary float-right" id="srch" />
                                </div>
                            </div>
                        </form>
                        <?php
                            $offset = 0;
                            $stranka = "./moje_jidelny.php?";
                            if(isset($_GET['page'])){
                                if($page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT))
                                    $offset = ($page * 10) - 10;
                                if($offset < 0)
                                    $offset = 0;
                            }
                            echo "<span>Výpis jídelen $offset - ".($offset+10) . "</span>";
                        ?>
                </div>
                    <div class="card-body">
        
        <?php
            $moje_id = $_SESSION['id'];            
            if(isset($_GET['search'])){
                $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_STRING);
                $stranka = "./moje_jidelny.php?search=$search&";
                $sql = "SELECT id, nazev, mesto, adresa, stav FROM jidelna WHERE (nazev LIKE '%$search%' OR adresa LIKE '%$search%') AND operator = $moje_id LIMIT 10 OFFSET $offset";
                $sql2 = "SELECT COUNT(*) FROM jidelna WHERE (nazev LIKE '%$search%' OR adresa LIKE '%$search%') AND operator = $moje_id";
            }else{
                $sql = "SELECT id, nazev, mesto, adresa, stav FROM jidelna WHERE operator = $moje_id LIMIT 10 OFFSET $offset";
                $sql2 = "SELECT COUNT(*) FROM jidelna WHERE operator = $moje_id";
            }
            $db = dbconnect();
            if($num_of_my_jidelna = $db->prepare($sql2)){
                $num_of_my_jidelna->execute();
                $num_of_my_jidelna->bind_result($count);
                $number = 0;
                if($num_of_my_jidelna->fetch())
                    $number = $count;
            }
            $num_of_my_jidelna->close();
            $load_my_jidelna = $db->query($sql);
            $porad = $offset+1;
            if($load_my_jidelna->num_rows>0){
                echo "
                    <table class='table table-responsive table-hover mt-4'>";
                    echo "
                        <thead>
                            <tr>
                                <th scope='col'>Pořadí</th>
                                <th scope='col'>Název</th>
                                <th scope='col'>Města dovozu</th>
                                <th scope='col'>Mesto</th>
                                <th scope='col'>Adresa</th>
                                <th scope='col'>Připravena</th>
                                <th scope='col'>Upravit</th>
                                <th scope='col'>Jídelníček</th>
                            </tr>
                        </thead>
                    "; 
    			while($row = $load_my_jidelna->fetch_assoc()){
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<th scope='row'>".$porad++."</th>";
                    echo "<td>".$row['nazev']."</td>";
                    echo "<td>".getMestaDovozu($row['id'])."</td>";
                    echo "<td>".$row['mesto']."</td>";
                    echo "<td>".$row['adresa']."</td>";
                    if ($row['stav'] == 0)
                        echo "<td>Ne</td>";
                    else
                        echo "<td>Ano</td>";
                    echo "<td><a class='badge badge-light' href='./jidelna.php?jidelna=".$row['id']."'>Upravit</a></td>";
                    echo "<td><a class='badge badge-light' href='./manage_jidelnicek.php?jidelna=".$row['id']."'>Upravit jídelníček</a></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";                
            }else{
                echo "<p class='alert alert-danger text-center border-danger my-4'>Nepodařilo se načíst žádné jídelny!</p>";
            }
            $load_my_jidelna->close();
            $db->close();

            strankovani($number, $offset, $stranka);
        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="mt-4">
        <div class="bg-dark p-2 text-center text-white footer">
            Zer.to IIS Projekt | 2019 FIT VUT
        </div>
	</footer>
	
	<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
        
    <!-- FONT AWESOME -->
        <script src="https://kit.fontawesome.com/9e04c8ca52.js" crossorigin="anonymous"></script>
	</body>
</html>