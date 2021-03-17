<html>
	<head>
		<title>Creazione tabelle</title>
	</head>
	<body>
	<?php
		$host_name='localhost';
		$user_name='root';
		$conn=@mysql_connect($host_name,$user_name,'')
			or die ("<BR>Impossibile stabilire una connessione con il server: ".mysql_error());
		@mysql_select_db('tabellearbitrarie')
			or die ("Impossibile selezionare il database <i>TabelleArbitrarie</i>, chiudere il programma e riprovare: ".mysql_error());
			
		//recupero i dati provenienti dalla form, andando a rendere le stringhe maiuscole, eventualmente a sostituire gli spazi con degli underscore ed andando ad applicare la funzione addslashes() per la gestione delle stringhe con apostrofi
		$NumCampi=$_POST["NumCampi"];
		$NomeTabella1=addslashes(strtoupper(str_replace(" ","_",$_POST["NomeTabella1"])));
		$NomeTabella2=addslashes(strtoupper(str_replace(" ","_",$_POST["NomeTabella2"])));
		
		
		$lunghezzaNomeTabella1=strlen($NomeTabella1);
		if($lunghezzaNomeTabella1==0)
			echo("<font color='red' size=4><div align='justify'><b>Non &egrave; stato inserito il nome della tabella 1; torna indietro con la freccia del browser e completa la form prima di continuare</b></div></font>");
		elseif( ($lunghezzaNomeTabella1==1) and ($NomeTabella1!='A') and ($NomeTabella1!='B') and ($NomeTabella1!='C') and ($NomeTabella1!='D') and ($NomeTabella1!='E') and ($NomeTabella1!='F') and ($NomeTabella1!='G') and ($NomeTabella1!='H') and ($NomeTabella1!='I') and ($NomeTabella1!='J') and ($NomeTabella1!='K') and ($NomeTabella1!='L') and ($NomeTabella1!='M') and ($NomeTabella1!='N') and ($NomeTabella1!='O') and ($NomeTabella1!='P') and ($NomeTabella1!='Q') and ($NomeTabella1!='R') and ($NomeTabella1!='S') and ($NomeTabella1!='T') and ($NomeTabella1!='U') and ($NomeTabella1!='V') and ($NomeTabella1!='W') and ($NomeTabella1!='X') and ($NomeTabella1!='Y') and ($NomeTabella1!='Z') )
			echo("<font color='red' size=4><div align='justify'><b>Il nome della tabella 1 contiene un carattere non valido; torna indietro con la freccia del browser e correggi il dato prima di continuare. Se si attribuisce un nome di un solo carattere, questo carattere deve essere una lettera.</b></div></font>");
		else
		{
			$caratteriNonConsentiti=0;
			//echo("Nome tabella 1: ".$NomeTabella1."<br>");
			for($i=0;$i<$lunghezzaNomeTabella1;$i++)
			{
				$carattereDaControllare=substr($NomeTabella1,$i,1);
				//echo("Carattere da controllare: ".$carattereDaControllare."<br>");
				if(($carattereDaControllare!='A') and ($carattereDaControllare!='B') and ($carattereDaControllare!='C') and ($carattereDaControllare!='D') and ($carattereDaControllare!='E') and ($carattereDaControllare!='F') and ($carattereDaControllare!='G') and ($carattereDaControllare!='H') and ($carattereDaControllare!='I') and ($carattereDaControllare!='J') and ($carattereDaControllare!='K') and ($carattereDaControllare!='L') and ($carattereDaControllare!='M') and ($carattereDaControllare!='N') and ($carattereDaControllare!='O') and ($carattereDaControllare!='P') and ($carattereDaControllare!='Q') and ($carattereDaControllare!='R') and ($carattereDaControllare!='S') and ($carattereDaControllare!='T') and ($carattereDaControllare!='U') and ($carattereDaControllare!='V') and ($carattereDaControllare!='W') and ($carattereDaControllare!='X') and ($carattereDaControllare!='Y') and ($carattereDaControllare!='Z') and ($carattereDaControllare!='1') and ($carattereDaControllare!='2') and ($carattereDaControllare!='3') and ($carattereDaControllare!='4') and ($carattereDaControllare!='5') and ($carattereDaControllare!='6') and ($carattereDaControllare!='7') and ($carattereDaControllare!='8') and ($carattereDaControllare!='9') and ($carattereDaControllare!='0'))
					$caratteriNonConsentiti++;
			}
			if($caratteriNonConsentiti>0)
				echo("<font color='red' size=4><div align='justify'><b>Nel nome della tabella 1 sono stati inseriti caratteri non consentiti; torna indietro con la freccia del browser e correggi la form (sono ammessi solo lettere e numeri) Si precisa inoltre che non sono ammessi spazi.</b></div></font>");
			
			// fine controlli sul nome della tabella 1
			else
			{
				$lunghezzaNomeTabella2=strlen($NomeTabella2);
				if($lunghezzaNomeTabella2==0)
					echo("<font color='red' size=4><div align='justify'><b>Non &egrave; stato inserito il nome della tabella 2; torna indietro con la freccia del browser e completa la form prima di continuare</b></div></font>");
				elseif( ($lunghezzaNomeTabella2==1) and ($NomeTabella2!='A') and ($NomeTabella2!='B') and ($NomeTabella2!='C') and ($NomeTabella2!='D') and ($NomeTabella2!='E') and ($NomeTabella2!='F') and ($NomeTabella2!='G') and ($NomeTabella2!='H') and ($NomeTabella2!='I') and ($NomeTabella2='J') and ($NomeTabella2!='K') and ($NomeTabella2!='L') and ($NomeTabella2!='M') and ($NomeTabella2!='N') and ($NomeTabella2!='O') and ($NomeTabella2!='P') and ($NomeTabella2!='Q') and ($NomeTabella2!='R') and ($NomeTabella2!='S') and ($NomeTabella2!='T') and ($NomeTabella2!='U') and ($NomeTabella2!='V') and ($NomeTabella2!='W') and ($NomeTabella2!='X') and ($NomeTabella2!='Y') and ($NomeTabella2!='Z') )
					echo("<font color='red' size=4><div align='justify'><b>Il nome della tabella 2 contiene un carattere non valido; torna indietro con la freccia del browser e correggi il dato prima di continuare. Se si attribuisce un nome di un solo carattere, questo carattere deve essere una lettera.</b></div></font>");
				elseif($NomeTabella1==$NomeTabella2)
				{
					echo("<font color='red' size=4><div align='justify'><b>Alle due tabelle &egrave; stato assegnato lo stesso nome e questo non &egrave; consentito; torna indietro con la freccia del browser per modificare uno dei due nomi</b></div></font>");
				}
				else					
				{
					$caratteriNonConsentiti=0;
					//echo("Nome tabella 2: ".$NomeTabella2."<br>");
					for($i=0;$i<$lunghezzaNomeTabella2;$i++)
					{
						$carattereDaControllare=substr($NomeTabella2,$i,1);
						//echo("Carattere da controllare: ".$carattereDaControllare."<br>");
						if(($carattereDaControllare!='A') and ($carattereDaControllare!='B') and ($carattereDaControllare!='C') and ($carattereDaControllare!='D') and ($carattereDaControllare!='E') and ($carattereDaControllare!='F') and ($carattereDaControllare!='G') and ($carattereDaControllare!='H') and ($carattereDaControllare!='I') and ($carattereDaControllare!='J') and ($carattereDaControllare!='K') and ($carattereDaControllare!='L') and ($carattereDaControllare!='M') and ($carattereDaControllare!='N') and ($carattereDaControllare!='O') and ($carattereDaControllare!='P') and ($carattereDaControllare!='Q') and ($carattereDaControllare!='R') and ($carattereDaControllare!='S') and ($carattereDaControllare!='T') and ($carattereDaControllare!='U') and ($carattereDaControllare!='V') and ($carattereDaControllare!='W') and ($carattereDaControllare!='X') and ($carattereDaControllare!='Y') and ($carattereDaControllare!='Z') and ($carattereDaControllare!='1') and ($carattereDaControllare!='2') and ($carattereDaControllare!='3') and ($carattereDaControllare!='4') and ($carattereDaControllare!='5') and ($carattereDaControllare!='6') and ($carattereDaControllare!='7') and ($carattereDaControllare!='8') and ($carattereDaControllare!='9') and ($carattereDaControllare!='0'))
							$caratteriNonConsentiti++;
					}
					if($caratteriNonConsentiti>0)
						echo("<font color='red' size=4><div align='justify'><b>Nel nome della tabella 2 sono stati inseriti caratteri non consentiti; torna indietro con la freccia del browser e correggi la form (sono ammessi solo lettere e numeri)</b></div></font>");
					// fine controlli sul nome della tabella 2
					
					else
					{
						$numeroCampiConDescrizionePopolata=0;
						$numeroCampiConDescrizioneMonocaratterialeErrata=0;
						$numeroCampiConDescrizionePluricaratterialeErrata=0;
						$numeroCampiConTipologiaPopolata=0;
						$numeroCampiConNomeUguale=0;
						for($i=0;$i<$NumCampi;$i++)
						{
							$NumCampo=$i+1;
							
							//inizio controlli sul nome di ogni campo
							$nomeCampo="NomeCampo".$NumCampo;
							$campo[$i]=strtoupper(str_replace(" ","_",$_POST[$nomeCampo]));
							if($i>0)
							{
								for($j=0;$j<$i;$j++)
								{
									if($campo[$j]==$campo[$i])
										$numeroCampiConNomeUguale++;
								}
							}
							//$campo[$i]=str_replace("@","_",$campo[$i]);
							//echo("Strlen($campo): ".strlen($campo[$i])."<br>");
							if(strlen($campo[$i])>0)
							{
								$numeroCampiConDescrizionePopolata++; // campo che serve per il successivo controllo sul passaggio di tutti i dati necessari alla creazione delle tabelle
								if(strlen($campo[$i])>1)
								{
									for($j=0;$j<strlen($campo[$i]);$j++)
									{
										$carattereDaControllare=substr($campo[$i],$j,1);
										//echo("Nome campo da controllare: ".$campo[$i]."<br>");
										//echo("Carattere da controllare: ".$carattereDaControllare."<br>");
										if(($carattereDaControllare!='A') and ($carattereDaControllare!='B') and ($carattereDaControllare!='C') and ($carattereDaControllare!='D') and ($carattereDaControllare!='E') and ($carattereDaControllare!='F') and ($carattereDaControllare!='G') and ($carattereDaControllare!='H') and ($carattereDaControllare!='I') and ($carattereDaControllare!='J') and ($carattereDaControllare!='K') and ($carattereDaControllare!='L') and ($carattereDaControllare!='M') and ($carattereDaControllare!='N') and ($carattereDaControllare!='O') and ($carattereDaControllare!='P') and ($carattereDaControllare!='Q') and ($carattereDaControllare!='R') and ($carattereDaControllare!='S') and ($carattereDaControllare!='T') and ($carattereDaControllare!='U') and ($carattereDaControllare!='V') and ($carattereDaControllare!='W') and ($carattereDaControllare!='X') and ($carattereDaControllare!='Y') and ($carattereDaControllare!='Z') and ($carattereDaControllare!='1') and ($carattereDaControllare!='2') and ($carattereDaControllare!='3') and ($carattereDaControllare!='4') and ($carattereDaControllare!='5') and ($carattereDaControllare!='6') and ($carattereDaControllare!='7') and ($carattereDaControllare!='8') and ($carattereDaControllare!='9') and ($carattereDaControllare!='0'))
											$numeroCampiConDescrizionePluricaratterialeErrata++;
									}
								}
							}
							if((strlen($campo[$i])==1)and(($campo[$i]!='A') and ($campo[$i]!='B') and ($campo[$i]!='C') and ($campo[$i]!='D') and ($campo[$i]!='E') and ($campo[$i]!='F') and ($campo[$i]!='G') and ($campo[$i]!='H') and ($campo[$i]!='I') and ($campo[$i]!='J') and ($campo[$i]!='K') and ($campo[$i]!='L') and ($campo[$i]!='M') and ($campo[$i]!='N') and ($campo[$i]!='O') and ($campo[$i]!='P') and ($campo[$i]!='Q') and ($campo[$i]!='R') and ($campo[$i]!='S') and ($campo[$i]!='T') and ($campo[$i]!='U') and ($campo[$i]!='V') and ($campo[$i]!='W') and ($campo[$i]!='X') and ($campo[$i]!='Y') and ($campo[$i]!='Z')))
								$numeroCampiConDescrizioneMonocaratterialeErrata++; //conto i campi di un solo carattere dove il nome del campo è un carattere speciale o un numero
							
							//inizio controlli sulla tipologia di ogni campo
							$tipologiaCampo="Tipologia".$NumCampo;
							$tipologia[$i]=strtoupper(str_replace(" ","_",$_POST[$tipologiaCampo]));
						}
						
						
						//mando a video i campi ottenuti dalla form
						/*echo("
							Nome tabella 1: ".$NomeTabella1."<br>
							Nome tabella 2: ".$NomeTabella2."<br>
							<br>
							<table border=1>
								<tr>
									<td>Nome campo</td>
									<td>Tipologia campo</td>
								</tr>");
									for($i=0;$i<$NumCampi;$i++)
									{
										echo("<tr>");
											echo("<td>".$campo[$i]."</td>");
											echo("<td>".$tipologia[$i]."</td>");
										echo("</tr>");
									}
						echo("</table>"); */
						//echo("Strlen(NomeTabella1)=".strlen($NomeTabella1)."<br>");
						//echo("Strlen(NomeTabella2)=".strlen($NomeTabella2)."<br>");
						//echo("NumeroCampiConDescrizionePopolata: ".$numeroCampiConDescrizionePopolata."<br>");
						//controllo che tutti i campi necessari alla creazione delle tabelle siano stati popolati: se non lo sono, blocco l'esecuzione
						//if((strlen($NomeTabella1)==0)or(strlen($NomeTabella2)==0)or($numeroCampiConDescrizionePopolata<$NumCampi))
							
						/*
						Ecco i nomi dei campi su cui costruire i controlli successivi
						$numeroCampiConDescrizionePopolata=0;
						$numeroCampiConDescrizioneMonocaratterialeErrata=0;
						$numeroCampiConDescrizionePluricaratterialeErrata=0;
						$numeroCampiConTipologiaPopolata=0;
						*/
						
						if($numeroCampiConDescrizionePopolata<$NumCampi)
						{
							echo("<font color='red' size=4><div align='justify'><b>Il nome di uno o pi&ugrave; campi delle tabelle non sono stati nominati; prima di continuare torna indietro con le frecce del browser per completare la form</b></div></font>");
						}
						elseif($numeroCampiConDescrizioneMonocaratterialeErrata>0)
						{
							echo("<font color='red' size=4><div align='justify'><b>&Egrave; stato inserito almeno un campo con nome composto da un solo carattere e questo carattere non era una lettera; torna indietro con le frecce del browser per correggere questo dato</b></div></font>");
						}
						elseif($numeroCampiConDescrizionePluricaratterialeErrata>0)
						{
							echo("<font color='red' size=4><div align='justify'><b>Uno o pi&ugrave; campi della tabella contengono caretteri non consentiti; torna indietro con le frecce del browser per correggere questo dato (sono ammessi solo  lettere e numeri)</b></div></font>");
						}
						elseif($numeroCampiConNomeUguale>0)
						{
							echo("<font color='red' size=4><div align='justify'><b>Almeno due campi della tabella hanno lo stesso nome; torna indietro con le frecce del browser e correggi la form in modo che tutti i campi abbiano nomi diversi tra loro</b></div></font>");
						}
						else
						{
							//creo le tabelle in funzione dei dati inseriti dall'utente
							for($i=0;$i<2;$i++)
							{
								if($i==0)
								{
									$testoQuery1="";
									$testoDaAggiungere="";
									$testoQuery1=$testoQuery1."create table ".$NomeTabella1."( Progressivo int NOT NULL AUTO_INCREMENT, ";
										for($j=0;$j<$NumCampi;$j++)
										{
											$testoDaAggiungere=$testoDaAggiungere.$j.$campo[$j]." ".$tipologia[$j].", ";
										}
									$testoQuery1=$testoQuery1.$testoDaAggiungere." primary key (Progressivo) );";					
								}
								if($i==1)
								{
									$testoQuery2="";
									$testoDaAggiungere="";
									$testoQuery2=$testoQuery2."create table ".$NomeTabella2."( Progressivo int NOT NULL AUTO_INCREMENT, ";
										for($j=0;$j<$NumCampi;$j++)
										{
											$testoDaAggiungere=$testoDaAggiungere.$j.$campo[$j]." ".$tipologia[$j].", ";
										}
									$testoQuery2=$testoQuery2.$testoDaAggiungere." primary key (Progressivo) );";
								}
							}
							//echo("Testo query tabella 1: ".$testoQuery1."<br>");
							//echo("Testo query tabella 2: ".$testoQuery2."<br>");
							$testoQuery1=str_replace("'","",$testoQuery1); // eventuali apici digitati al posto degli accenti li tolgo
							$testoQuery2=str_replace("'","",$testoQuery2); // eventuali apici digitati al posto degli accenti li tolgo
							$risultato=mysql_query($testoQuery1)
								or die("Impossibile creare la tabella ".$NomeTabella1.": ".mysql_error());
							$risultato=mysql_query($testoQuery2)
								or die("Impossibile creare la tabella ".$NomeTabella2.": ".mysql_error());
							
							
							//mando a video la struttura delle due tabelle create
							echo("<font size=5><b>Tabelle ".$NomeTabella1." e ".$NomeTabella2." create correttamente con la seguente struttura:<br></b></font><br>");
							$query="describe ".$NomeTabella1;
							$risultato=mysql_query($query)
								or die("Impossibile estrapolare la struttura della tabella ".$NomeTabella1.": ".mysql_error());
							$NumeroColonne=mysql_num_fields($risultato);
							echo("<table border=1>");
							echo("<tr>");
									echo("<td><b>Campo</b></td>
										  <td><b>Tipologia</b></td>
										  <td><b>PossibileCampoNullo</b></td>
										  <td><b>Chiave</b></td>
										  <td><b>EventualeValoreDefalut</b></td>
										  <td><b>AltreInformazioni</b></td>");
								echo("</tr>");
							$j=0;
							while($riga=mysql_fetch_row($risultato))
							{
								if($j!=0) // se $j=0 non mando a video nulla, perché il primo record della tabella è creato in automatico dal programma, a prescindere dalla configurazione stabilita dall'utente
								{
									echo("<tr>");
									for($i=0;$i<$NumeroColonne;$i++)
									{
										if(($riga[$i]=="int(11)") or ($riga[$i]=="double"))
											echo("<td>Numero</td>");
										elseif($riga[$i]=="varchar(1000)")
											echo("<td>Stringa</td>");
										elseif($riga[$i]=="date")
											echo("<td>Data</td>");
										elseif(($riga[$i]=="YES")or($riga[$i]=="NO"))
											echo("<td>".$riga[$i]."</td>");
										else
										{
											$lunghezzaCampo=strlen($riga[$i]);
											echo("<td>".substr($riga[$i],1,$lunghezzaCampo)."</td>");
										}
									}
									echo("</tr>");
								}
								$j++;
							}
							echo("</table>");
							$j=0;
							echo("<br><br>
								  <font color='blue' size=5><b>Determina ora in quali campi ricercare eventuali differenze tra il contenuto delle tabelle, spuntando il relativo checkbox.<br>
								  I campi non spuntati saranno considerati uguali tra le due tabelle e si utilizzeranno per stabilire quali record della prima tabella non sono presenti nella seconda e viceversa.</b></font><br>
								  <br>
								  <form name='GoToPopolamento' action='selezioneFile.php' method='POST'>");
							echo("<table border=1>");
							$query="describe ".$NomeTabella1;
							$risultato=mysql_query($query)
								or die("Impossibile estrapolare la struttura della tabella ".$NomeTabella1.": ".mysql_error());
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
							echo("<br>");
							echo("<input type='submit' name='invio' value='Accedi alla pagina di popolamento delle tabelle con i dati da confrontare'>
								</form>
								<br><br>
								<a href='index.php'>Torna alla pagina iniziale</a>
							");
						}
					}
				}
			}				
		}
		mysql_close($conn);
	?>	
	</body>
</html>