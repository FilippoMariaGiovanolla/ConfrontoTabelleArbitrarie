<html>
	<head>
		<title>Determina la struttura delle tabelle</title>
	</head>
	<body>
		<?php
			$quantiCampi=$_POST["NumCampi"];
			//echo("Numero campi delle tabelle: ".$quantiCampi);
			echo("<form name='struttura' action='CreazioneTabelle.php' method='POST'>
					<input type='hidden' name='NumCampi' value='$quantiCampi'>
					<table border=0 width='100%'>
						<tr>
							<td><b><font size=4>Determina il nome delle due tabelle da creare</font></b></td>
						</tr>
						<tr>
							<td>Nome tabella 1: <input type='text' name='NomeTabella1' size='30'></td>
						</tr>
						<tr>
							<td>Nome tabella 2: <input type='text' name='NomeTabella2' size='30'></td>
						</tr>					
					</table>
					<br>
					<table border=0 width='100%'>
					<tr>
						<td><b><font size=4>Indica il nome dei ".$quantiCampi." campi delle due tabelle<br>(strutturalmente identiche)</font></b></td>
					</tr>
				");				
				for($i=0;$i<$quantiCampi;$i++)
				{
				$numCampo=$i+1;
				$nomeCampo="NomeCampo".$numCampo;
				$tipologiaCampo="Tipologia".$numCampo;
				echo("<tr>
						<td width='35%'>Nome campo ".$numCampo." (senza spazi): <input type='text' name='$nomeCampo' size='30'></td>
						<td width='65%'>
							Tipologia:
							<select name='$tipologiaCampo'>
								<option value='varchar(1000)'>Testo
								<option value='double'>Numero
								<option value='date'>Data
							</select>
						</td>
					  </tr>");
				}
				echo("<tr>
						<td><br><input type='submit' name='invio' value='Continua'></td>
					  </tr>
					</table>
				</form>");
		?>
	</body>
</html>