<html>
	<head>
		<title>Aggiornamento campi</title>
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
			
			$tabella=$_POST["tabella"];
			//echo("Tabella: ".$tabella."<br>");
			$query="describe ".$tabella;
			$risultato=mysql_query($query)
				or die("Impossibile fare la describe della tabella <i>".$tabella."</i>: ".mysql_error());
			$numCampiTabelle=((mysql_num_rows($risultato))-1); // -1 al fine di non conteggiare il campo "progressivo", che è inserito da programma
			$i=0;
			while($riga=mysql_fetch_row($risultato))
			{
				if($riga[0]!='Progressivo')
				{
					$campo[$i][0]=$riga[0];
					$campo[$i][1]=$_POST["$riga[0]"];
					//echo($campo[$i][0]." - ".$campo[$i][1]."<br>");
					$i++;
				}
			}
			
			
			
			$query="delete from campiperconfronto";
			$risultato=mysql_query($query)
				or die("Impossibile cancellare i dati dalla tabella <i>campiperconfronto</i>: ".mysql_error());
				
			$query="delete from campiuguali where nomeCampo!='Progressivo'";
			$risultato=mysql_query($query)
				or die("Impossibile cancellare i dati dalla tabella <i>campiuguali</i>: ".mysql_error());
			
			$quanti=0;
			for($j=0;$j<$i;$j++)
			{
				if($campo[$j][1]=="S")
				{
					$query="insert into CampiPerConfronto values ('".$campo[$j][0]."')";
					$risultato=mysql_query($query)
						or die("Impossibile inserire un valore nella tabella <i>CampiPerConfronto</i>: ".mysql_error());
					$campoSelezionato[$quanti]=$campo[$j][0]; // variabile che serve per la costruzione della succesiva variabile $elenco	
					$quanti++;
				}
				else
				{
					$query="insert into CampiUguali values ('".$campo[$j][0]."')";
					$risultato=mysql_query($query)
						or die("Impossibile inserire un valore nella tabella <i>CampiUguali</i>: ".mysql_error());
				}
			}
			
			$elenco="";
			for($i=0;$i<$quanti;$i++)
			{
				if($i<$quanti-1)
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
			}
			//echo("Elenco: ".$elenco."<br>");
			if($quanti==1)
				echo("<font size=4><b>Il confronto verr&agrave; effettuato ricercando eventuali differenze sul campo ".$elenco."</b></font>");
			else
				echo("<font size=4><b>Il confronto verr&agrave; effettuato ricercando eventuali differenze sui campi ".$elenco."</b></font>");
			
			
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
			
			
			//qui stabilisco se le tabelle da popolare sono già state popolate o meno
			$nomiTabelle=substr($tabelle[0],20)." ".substr($tabelle[1],20);
			//echo("<br>Tabelle: ".$nomiTabelle."<br>");
			$NumElementiTabelle=0;
			$query="select count(*) from ".substr($tabelle[0],20);
			$risultato=mysql_query($query)
				or die("Impossibile contare i valori presenti nella tabella <i>".substr($tabelle[0],20)."</i>: ".mysql_error());
			while($riga=mysql_fetch_row($risultato))
			{
				$NumElementiTabelle=$NumElementiTabelle+$riga[0];
				$NumElementiTabella1=$riga[0];
			}
			$query="select count(*) from ".substr($tabelle[1],20);
			$risultato=mysql_query($query)
				or die("Impossibile contare i valori presenti nella tabella <i>".substr($tabelle[0],20)."</i>: ".mysql_error());
			while($riga=mysql_fetch_row($risultato))
			{
				$NumElementiTabelle=$NumElementiTabelle+$riga[0];
				$NumElementiTabella2=$riga[0];
			}
			//echo("<br><br>Numero elementi tabelle: ".$NumElementiTabelle."<br>");
			//echo("<br><br>Numero elementi tabella ".substr($tabelle[0],20).": ".$NumElementiTabella1."<br>");
			//echo("<br><br>Numero elementi tabella ".substr($tabelle[1],20).": ".$NumElementiTabella2."<br>");
			if($NumElementiTabelle==0)
			{
				echo("<br><br><form name='ComponiConfronto' method='POST' action='selezioneFile.php'>
								<input type='hidden' name='elenco' value='$elenco'>
								<input type='submit' name='go' value='Accedi alla pagina di caricamento dei dati da confrontare'>
							  </form>");
			}
			elseif($NumElementiTabella1==0)
			{
				echo("<br><br><font color='red' size=4><b>La tabella 1 <i>".substr($tabelle[0],20)."</i> &egrave; vuota: occorre popolarla prima di continuare</b></font>");
				$tabellaVuota=substr($tabelle[0],20);
				echo("<br><br><form name='PopolamentoTabellaMancante1' method='POST' action='popolamentoTabellaMancante.php'>
								<input type='hidden' name='tabellaVuota' value='$tabellaVuota'>
								<input type='submit' name='go' value='Accedi alla pagina di caricamento della tabella ".$tabellaVuota."'>
							  </form>");
			}
			elseif($NumElementiTabella2==0)
			{
				echo("<br><br><font color='red' size=4><b>La tabella 2 <i>".substr($tabelle[1],20)."</i> &egrave; vuota: occorre popolarla prima di continuare</b></font>");
				$tabellaVuota=substr($tabelle[1],20);
				echo("<br><br><form name='PopolamentoTabellaMancante2' method='POST' action='popolamentoTabellaMancante.php'>
								<input type='hidden' name='tabellaVuota' value='$tabellaVuota'>
								<input type='submit' name='go' value='Accedi alla pagina di caricamento della tabella ".$tabellaVuota."'>
							  </form>");
			}
			else
			{
				echo("<br><br>");
				echo("<form name='ComponiConfronto2' method='POST' action='esecuzioneConfronto.php'>
						<input type='hidden' name='NomiTabelle' value='$nomiTabelle'>
						<input type='submit' name='go' value='Esegui il confronto sui dati importati'>
					  </form>");
			}
			
			mysql_close($conn);
		?>
	</body>
</html>