<?php
	$host_name='localhost';
	$user_name='root';
	$conn=@mysql_connect($host_name,$user_name,'')
		or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
	@mysql_select_db('tabellearbitrarie')
		or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
	
	// controllo che non ci siano stati errori nell'upload (codice = 0) 
	if ($_FILES['uploadfile']['error'] == 0)
	{
		// upload ok
		// copio il file dalla cartella temporanea a quella di destinazione mantenendo il nome originale 
		copy($_FILES['uploadfile']['tmp_name'], "C:/Program Files (x86)/EasyPHP 2.0b1/www/ConfrontoTabelleArbitrarie/".$_FILES['uploadfile']['name']) or die("Impossibile caricare il file");
	    echo "Il file &egrave; stato correttamente importato sul server<br><br>";
	    // upload terminato, stampo alcune info sul file
		//echo "Nome file: ".$_FILES['uploadfile']['name']."<br>";
		//echo "Dimensione file: ".$_FILES['uploadfile']['size']."<br>";
		//echo "Tipo MIME file: ".$_FILES['uploadfile']['type'];
    }
    else
    {
	   // controllo il tipo di errore
	   if ($_FILES['uploadfile']['error'] == 2)
	   {
		// errore, file troppo grande (> 1MB)
		die("Errore, file troppo grande: il massimo consentito &egrave; 1MB");
       }
	   else
	   {
		// errore generico
		die("Errore, impossibile caricare il file");
	   }
    }
	
	//recupero il nome della tabella da popolare
	$tabellaVuota=$_POST["TabellaVuota"];
	//echo("Tabella da popolare: ".$tabellaVuota."<br>");
	
	$query="select * from ".$tabellaVuota;
	$risultato=mysql_query($query)
		or die("Impossibile estrarre i dati dalla tabella <i>".$tabellaVuota."</i>: ".mysql_error());
	$NumRighe=mysql_num_rows($risultato);
	if($NumRighe==0)
	{
		$query="describe ".$tabellaVuota;
		$risultato=mysql_query($query)
			or die("Impossibile eseguire la describe della tabella <i>".$tabellaVuota."</i>: ".mysql_error());
		$NumeroCampiTabellaVuota=mysql_num_rows($risultato);
		$CSVFile="C:/Program Files (x86)/EasyPHP 2.0b1/www/ConfrontoTabelleArbitrarie/".$_FILES['uploadfile']['name'];
		$inserimento="LOAD DATA LOCAL INFILE '" .$CSVFile. "' INTO TABLE ".$tabellaVuota." FIELDS TERMINATED BY '	' (";
		$i=0;
		while($riga=mysql_fetch_row($risultato))
		{
			if($i>0)
				$inserimento=$inserimento.$riga[0].",";
			$i++;
		}
		
		//echo("Testo inserimento: ".$inserimento."<br>");
		$lunghezzaStringaInserimento=strlen($inserimento);
		$inserimento=substr($inserimento,0,$lunghezzaStringaInserimento-1);
		//echo("Testo inserimento: ".$inserimento."<br>");
		$inserimento=$inserimento.") ";
		//echo("Testo inserimento: ".$inserimento."<br>");
		
		//procedo con il popolamento della tabella vuota
		$importazione=mysql_query($inserimento)
		or die ("Impossibile caricare i dati nella tabella <i>".$tabellaVuota."</i>: ".mysql_error());
	
		$query="select * from ".$tabellaVuota;
		$risultato=mysql_query($query)
			or die("Impossibile estrarre i dati della tabella ".$tabellaVuota.": ".mysql_error());
		$righe=mysql_num_rows($risultato);
		if($righe==0)
			echo("<font color='red' size=4><div align='justify'><b>La tabella ".$tabellaVuota." &egrave; vuota; torna indietro con la freccia del browser e procedi nuovamente con l'upload del file per il caricamento dei dati</b></div></font>");
		else
		{
			echo("Tabella ".$tabellaVuota." popolata correttamente.");
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
			
			$quanti=count($tabelle);
			for($i=0;$i<$quanti;$i++)
			{
				echo("<br>Tabelle[".$i."]: ".$tabelle[$i]."<br>");
			}
			
			
			/*a questo punto devo stabilire se anche l'altra tabella su cui effettuare il confronto è popolata: 
			 - se lo è, devo stabilire quale tra $tabelle[0] e $tabelle[1], non è uguale a $tabellaVuota. Questa tabella e $tabellaVuota devo passarle alla pagina di esecuzione del confronto 
			 - se non lo è, torno a popolamentoTabellaMancante.php passandogli i parametri necessari */
			
			$AltraTabellaDaPopolare='';
			
			$Tabelle0=substr($tabelle[0],20); // questo comando restituisce una sottostringa di una stringa tipo questa "2020-03-09 21:26:40_tecnico", prendendo tutto ciò che c'è dopo l'underscore
			echo("<br>Tabelle0: ".$Tabelle0."<br>");
			$query="select * from ".$Tabelle0;
			$risultatoTabelle0=mysql_query($query)
				or die("Impossibile estrarre i dati dalla tabelle[0] <i>(".$Tabelle0.")</i>: ".mysql_error());
			$NumRigheRisultatoTabelle0=mysql_num_rows($risultatoTabelle0)
				or die("Impossibile stabilire quante righe contiene la tabelle[0] <i>(".$Tabelle0.")</i>: ".mysql_error());
			
			$Tabelle1=substr($tabelle[1],20); // questo comando restituisce una sottostringa di una stringa tipo questa "2020-03-09 21:26:40_tecnico", prendendo tutto ciò che c'è dopo l'underscore
			echo("Tabelle1: ".$Tabelle1."<br>");
			$query="select * from ".$Tabelle1;
			$risultatoTabelle1=mysql_query($query)
				or die("Impossibile estrarre i dati dalla tabelle[1] <i>(".$Tabelle1.")</i>: ".mysql_error());
			$NumRigheRisultatoTabelle1=mysql_num_rows($risultatoTabelle1)
				or die("Impossibile stabilire quante righe contiene la tabelle[1] <i>(".$Tabelle1.")</i>: ".mysql_error());
			
			// se entrambe le tabelle su cui effettuare il confronto sono popolate
			if($NumRigheRisultatoTabelle0>0 and $NumRigheRisultatoTabelle1>0)
			{
				if($Tabelle0==$tabellaVuota)
					$AltraTabellaDaPassare=$Tabelle1;
				else
					$AltraTabellaDaPassare=$Tabelle0;
				//echo("<br>tabellaVuota: ".$tabellaVuota."<br>");
				//echo("<br>AltraTabellaDaPassare: ".$AltraTabellaDaPassare."<br>");
				$nomiTabelle=$tabellaVuota.' '.$AltraTabellaDaPassare;
				echo("<br>NomiTabelle: ".$nomiTabelle."<br>");
				echo("<form name='struttura' action='esecuzioneConfronto.php' method='POST'>");
					echo("<input type='hidden' name='NomiTabelle' value='$nomiTabelle'>");
					echo("<input type='submit' name='invio' value='Componi confronto tra le tabelle caricate'>");
				echo("</form>");
			}
			else
			{
				echo("Ciao");
			}
		}
	}
	else
		echo("La tabella ".$tabellaVuota." non &egrave; vuota; passare direttamente alla pagina di confronto tra il contenuto delle tabelle.<br>");
	
	mysql_close($conn);
?>