<html>
	<head>
		<title>Pagina iniziale</title>
	<head>
	<body>
		<?php
			$host_name='localhost';
			$user_name='root';
			$conn=@mysql_connect($host_name,$user_name,'')
				or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
			@mysql_select_db('tabelleArbitrarie')
				or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
			
			//query che conta quante tabelle ci sono nel database 'tabelleArbitrarie'
			$query="select count(*)
					from information_schema.tables
					where table_schema='tabelleArbitrarie'";
					
			$risultato=mysql_query($query)
				or die("Impossibile contare le tabelle del database: ".mysql_error());
			while($riga=mysql_fetch_row($risultato))
			{
				$quanteTabelle=$riga[0];
			}
			echo("<h2><center><font color='grey'>Programma di confronto tra il contenuto di due tabelle</font></center></h2>");
			if($quanteTabelle==0) // se non ci sono tabelle nel database
			{
				echo("<fieldset>");
				echo("<table border=0 width='100%'");
					echo("<tr>");
						echo("<td width='33%'>&nbsp;</td>");
						echo("<td width='34%'>
								<div align='center'><b><font size=4>Determina il numero dei campi per la<br>
								creazione delle due tabelle da confrontare</font></b></div>
								<br>
								<form name='struttura' action='form.php' method='POST'>
									<center>
									<select name='NumCampi'>
										<option value=1>1
										<option value=2>2
										<option value=3>3
										<option value=4>4
										<option value=5>5
										<option value=6>6
										<option value=7>7
										<option value=8>8
										<option value=9>9
										<option value=10>10
										<option value=11>11
										<option value=12>12
										<option value=13>13
										<option value=14>14
										<option value=15>15
										<option value=16>16
										<option value=17>17
										<option value=18>18
										<option value=19>19
										<option value=20>20
									</select>
									</center>
									<br>
									<center><input type='submit' name='invio' value='Continua'></center>
								</form>
							  </td>");
						echo("<td width='33%'>&nbsp;</td>");
					echo("</tr>");
				echo("</table>");
				echo("</fieldset>");
			}
			else // se ci sono tabelle nel database
			{
				$query="show tables";
				$risultato=mysql_query($query)
					or die ("Impossibile estrarre i nomi delle tabelle presenti nel database <i>tabelleArbitrarie</i>: ".mysql_error());
				$i=0;
				//$quanti=0;
				while($riga=mysql_fetch_row($risultato))
				{
					$query="select count(*) from ".$riga[0];
					//echo("Testo della query: ".$query."<br>");
					$risultato2=mysql_query($query)
						or die("Impossibile contare gli elementi contenuti nella tabella ".$riga[0].": ".mysql_error());
					while($riga2=mysql_fetch_row($risultato2))
						$quanti[$i]=$riga[0];
					$i++;
				}
				if(($quanti[0]>0) and (($quanti[1]>0))) // se c'è almeno un elemento in entrambe le tabelle
				{
					//echo("<h2><center><font color='grey'>Programma di confronto tra il contenuto di due tabelle</font></center></h2>");
					echo("<ul>");
						echo("<li>Accedi alla pagina di creazione delle tabelle (passo gi&agrave; eseguito)</li>");
						echo("<li>Accedi alla pagina di popolamento delle tabelle con i dati da confrontare (passo gi&agrave; eseguito)</li>");
						echo("<li><a href='ConfrontoTabelle.php'>Componi il confronto tra le tabelle caricate</a></li>");
						echo("<li><a href='CancellazioneTabelle.php'>Cancella il contenuto delle tabelle ed elimina le tabelle stesse</a></li>");
					echo("</ul>");
				}
				else
				{
					//echo("<h2><center><font color='grey'>Programma di confronto tra il contenuto di due tabelle</font></center></h2>");
					echo("<ul>");
						echo("<li>Accedi alla pagina di creazione delle tabelle (passo gi&agrave; eseguito)</li>");
						echo("<li><a href='sceltaCampi.php'>Scegli i campi delle tabelle su cui effettuare il confronto</a></li>");
						echo("<li>Componi il confronto tra le tabelle caricate (passo non ancora eseguibile)</li>");
						echo("<li><a href='CancellazioneTabelle.php'>Cancella il contenuto delle tabelle ed elimina le tabelle stesse</a></li>");
					echo("</ul>");
				}
			}
			mysql_close($conn);
		?>
	</body>
<html>