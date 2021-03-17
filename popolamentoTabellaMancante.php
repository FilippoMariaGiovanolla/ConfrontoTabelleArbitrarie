<html>
	<head>
		<title>Popolamento tabella mancante</title>
	</head>
	<body>
		<?php
			$host_name='localhost';
			$user_name='root';
			$conn=@mysql_connect($host_name,$user_name,'')
				or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
			@mysql_select_db('tabellearbitrarie')
				or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
			
			$tabellaVuota=$_POST["tabellaVuota"];
			//echo("La tabella da popolare &egrave; la tabella ".$tabellaVuota."<br>");
			
			echo("<center><h2>Caricamento dati tabella <i>".$tabellaVuota."</i></h2></center>");
			echo("<fieldset>");
				echo("<form name='upload' method='post' action='UploadTabellaMancante.php' enctype='multipart/form-data'>");
				echo("<input type='hidden' name='TabellaVuota' value='$tabellaVuota'");
				echo("<table border=0>");
					echo("<tr>");
						echo("<td>Carica il file csv con i dati della tabella <b>".strtoupper($tabellaVuota)."</b> (utilizzare il <b>tabulatore</b> come carattere separatore)</td>");
						echo("<td>&nbsp;&nbsp;&nbsp;<input type='file' name='uploadfile'></td>");
					echo("</tr>");
				echo("</table>");
				echo("<br><br>");
				echo("<input type='submit' name='go' value='Continua'>");
				echo("</form>");
			echo("</fieldset>");
			
			mysql_close($conn);
		?>
	</body>
</html>