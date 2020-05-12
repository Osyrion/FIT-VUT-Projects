<?php 
session_start();
include '../functions.php';
if(isset($_SESSION['jmeno']) && isset($_SESSION['prava'])){
    if($_SESSION['prava'] != 1){
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
        <title>Pridať jídelnu | Jidelna IS</title>
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
            <div class="col col-md-6">
                <div class="card shadow-lg border-dark">
                    <h5 class="card-header">Vložení jídelny do databázy</h5>
                    <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="restaurant">Název provozovny</label>
                            <input type="text" placeholder="Zadajte název jídelny" name="nazev" class="form-control" id="restaurant" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="address">Adresa provozovny</label>
                            <input class="form-control" placeholder="Zadajte adresu jídelny" id="address" type="text" name="adresa" />
                        </div>
			            <select class="custom-select mb-4" name="operator">
                            <option selected>Vyberte meno</option>
                                <?php
                                    $db = dbconnect();                
                                    $sql_op = "SELECT id, jmeno, prijmeni FROM user WHERE prava = 2";
                                    $operators = $db->query($sql_op);
                                    if ($operators->num_rows > 0) {
                                        while ($row = $operators->fetch_assoc()) {
                                                echo "<option value=\"".$row["id"]."\">".$row["jmeno"]." ".$row['prijmeni']. "</option>";
                                        }
                                    }
                                    $operators->close();
                                ?>
                        </select>
                        <select class="custom-select mb-4" name="mesto">
                            <option selected>Vyberte mesto</option>
                                <?php
                                    $sql_mesta = "SELECT nazev FROM mesta";
                                    $mesta = $db->query($sql_mesta);
                                    if ($mesta->num_rows > 0) {
                                        while ($row = $mesta->fetch_assoc()) {
                                                echo "<option value=\"".$row["nazev"]."\">".$row["nazev"]."</option>";
                                        }
                                    }
                                    $mesta->close();
                                ?>
                        </select>
                        <input type="submit" name="submit" value="Vložit jídelnu" class="btn btn-primary float-right">
                    </form>
		<?php 
			if(isset($_POST['submit'])){
				$nazev = filter_input(INPUT_POST, "nazev");
				$mesto = filter_input(INPUT_POST, "mesto");
                $operator = filter_input(INPUT_POST, "operator");
                $adresa = filter_input(INPUT_POST, "adresa");
                $sql = "INSERT INTO jidelna (nazev, mesto, adresa, operator) VALUES (?, ?, ?, ?)";
				if($stat = $db->prepare($sql)){
					$stat->bind_param("sssi", $nazev, $mesto, $adresa, $operator);
					$stat->execute();
                    $stat->close();
                echo "
                <br>
                <p class='my-4 alert alert-success text-center border-success'>Jídelna byla úspešne vložena!</p>
                ";
				}
			}
		    $db->close();
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