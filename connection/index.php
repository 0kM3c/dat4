<?php session_start();//obligatoire pour utiliser les sessions
$page="";
?>
<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<title>Connexion</title>
		<link rel="stylesheet" href="index.css">
	</head>
	<body>
		<?php // si absence de session on affiche formulaire
			if(empty($_SESSION)){
		?>
		<section>
            <h1>Connexion à votre compte</h1>
            <form id="conn" method="post" action="">
                <label for="login">Compte :</label>
                <input type="text" id="login" name="login" required/><br><br>

                <label for="pass">Mot de Passe :</label>
                <input type="password" id="pass" name="pass" required/>
				<input type="checkbox" onclick="pass.type=this.checked?'text':'password'"> <br><br>

                <input type="submit" id="submit" name="submit" value="Connexion" />
            </form>
        </section>
		<?php
			}//FIN DU IF
			else echo'<p><a href="index.php?action=logout">Se déconnecter</a></p>';
		?>
		<?php	// traitement demande de connexion
			if (!empty($_POST) && isset($_POST['login']) && isset($_POST['pass']))	{		
				if ( authentification($_POST["login"],$_POST["pass"]) )		{
					// si authentification correcte, on ouvre une session
					$_SESSION["user"]=$_POST['login'];
					// si admin => session prof
					if ($_SESSION["user"]=='admin'){
						$page='centre.html';
					}
					if ($_SESSION["user"]=='hubert'){
						$page='./site_web/';
					}
					if ($_SESSION["user"]=='nolwenn'){
						$page='./stephane/';
					}
					redirect($page,0); 					
					// Affichage message
				}
				else echo"<p class='p'>Erreur d'authentification</p> <style>.p{color:red;}</style>";
			}
			function redirect($url,$tps){
				$temps = $tps * 1000;
				echo "<script type=\"text/javascript\">\n"
				. "<!--\n"
				. "\n"
				. "function redirect() {\n"
				. "window.location='" . $url . "'\n"
				. "}\n"
				. "setTimeout('redirect()','" . $temps ."');\n"
				. "\n"
				. "// -->\n"
				. "</script>\n";
			}
		?>
		
		<?php
			// traitement de la fermeture de la session
			if(!empty($_GET)&& isset($_GET['action'])&& $_GET['action']=='logout'){
				session_destroy();
				$_SESSION=array();//on vide le tableau de sessions
			}
			
			
			//****************************************************************************************   
			function authentification($login,$pass){ 
				try {
					$retour = false;
					$madb = new PDO('sqlite:bdd/IUT.sqlite');			
					$requete = "SELECT * FROM etudiants WHERE mail = '".$login."' AND mdp = '".$pass."'";
					//var_dump($requete);
					$resultat = $madb->query($requete);
					$tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
					//var_dump($tableau_assoc );	
					if (sizeof($tableau_assoc)!=0) {	// s'il y a une réponse	=> utilisateur éxiste
						$retour = true;
					}// fin if
				}// fin try
				catch (Exception $e) {		
					echo "Erreur BDD" . $e->getMessage();		
				}	// fin catch	
				return $retour;
			}	
		?>
		</body>
		</html>		