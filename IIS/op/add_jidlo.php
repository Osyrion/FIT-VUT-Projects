<?php 
session_start();
include '../functions.php';
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
        <title>Přidať jídlo | Jidelna IS</title>
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
                    <h5 class="card-header">Vložení jídla do databázy</h5>
                    <div class="card-body">

					<form method="post" action="" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name">Název jídla</label>
							<input class="form-control" type="text" placeholder="Zadajte název jídla" name="nazev" required="required" id="name" />
						</div>
						<div class="form-group">
							<label for="cena">Cena</label>
							<input class="form-control" type="number" placeholder="Zadajte cenu" name="cena" required="required"/>
						</div>
						<div class="form-group">
							<label for="description">Popis jídla</label>
							<textarea class="form-control" id="description" rows="3" placeholder="Popište jídlo" name="popis"></textarea>
						</div>
						<div class="custom-file">
  							<input type="file" class="custom-file-input" id="ob" name="ob" />
  							<label class="custom-file-label" for="ob" data-browse="Vybrat soubor">Vyberte obrázek</label>
						</div>
						<div class="form-group mt-3">
							<select class="custom-select" name="typ">
								<option value="hlavni">Hlavni
								<option value="polevka">Polevka
							</select>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num1" type="checkbox" name="al1" value="1" />
							<label class="custom-control-label" for="num1">1</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num2" type="checkbox" name="al2" value="2" />
							<label class="custom-control-label" for="num2">2</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num3" type="checkbox" name="al3" value="3" />
							<label class="custom-control-label" for="num3">3</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num4" type="checkbox" name="al4" value="4" />
							<label class="custom-control-label" for="num4">4</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num5" type="checkbox" name="al5" value="5" />
							<label class="custom-control-label" for="num5">5</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num6" type="checkbox" name="al6" value="6" />
							<label class="custom-control-label" for="num6">6</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num7" type="checkbox" name="al7" value="7" />
							<label class="custom-control-label" for="num7">7</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num8" type="checkbox" name="al8" value="8" />
							<label class="custom-control-label" for="num8">8</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num9" type="checkbox" name="al9" value="9" />
							<label class="custom-control-label" for="num9">9</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num10" type="checkbox" name="al10" value="10" />
							<label class="custom-control-label" for="num10">10</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num11" type="checkbox" name="al11" value="11" />
							<label class="custom-control-label" for="num11">11</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num12" type="checkbox" name="al12" value="12" />
							<label class="custom-control-label" for="num12">12</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num13" type="checkbox" name="al13" value="13" />
							<label class="custom-control-label" for="num13">13</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" id="num14" type="checkbox" name="al14" value="14" />
							<label class="custom-control-label" for="num14">14</label>
						</div>
						<br>
			<input class="btn btn-primary float-right" type="submit" name="submit" value="Vložit" />
        </form>
		<?php 
			if(isset($_POST['submit'])){
				$nazev = filter_input(INPUT_POST, "nazev");
          		$popis = filter_input(INPUT_POST, "popis");
				$typ = filter_input(INPUT_POST, "typ");
				$cena = filter_input(INPUT_POST, "cena");
				$db = dbconnect();
				$sql = "INSERT INTO jidlo (nazev, popis, typ, cena) VALUES (?, ?, ?, ?)";
				if($stat = $db->prepare($sql)){
					$stat->bind_param("sssi", $nazev,$popis,$typ, $cena);
					$stat->execute();
					$jidlo_id = $stat->insert_id;
					$stat->close();
					echo "<p class='alert alert-success text-center border-success'>Jidlo úspěšné vloženo</p>";					
				}else{
					echo "<p class='alert alert-danger border-danger text-center'>Chyba ve vložení jídla do db!</p>";
				}	
				for ($i=1; $i <= 14; $i++) { 
					$al = filter_input(INPUT_POST, "al$i");
					if($al != 0){
						$sql_alergen = "INSERT INTO alergeny_v_jidle (alergen, jidlo) VALUES (?, ?)";
						if($alerg = $db->prepare($sql_alergen)){
							$alerg->bind_param("ii", $al, $jidlo_id);
							$alerg->execute();
							$al_id = $alerg->insert_id;
							$alerg->close();
							echo "<p class='alert alert-success border-success text-center'>Spojení $al_id úspěšně vytvořené : $al --- $jidlo_id </p>";
						}else{
							echo "<p class='alert alert-danger border-danger text-center'>Chyba ve vytvoření spojení alegenu s jídlem!</p>";
						}
					}
					$al = 0;
				}
				if (!$_FILES['ob']['size'] == 0 && $_FILES['ob']['error'] == 0){
					$dir = "../pic/$jidlo_id/";
					mkdir("../pic/$jidlo_id");
					$file = $dir . basename($_FILES["ob"]["name"]);
					$filetype = pathinfo($file, PATHINFO_EXTENSION);
					$filename = pathinfo($_FILES['ob']['name'], PATHINFO_FILENAME);
					if (move_uploaded_file($_FILES["ob"]["tmp_name"], $file)) {
						$slq = "UPDATE jidlo SET ob = ? WHERE id = $jidlo_id";
						if($asd = $db->prepare($slq)){
							$ob = $filename.".".$filetype;
							$asd->bind_param("s", $ob);
							$asd->execute();
							$asd->close();
						}
					}
				}
			}
		
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