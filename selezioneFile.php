<html>
	<head>
		<title>Selezione file</title>
	</head>
	<body>
		<?php
			error_reporting (E_ALL ^ E_NOTICE); // questo comando permette di eliminare dall'output a video le NOTICE indesiderate
			$host_name='localhost';
			$user_name='root';
			$conn=@mysql_connect($host_name,$user_name,'')
				or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
			@mysql_select_db('tabellearbitrarie')
				or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
			
			
			//inizio recupero nomi delle tabelle inserite inizialmente dall'utente
				$query="SHOW TABLE STATUS FROM `tabellearbitrarie`"; // questa query estrae l'elenco delle tabelle con, tra le altre cose, data e ora della loro creazione
				$risultato=mysql_query($query)
					or die("Impossibile estrarre i nomi delle tabelle del database");
				$k=0;
				$CampiPerConfrontoEsiste=0;
				while($riga=mysql_fetch_row($risultato))
				{
					$tabelle[$k]=$riga[11]."_".$riga[0]; // inserisco in un array ogni elemento estratto dalla query precedente
					//echo("Record estratto: ".$tabelle[$k]."<br>");
					if($riga[0]=='campiperconfronto')
						$CampiPerConfrontoEsiste=1;
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
				
				$tabella1=substr($tabelle[0],20);
				$tabella2=substr($tabelle[1],20);
			//fine recupero nomi delle tabelle inserite inizialmente dall'utente
			
			if($CampiPerConfrontoEsiste==0)
			{
				$query="describe ".$tabella1;
				$risultato=mysql_query($query)
					or die("Impossibile fare la describe della tabella 1: ".mysql_error());
				$numCampiTabelle=((mysql_num_rows($risultato))-1); // -1 al fine di non conteggiare il campo "progressivo", che è inserito da programma
				$i=0;
				$quanti=0; // conta quanti campi sono stati scelti dall'utente come campi su cui effettuare il confronto tra le tabelle
				while($riga=mysql_fetch_row($risultato))
				{
					$campo[$i][0]=$riga[0];
					$campo[$i][1]=$_POST["$riga[0]"];
					if($campo[$i][1]=="S")
						$quanti++;
					//echo($campo[$i][0]." - ".$campo[$i][1]."<br>");
					$i++;
				}
				
				/*for($j=0;$j<$k;$j++)
					echo("Primo elemento array: ".$tabelle[$j]."<br>");*/
				
				if($quanti==0)
					echo("<font color='red' size=4><div align='justify'><b>Non sono stati selezionati campi per effettuare il confronto; torna indietro con la freccia del browser per selezionare i campi di tuo interesse</b></div></font>");
				elseif($quanti==$numCampiTabelle)
					echo("<font color='red' size=4><div align='justify'><b>Tutti i campi delle tabelle sono stati selezionati per essere confrontati tra loro, senza lasciare nessun campo come riferimento di uguaglianza sui record di entrambe le tabelle. Tornare indietro con la freccia del browser per modificare la propria scelta</b></div></font>");
				else
				{
					//echo("Numero campi selezionati per il confronto: ".$quanti."<br>");
					$CampiPerConfrontoEsiste=0;
					$CampiUgualiEsiste=0;
					for($j=0;$j<$k;$j++)
					{
						if((substr($tabelle[$j],20))=='campiperconfronto')
							$CampiPerConfrontoEsiste=1;
						if((substr($tabelle[$j],20))=='campiuguali')
							$CampiUgualiEsiste=1;
					}
					
					if($CampiPerConfrontoEsiste==0)
					{
						$query="create table CampiPerConfronto
							(
								NomeCampo varchar(100),
								primary key (NomeCampo)
							)";
						$risultato=mysql_query($query)
							or die("Impossibile creare la tabella <i>CampiPerConfronto</i>: ".mysql_error());
					}
						
					if($CampiUgualiEsiste==0)
					{
						$query="create table CampiUguali
							(
								NomeCampo varchar(100),
								primary key (NomeCampo)
							)";
						$risultato=mysql_query($query)
							or die("Impossibile  creare la tabella <i>CampiUguali</i>: ".mysql_error());
					}
						
					$query="select * from ".$tabella1;
					$risultato=mysql_query($query)
						or die("Impossibile effettuare la select sulla tabella ".$tabella1."<br>");
					$colonne=mysql_num_fields($risultato);
					$j=0;
					for($i=0;$i<$colonne;$i++)
					{
						//echo("campo[$i][1]=".$campo[$i][1]."<br>");
						if($campo[$i][1]=="S")
						{
							$query="insert into CampiPerConfronto values ('".$campo[$i][0]."')";
							//echo("Query: ".$query."<br>");
							$risultato=mysql_query($query)
								or die("Impossibile inserire un valore nella tabella <i>CampiPerConfronto</i>: ".mysql_error());
							$campoSelezionato[$j]=$campo[$i][0]; // variabile che serve per la costruzione della succesiva variabile $elenco
							$j++;
						}
						else
						{
							$query="insert into CampiUguali values ('".$campo[$i][0]."')";
							$risultato=mysql_query($query)
								or die("Impossibile inserire un valore nella tabella <i>CampiUguali</i>: ".mysql_error());
						}
					}
					
					$elenco=""; // variabile che serve per mandare a video l'elenco dei campi su cui verranno cercate eventuali differenze
					for($i=0;$i<$quanti;$i++)
					{
						if($i!=($quanti-1))
						{
							$lunghezzaCampoSelezionato=strlen($campoSelezionato[$i]);
							$campoPerElenco=substr($campoSelezionato[$i],1,$lunghezzaCampoSelezionato);
							$elenco=$elenco.$campoPerElenco.", ";
						}
						else
						{
							$lunghezzaCampoSelezionato=strlen($campoSelezionato[$i]);
							$campoPerElenco=substr($campoSelezionato[$i],1,$lunghezzaCampoSelezionato);
							$elenco=$elenco.$campoPerElenco;
						}
						//echo("Elenco: ".$elenco."<br>");
					}
					if($quanti==1)
						echo("<font size=4><b>Il confronto verr&agrave; effettuato ricercando eventuali differenze sul campo ".$elenco."</b></font>");
					else
						echo("<font size=4><b>Il confronto verr&agrave; effettuato ricercando eventuali differenze sui campi ".$elenco."</b></font>");
					
					echo("<br><br>");
					$nomiTabelle=$tabella1." ".$tabella2;
					echo("<fieldset>");
					echo("<legend>Caricamento dati tabella ".$tabella1."</legend>");
						echo("<form name='upload' method='post' action='UploadTabella1.php' enctype='multipart/form-data'>");
						echo("<input type='hidden' name='NomiTabelle' value='$nomiTabelle'");
						echo("<table border=0>");
							echo("<tr>");
								echo("<td>Carica il file csv con i dati della tabella <b>".strtoupper($tabella1)."</b> (utilizzare il <b>tabulatore</b> come carattere separatore)</td>");
								echo("<td>&nbsp;&nbsp;&nbsp;<input type='file' name='uploadfile'></td>");
							echo("</tr>");
						echo("</table>");
						echo("<br><br>");
						echo("<input type='submit' name='go' value='Continua'>");
						echo("</form>");
					echo("</fieldset>");
				}
			}
			else
			{
				$nomiTabelle=$tabella1." ".$tabella2;
				$elencoCampi=$_POST["elenco"];
				//echo("Elenco campi: ".$elencoCampi."<br>");
				$QuantiSpazi=substr_count($elencoCampi," ");
				if($QuantiSpazi>0)
					echo("<font size=4><b>Il confronto verr&agrave; effettuato ricercando eventuali differenze sui campi ".$elencoCampi."</b></font>");
				else
					echo("<font size=4><b>Il confronto verr&agrave; effettuato ricercando eventuali differenze sul campo ".$elencoCampi."</b></font>");
				echo("<br><br>");
				echo("<fieldset>");
					echo("<legend>Caricamento dati tabella ".$tabella1."</legend>");
						echo("<form name='upload' method='post' action='UploadTabella1.php' enctype='multipart/form-data'>");
						echo("<input type='hidden' name='NomiTabelle' value='$nomiTabelle'");
						echo("<table border=0>");
							echo("<tr>");
								echo("<td>Carica il file csv con i dati della tabella <b>".strtoupper($tabella1)."</b> (utilizzare il <b>tabulatore</b> come carattere separatore)</td>");
								echo("<td>&nbsp;&nbsp;&nbsp;<input type='file' name='uploadfile'></td>");
							echo("</tr>");
						echo("</table>");
						echo("<br><br>");
						echo("<input type='submit' name='go' value='Continua'>");
						echo("</form>");
					echo("</fieldset>");
			}
			
			mysql_close($conn);
		?>
		<br>
		<a href="index.php">Torna alla pagina iniziale</a>
	</body>
</html>