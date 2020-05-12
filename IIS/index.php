<?php 
session_start();
include './functions.php';
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
        <link rel="stylesheet" href="./styles/styles.css" />

        <!-- FAVICON -->
		<link rel="icon" href="./pic/ico.ico" type="image/x-icon" />
        
        <!-- TITLE -->
        <title>Jidelna</title>
	</head>
	<body>
        <nav class='mb-4 navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container'>
                <a class='navbar-brand' href='index.php'><img src='./pic/logo/logo.png' /></a>
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

                        echo "<a class='dropdown-item' href=\"./account/user.php?user=".$_SESSION['id']."\">Můj účet</a>";
                        echo "<a class='dropdown-item' href='./account/moje_objednavky.php'>Moje objednávky</a>";
                if($_SESSION['prava'] == 2){
                    echo "<a class='dropdown-item' href='./op/moje_jidelny.php'>Moje jídelny</a>";
                    echo "<a class='dropdown-item' href='./op/dat_zakazky.php'>Nové zakázky</a>";
                }
                if($_SESSION['prava'] <= 2){
                    echo "<a class='dropdown-item' href='./op/add_jidlo.php'>Vložení jídla do DB</a>";
                    if($_SESSION['prava'] == 1){
                        echo "<a class='dropdown-item' href='./admin/accounts.php'>Účty</a>";
                        echo "<a class='dropdown-item' href='./admin/add_jidelna.php'>Vložení jídelny do DB</a>";
                        echo "<a class='dropdown-item' href='./admin/jidelny.php'>Jídelny</a>";
                    }
                }
                if($_SESSION['prava'] == 3) {
                    echo "<a class='dropdown-item' href='./ridic/zakazky.php?search=&f_akt=akt'>Zakázky</a>";
                }
                echo "<div class='dropdown-divider'></div>";
                echo "<a class='dropdown-item' href='./account/login.php?action=off'>Odhlásit se</a>";
                echo "</div></nav>";
            }else{
                echo "

                                <li class='nav-item'>
                                    <a class='nav-link' href='./account/register.php'><button class='btn btn-outline-info'>Registrace</button></a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='./account/login.php'><button class='btn btn-outline-warning'>Přihlášení</button></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                ";
            }
            echo "<main>";
            echo "<section class='container'>";
            echo "<div class='d-flex justify-content-center mb-4'>";
            echo "<a href='./jidlo/objednavka.php'><button class='btn btn-primary shadow'>Najít objednávku</button></a></div>";
            $db = dbconnect();
            $sql = "SELECT id, nazev, adresa, mesto FROM jidelna WHERE stav = 1";
            $jidelny = $db->query($sql);
            if($jidelny->num_rows>0){
    			while($row = $jidelny->fetch_assoc()){
                    echo "<article class='card p-4 mb-2 border-dark bg-light shadow-lg'>";
                    echo "<a class='text-decoration-none text-dark' href='./jidlo/jidelnicek.php?jidelna=".$row['id']."' '>";
                    echo "<b>".$row['nazev']."</b>";
                    echo "<p>Města dovozu - ".getMestaDovozu($row['id'])."</p>";
                    echo "<p>Adresa - ".$row['mesto']." ".$row['adresa']."</p>";
                    echo "</a></article>";
                }               
            }else{
                echo "<div class='alert alert-danger text-center m-4 border-danger' role='alert'>Nepodařilo se načíst žádné jídelny!</div>";
            }
            echo "</section>";

        ?>
    </main>
    <footer class="mt-4">
        <section class="container">
            <div class="row justify-content-md-center mt-4">
                <div class="col col-md-6">
                    <table class="table table-striped table-dark mt-4">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Účet</th>
                                <th scope="col">Email</th>
                                <th scope="col">Heslo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th scope="row">1</th>
                                <td>Admin</td>
                                <td>admin@jidelna.cz</td>
                                <td>admin</td>
                            </tr>
                            <tr>
                            <th scope="row">2</th>
                                <td>Operátor</td>
                                <td>LadNov@jidelna.cz</td>
                                <td>heslo</td>
                            </tr>
                            <tr>
                            <th scope="row">3</th>
                                <td>Řidič</td>
                                <td>novak@jidelna.cz</td>
                                <td>heslo</td>
                            </tr>
                            <tr>
                            <th scope="row">4</th>
                                <td>Konzument</td>
                                <td>novy@jidelna.cz</td>
                                <td>heslo</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    
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
