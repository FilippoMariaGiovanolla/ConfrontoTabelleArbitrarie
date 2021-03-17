<html>
	<head>
		<title>Selezione campi</title>
	</head>
	<body>
		<?php
			$host_name='localhost';
			$user_name='root';
			$conn=@mysql_connect($host_name,$user_name,'')
				or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
			@mysql_select_db('tabellearbitrarie')
				or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
			
			//inizio recupero nomi delle tabelle inserite inizialmente dall'utente
				$CampiPerConfrontoEsiste=0;
				$CampiUgualiEsiste=0;
				$query="SHOW TABLE STATUS FROM `tabellearbitrarie`"; // questa query estrae l'elenco delle tabelle con, tra le altre cose, data e ora della loro creazione
				$risultato=mysql_query($query)
					or die("Impossibile estrarre i nomi delle tabelle del database");
				$k=0;
				while($riga=mysql_fetch_row($risultato))
				{
					if($riga[0]=='campiperconfronto')
						$CampiPerConfrontoEsiste=1;
					if($riga[0]=='campiuguali')
						$CampiUgualiEsiste=1;
					$tabelle[$k]=$riga[11]."_".$riga[0]; // inserisco in un array ogni elemento estratto dalla query precedente
					//echo("Record estratto: ".$tabelle[$k]."<br>");
					$k++;
				}
				
				
				do // ordino dalla più vecchia alla più recente le tabelle presenti nell'array, così da avere come primo e secondo elemento le tabelle inizialmente create dall'utente
				{
					$effettuatiScambi=0;
					for($i=0;$i<($k-1);$i++)
					{
						if((substr($tabelle[$i],0,19))>(substr($tabelle[$i+1],0,19))) //2019-08-26 17:41:36
						{
							$ausiliaria=$tabelle[$i];
							$tabelle[$i]=$tabelle[$i+1];
							$tabelle[$i+1]=$ausiliaria;
							$effettuatiScambi=1;
						}
					}
				}
				while($effettuatiScambi==1);
			//fine recupero nomi delle tabelle inserite inizialmente dall'utente
			
			if(($CampiPerConfrontoEsiste==1) and ($CampiUgualiEsiste==1))
			{
				$queryCampiConfronto="select * from campiperconfronto";
				$risultatoCampiConfronto=mysql_query($queryCampiConfronto)
					or die("Impossibile estrarre i dati dalla tabella <i>CampiPerConfronto</i>");
				$quantiCampiPerConfronto=mysql_num_rows($risultatoCampiConfronto);
				$queryCampiUguali="select * from campiuguali";
				$risultatoCampiUguali=mysql_query($queryCampiUguali)
					or die("Impossibile estrarre i dati dalla tabella <i>CampiUguali</i>");
				$quantiCampiUguali=mysql_num_rows($risultatoCampiUguali);
				if(($quantiCampiPerConfronto>0) and ($quantiCampiUguali>0))
				{
					echo("<font size=5><b>&Egrave; gi&agrave; stata fatta la scelta dei campi su cui cercare differenze tra le due tabelle; qui sotto viene riportata la selezione precedentemente impostata; &egrave; possibile mantenere questa selezione oppure modificarla e continuare secondo le nuove scelte:<br><br></b></font>");
					$i=0;
					while($riga=mysql_fetch_row($risultatoCampiConfronto))
					{
						$campi[$i]=$riga[0]." S";
						$i++;
					}
					
					while($riga=mysql_fetch_row($risultatoCampiUguali))
					{
						$campi[$i]=$riga[0];
						$i++;
					}
					
					$quantiCampi=$quantiCampiPerConfronto+$quantiCampiUguali;
					//echo("Numero campi totali: ".$quantiCampi."<br>");
					
					do // ordino i campi come realmente presenti in tabella
					{
						$effettuatiScambi=0;
						for($j=0;$j<($quantiCampi-1);$j++)
						{
							if(($campi[$j])>($campi[$j+1]))
							{
								$ausiliaria=$campi[$j];
								$campi[$j]=$campi[$j+1];
								$campi[$j+1]=$ausiliaria;
								$effettuatiScambi=1;
							}
						}
					}
					while($effettuatiScambi==1);
					
					/*for($j=0;$j<$quantiCampi-1;$j++)
					{
						echo("Elemento: ".$campi[$j]."<br>");
					}*/
					
					echo("<form name='GoToAggiornamentoCampi' action='aggiornamentoCampi.php' method='POST'>");
					echo("<table border=1>");						
						for($j=0;$j<$quantiCampi-1;$j++)
						{
							echo("<tr>");
							$lunghezzaCampo=strlen($campi[$j]);
							if(substr($campi[$j],$lunghezzaCampo-2,1)==' ')
							{
								$valoreDaPassare=substr($campi[$j],0,$lunghezzaCampo-2);
								echo("<td><b>".substr($campi[$j],1,$lunghezzaCampo-2)."</b></td><td><input checked type='checkbox' name='".$valoreDaPassare."' value='S'></td>");
							}
							else
							{
								$valoreDaPassare=substr($campi[$j],0,$lunghezzaCampo);
								echo("<td><b>".substr($campi[$j],1,$lunghezzaCampo)."</b></td><td><input type='checkbox' name='".$valoreDaPassare."' value='S'></td>");
							}
							echo("</tr>");
						}						
					echo("</table>");
					echo("<br>");
					$tabellaDaPassare=substr($tabelle[0],20);
					echo("<input type='hidden' name='tabella' value='$tabellaDaPassare'>");
					echo("<input type='submit' name='invio' value='Avanti'>");
					echo("</form>");
				}
			}
			else
			{
				$query="describe ".substr($tabelle[0],20);
				//echo("Query: ".$query."<br>");
				$risultato=mysql_query($query)
					or die("Impossibile accedere ai campi della tabella <i>".substr($tabelle[0],20)."</i>: ".mysql_error());
				$j=0;
				echo("<font size=5><b>Determina su quali campi ricercare eventuali differenze, spuntando il relativo checkbox</b></font><br>
					  <br>
					  <form name='GoToPopolamento' action='selezioneFile.php' method='POST'>");
					echo("<table border=1>");
						while($riga=mysql_fetch_row($risultato))
						{
							if($j!=0) // se $j=0 non mando a video nulla, perché il primo record della tabella è creato in automatico dal programma, a prescindere dalla configurazione stabilita dall'utente
							{
								echo("<tr>");
									$lunghezzaCampo=strlen($riga[0]);
									echo("<td><b>".substr($riga[0],1,$lunghezzaCampo)."</b></td><td><input type='checkbox' name='".$riga[0]."' value='S'></td>");
								echo("</tr>");
							}
							$j++;
						}
					echo("</table>");
					echo("<br><br>");
					echo("<input type='submit' name='invio' value='Accedi alla pagina di popolamento delle tabelle con i dati da confrontare'>
						</form>
						<br><br>
					");
			}
			
			mysql_close($conn);
		?>
		<br>
		<a href="index.php">Torna alla pagina iniziale</a>
	</body>
</html>