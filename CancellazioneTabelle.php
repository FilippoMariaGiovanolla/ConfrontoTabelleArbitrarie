<html>
	<head>
		<title>Cancellazione tabelle</title>
	</head>
	<body>
		<?php
			$host_name='localhost';
			$user_name='root';
			$conn=@mysql_connect($host_name,$user_name,'')
				or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
			@mysql_select_db('tabelleArbitrarie')
				or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
			$query="show tables";
			$risultato=mysql_query($query)
				or die("Impossibile estrarre i nomi delle tabelle presenti nel database: ".mysql_error());
			while($riga=mysql_fetch_row($risultato))
			{
				$query="drop table ".$riga[0];
				$risultato2=mysql_query($query)
					or die("Impossibile cancellare la tabella ".$riga[0].": ".mysql_error());
			}
			echo("<h3>Tabelle correttamente cancellate</h3>");
			mysql_close($conn);
		?>
		<br>
		<a href="index.php">Torna alla pagina iniziale</a>
	</body>
</html>