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
	
	
	//recupero il nome delle due tabelle che devo popolare
	$nomiTabelle=$_POST["NomiTabelle"];
	//echo("Nomi Tabelle: ".$nomiTabelle."<br>");
	$tabelle=explode(' ',$nomiTabelle);
	$tabella1=$tabelle[0];
	$tabella2=$tabelle[1];
	//echo("Tabella 1: ".$tabella1."<br>");
	//echo("Tabella 2: ".$tabella2."<br>");
	
	$query="describe ".$tabella1;
	$risultato=mysql_query($query)
		or die("Impossibile effettuare la describe della tabella ".$tabella1.": ".mysql_error());
	$NumeroCampiTabella1=mysql_num_rows($risultato);
	$CSVFile="C:/Program Files (x86)/EasyPHP 2.0b1/www/ConfrontoTabelleArbitrarie/".$_FILES['uploadfile']['name'];
	$inserimento="LOAD DATA LOCAL INFILE '" .$CSVFile. "' INTO TABLE ".$tabella1." FIELDS TERMINATED BY '	' (";
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
	
	
	//effettuo il popolamento della tabella 1
	$query="select * from ".$tabella1;
	$risultato=mysql_query($query)
		or die("Impossibile estrarre i dati dalla tabella ".$tabella1." per la preventiva verifica del suo popolamento: ".mysql_error());
	$righe=mysql_num_rows($risultato);
	if($righe==0)
	{
		$importazione=mysql_query($inserimento)
		or die ("Impossibile caricare i dati nella tabella <i>".$tabella1."</i>: ".mysql_error());
	
		$query="select * from ".$tabella1;
		$risultato=mysql_query($query)
			or die("Impossibile estrarre i dati della tabella ".$tabella1.": ".mysql_error());
		$righe=mysql_num_rows($risultato);
		if($righe==0)
			echo("<font color='red' size=4><div align='justify'><b>La tabella ".$tabella1." &egrave; vuota; torna indietro con la freccia del browser e procedi nuovamente con l'upload del file per il caricamento dei dati</b></div></font>");
		else
		{
			echo("<fieldset>");
			echo("<legend>Caricamento dati tabella ".$tabella2."</legend>");
			echo("<font size=4><b>La tabella <i>".$tabella1."</i> &egrave; stata correttamente popolata</b></font><br><br>");
			echo("<form name='upload' method='post' action='UploadTabella2.php' enctype='multipart/form-data'>");
			echo("<input type='hidden' name='NomiTabelle' value='$nomiTabelle'");
			echo("<table border=0>");
				echo("<tr>");
					echo("<td>Carica il file csv con i dati della tabella <b>".strtoupper($tabella2)."</b> (utilizzare il <b>tabulatore</b> come carattere separatore)</td>");
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
		echo("<font color='red' size=4><div align='justify'><b>La tabella ".$tabella1." non &egrave; vuota, pertanto non &egrave; possibile procedere con il suo popolamento. <a href='index.php'>Accedere alla pagina iniziale</a> e cancellare il contenuto delle tabelle prima di continuare.</b></div></font>");
	}
		
	mysql_close($conn);
?>