<html>
	<head>
		<title>Esecuzione confronto</title>
	</head>
	<body>
		<?php
			$host_name='localhost';
			$user_name='root';
			$conn=@mysql_connect($host_name,$user_name,'')
				or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
			@mysql_select_db('tabellearbitrarie')
				or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
			
			
			//recupero il nome delle due tabelle su cui devo fare il confronto
			$nomiTabelle=$_POST["NomiTabelle"];
			//echo("Nomi Tabelle: ".$nomiTabelle."<br>");
			$tabelle=explode(' ',$nomiTabelle);
			$tabella1=$tabelle[0];
			$tabella2=$tabelle[1];
			//echo("Tabella 1: ".$tabella1."<br>");
			//echo("Tabella 2: ".$tabella2."<br>");
			//$tabella2=$_POST["tabella2"];
			//echo("Tabella 2: ".$tabella2);
			
			$query="select * from campiperconfronto";
			$risultato=mysql_query($query)
				or die("Impossibile accedere ai dati della tabella <i>CampiPerConfronto</i>: ".mysql_error());
			$numCampiConfronto=0;
			while($riga=mysql_fetch_row($risultato))
			{
				$campiPerConfronto[$numCampiConfronto]=$riga[0];
				$numCampiConfronto++;
			}
			
			$query="select * from campiuguali";
			$risultato=mysql_query($query)
				or die("Impossibile estrarre i dati dalla tabella <i>CampiUguali</i>: ".mysql_error());
			$numCampiUguali=0;
			while($riga=mysql_fetch_row($risultato))
			{
				$campiUguali[$numCampiUguali]=$riga[0];
				$numCampiUguali++;
			}
			
			//ora costruisco la query per effettuare il confronto tra i dati delle tabelle
			$stringaCampiUguali=' ';
			for($i=0;$i<$numCampiUguali;$i++)
				if($campiUguali[$i]!='Progressivo')
					$stringaCampiUguali=$stringaCampiUguali."a.".$campiUguali[$i].",";
			//$lunghezzaStringaCampiUguali=strlen($stringaCampiUguali);
			//$stringaCampiUguali=substr($stringaCampiUguali,0,$lunghezzaStringaCampiUguali-1);
			$stringaCampiPerConfronto='';
			for($i=0;$i<$numCampiConfronto;$i++)
				$stringaCampiPerConfronto=$stringaCampiPerConfronto." a.".$campiPerConfronto[$i]." as '".$tabella1.".".$campiPerConfronto[$i]."', b.".$campiPerConfronto[$i]." as '".$tabella2.".".$campiPerConfronto[$i]."',";
			$lunghezzaStringaCampiPerConfronto=strlen($stringaCampiPerConfronto);
			$stringaCampiPerConfronto=substr($stringaCampiPerConfronto,0,$lunghezzaStringaCampiPerConfronto-1);
			$query="select ".$stringaCampiUguali.$stringaCampiPerConfronto." from ".$tabella1." a, ".$tabella2." b where ";
			for($i=0;$i<$numCampiUguali;$i++)
				if($campiUguali[$i]!='Progressivo')
					$query=$query."a.".$campiUguali[$i]."=b.".$campiUguali[$i]." and ";
			$query=$query."(";
			for($i=0;$i<$numCampiConfronto;$i++)
				$query=$query."a.".$campiPerConfronto[$i]."!=b.".$campiPerConfronto[$i]." or ";
			$lunghezzaQuery=strlen($query);
			$query=substr($query,0,$lunghezzaQuery-3);
			$query=$query.")";
			//echo("Query di confronto:<br>".$query."<br><br>");
			
			$risultato=mysql_query($query)
				or die("Impossibile eseguire la query per effettuare il confronto sul contenuto delle tabelle: ".mysql_error());
			$numColonne=mysql_num_fields($risultato);
			$numRighe=mysql_num_rows($risultato);
			
			if($numRighe>0)
			{
				//mando video il risultato della query appena elaborata
				echo("<center><font size='6'><b>Ecco il risultato del confronto tra le tabelle caricate</b></font></center>");
				echo("<br><br>
				<font size='5'><b>Record presenti in entrambe le tabelle, ma con dati differenti</b></font><br><br>");
				echo("<table border=1>");
					echo("<tr>");
						for($i=0;$i<$numCampiUguali;$i++)
							if($campiUguali[$i]!='Progressivo')
							{
								$lunghezzaCampo=strlen($campiUguali[$i]);
								echo("<td><b>".substr($campiUguali[$i],1,$lunghezzaCampo)."</b></td>");
							}
						for($i=0;$i<$numCampiConfronto;$i++)
						{
							$lunghezzaCampo=strlen($campiPerConfronto[$i]);
							echo("<td><b><font color='blue'>".strtoupper($tabella1).".</font>".substr($campiPerConfronto[$i],1,$lunghezzaCampo)."</b></td><td><b><font color='blue'>".strtoupper($tabella2).".</font>".substr($campiPerConfronto[$i],1,$lunghezzaCampo)."</b></td>");
						}
					echo("</tr>");
				//echo("Numero colonne risultato: ".$numColonneRisultato."<br>");
				while($riga=mysql_fetch_row($risultato))
				{
					echo("<tr>");
					
					//mando a video ogni campo uguale
					for($i=0;$i<$numCampiUguali-1;$i++)
						echo("<td>".$riga[$i]."</td>");
					
					// mando a video ogni campo potenzialmente diverso; se i campi sono effettivamente diversi, li mando a video rossi, altrimenti neri
					for($k=$numCampiUguali;$k<$numColonne;$k++)
					{
						if($i%2==1) // se $i è dispari
						{
							if($k%2==0) // se $k è pari; se $k è dispari non faccio nulla volutamente, per cui non mi sono dimenticato l'else
								if($riga[$k-1]==$riga[$k])
									echo("<td>".$riga[$k-1]."</td><td>".$riga[$k]."</td>");
								elseif($riga[$k-1]!=$riga[$k])
									echo("<td><font color='red'><b>".$riga[$k-1]."</b></font></td><td><font color='red'><b>".$riga[$k]."</b></font></td>");
						}
						else // se $i è pari
						{
							if($k%2==1) // se $k è dispari; se $k è pari non faccio nulla volutamente, per cui non mi sono dimenticato l'else
								if($riga[$k-1]==$riga[$k])
									echo("<td>".$riga[$k-1]."</td><td>".$riga[$k]."</td>");
								elseif($riga[$k-1]!=$riga[$k])
									echo("<td><font color='red'><b>".$riga[$k-1]."</b></font></td><td><font color='red'><b>".$riga[$k]."</b></font></td>");
						}
					}
					
					echo("</tr>");
				}
				echo("</table>"); // fine presentazione delle differenze tra record presenti in entrambe le tabelle
				echo("<br>");


				//inizio presentazione differenze per record non presenti in entrambe le tabelle
				$ConcatCampiUguali='';
				for($i=0;$i<$numCampiUguali;$i++)
				{
					if($i<$numCampiUguali-1)
						$ConcatCampiUguali=$ConcatCampiUguali.$campiUguali[$i].',';
					else
						$ConcatCampiUguali=$ConcatCampiUguali.$campiUguali[$i];
				}
				
				//record presenti nella prima tabella e non nella seconda
				$query="select count(*) 
						from ".$tabella1." 
						where concat(".$ConcatCampiUguali.") not in( 
							select concat(".$ConcatCampiUguali.") 
							from ".$tabella2.")";
				$risultato=mysql_query($query)
					or die("Impossibile contare quanti record sono presenti nella tabella <i>".$tabella1."</i> e non nella tabella <i>".$tabella2."</i>: ".mysql_error());
				while($riga=mysql_fetch_row($risultato))
					$quanti=$riga[0];
				if($quanti==0)
					echo("<font size='5'><b>Tutti i record presenti nella tabella <i>".$tabella1."</i> sono presenti anche nella tabella <i>".$tabella2."</i></b></font><br><br>");
				else
					{
						echo("<font size='5'><b>Record della tabella <i>".$tabella1."</i> che non sono presenti nella tabella <i>".$tabella2."</i></b></font><br><br>");
						echo("<table border=1>");
							$query="describe ".$tabella1;
							$risultato=mysql_query($query)
								or die("Impossibile fare la describe della tabella <i>".$tabella1."</i>: ".mysql_error());
							echo("<tr>");
								$j=0;
								while($riga=mysql_fetch_row($risultato))
								{
									if($j!=0)// se $j=0 non mando a video nulla, perché il primo record della tabella è creato in automatico dal programma, a prescindere dalla configurazione stabilita dall'utente
									{
										$lunghezzaCampo=strlen($riga[0]);
										echo("<td><b>".substr($riga[0],1,$lunghezzaCampo)."</b></td>");
									}
									$j++;
								}
							echo("</tr>");
							$query="select * 
									from ".$tabella1." 
									where concat(".$ConcatCampiUguali.") not in( 
										select concat(".$ConcatCampiUguali.") 
										from ".$tabella2.")";
							$risultato=mysql_query($query)
								or die("Impossibile estrarre i record che sono presenti nella tabella <i>".$tabella1."</i> e non nella tabella <i>".$tabella2."</i>: ".mysql_error());
							$numeroColonneTabella=mysql_num_fields($risultato)
								or die("Impossibile estrarre il numero delle colonne della tabella1 (<i>".$tabella1."</i>)");
							while($riga=mysql_fetch_row($risultato))
							{
								echo("<tr>");
								for($i=1;$i<$numeroColonneTabella;$i++)
									echo("<td>".$riga[$i]."</td>");
								echo("</tr>");
							}
						echo("</table>");
					}

				echo("<br>");

				//record presenti nella seconda tabella e non nella prima
				$query="select count(*) 
						from ".$tabella2." 
						where concat(".$ConcatCampiUguali.") not in( 
							select concat(".$ConcatCampiUguali.") 
							from ".$tabella1.")";
				$risultato=mysql_query($query)
					or die("Impossibile contare quanti record sono presenti nella tabella <i>".$tabella2."</i> e non nella tabella <i>".$tabella1."</i>: ".mysql_error());
				while($riga=mysql_fetch_row($risultato))
					$quanti=$riga[0];
				if($quanti==0)
					echo("<font size='5'><b>Tutti i record presenti nella tabella <i>".$tabella2."</i> sono presenti anche nella tabella <i>".$tabella1."</i></b></font><br><br>");
				else
					{
						echo("<font size='5'><b>Record della tabella <i>".$tabella2."</i> che non sono presenti nella tabella <i>".$tabella1."</i></b></font><br><br>");
						echo("<table border=1>");
							$query="describe ".$tabella2;
							$risultato=mysql_query($query)
								or die("Impossibile fare la describe della tabella <i>".$tabella2."</i>: ".mysql_error());
							echo("<tr>");
								$j=0;
								while($riga=mysql_fetch_row($risultato))
								{
									if($j!=0)// se $j=0 non mando a video nulla, perché il primo record della tabella è creato in automatico dal programma, a prescindere dalla configurazione stabilita dall'utente
									{
										$lunghezzaCampo=strlen($riga[0]);
										echo("<td><b>".substr($riga[0],1,$lunghezzaCampo)."</b></td>");
									}
									$j++;
								}
							echo("</tr>");
							$query="select * 
									from ".$tabella2." 
									where concat(".$ConcatCampiUguali.") not in( 
										select concat(".$ConcatCampiUguali.") 
										from ".$tabella1.")";
							$risultato=mysql_query($query)
								or die("Impossibile estrarre i record che sono presenti nella tabella <i>".$tabella2."</i> e non nella tabella <i>".$tabella1."</i>: ".mysql_error());
							$numeroColonneTabella=mysql_num_fields($risultato)
								or die("Impossibile estrarre il numero delle colonne della tabella1 (<i>".$tabella2."</i>)");
							while($riga=mysql_fetch_row($risultato))
							{
								echo("<tr>");
								for($i=1;$i<$numeroColonneTabella;$i++)
									echo("<td>".$riga[$i]."</td>");
								echo("</tr>");
							}
						echo("</table>");
					}
				//fine presentazione differenze per record non presenti in entrambe le tabelle
				
				echo("<br><br>");
				echo("<form name='PerConversioneExcel' action='conversioneExcel.php' method='POST'>");
					echo("<input type='hidden' name='NomiTabelle' value='$nomiTabelle'>");
					echo("<input type='submit' name='go' value='Esporta il risultato in Excel'>");
				echo("</form>");
			}
			else
				echo("<font size=5><b>Tra le tabelle confrontate non ci sono differenze<b></font><br><br>");
			
			echo("<br><a href='index.php'>Torna alla pagina iniziale</a>");
			
			mysql_close($conn);
		?>
	</body>
</html>