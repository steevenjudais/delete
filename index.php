<?php
function base()
 {
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "rtlry";
 $db = "todoo";
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname='.$db.';charset=utf8', $dbuser, $dbpass);
	}
	catch (Exception $e)
	{
			die('Erreur : ' . $e->getMessage());
	}
	if (isset($_GET["id"])){
        $stmt1 = $bdd->prepare("DELETE FROM liste WHERE id=(:id)");
        $stmt1->bindParam('id', $_GET['id']);
		$stmt1->execute();
    }

	
	if (isset($_POST["tache"])){
        $stmt2 = $bdd->prepare("INSERT INTO liste (tache) VALUES (:tache)");
        $stmt2->bindParam('tache', $_POST['tache']);
        $stmt2->execute();
    }
    
    # Faille de sécurité pour supprimer tout les enregistrements de la base
    # Ajouter "true');DELETE FROM liste;--" dans texte
    # Ou
    # Ajouter "or true" dans url
	
	$reponse = $bdd->query('SELECT * FROM liste');
	
	while ($donnees = $reponse->fetch())
	{
		echo "<li><a id=\"croix\" onclick=\"remove(".$donnees['id'].")\" href=\"#\">x</a>".$donnees['tache']."</li><br/>";
	}
}
?>

<html>
	<head>
		<style>
			#croix{
				color:white;
				text-decoration:none;
				right:5%;
				background-color:red;
				padding-right:5px;
				padding-left:5px;
			}
			ul{
				list-style:none;
			}
		</style>
	</head>
	<body> 
		<h1>TODOO</h1>
		<form action="index.php" method="post">
					 <input type="text" name="tache"/>
					 <input type="submit" value="Ajouter">
		</form>
		<p>
			<ul>
			<?php
			base();
			?>
			</ul>
        </p>
        <script>
            function remove(id) {
				var dostuff;
                var xhr1 = new XMLHttpRequest();
                xhr1.open('DELETE', `http://localhost/index.php?id=${id}`, true);
                xhr1.onreadystatechange = function() {
                	if (this.status == 200 && this.readyState == 4) {
                    	dostuff = this.responseText;
                	};
                }
				xhr1.send();
            }
        </script>
    </body>
</html>
