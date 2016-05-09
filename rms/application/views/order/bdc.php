<html>
<head>
<style type="text/css">
html {
	font-size: 100%;
}
body {
	font-size: 1em;
	line-height: 1.3;
	font-family: sans-serif /*{global-font-family}*/;
}
.table_bdc {
	border: 1px solid silver;
	padding: 6px;
}
.smallfont {
	font-size: 0.8em;
}
.delivery_title {
	font-size: 0.9em;
}
.delivery {
	font-size: 0.7em;
	padding-top: -10px;
	text-align: justify;
}
</style>
</head>
<center><h2>BON DE COMMANDE<br />N°<?=$info['idorder']?></h1></center>
	<table>
		<tr>
			<td>
<table class="smallfont" border="0" width="100%">
	<tr><td>S.A.S. HANK</td></tr>
	<tr><td>SIREN 798983706 00028</td></tr>
	<tr><td>APE 5610C</td></tr>
	<tr><td>TVA FR38798983706</td></tr>
	<tr><td>Tel : +33 (0)9 72 44 03 99</td></tr>
	<tr><td>Adresse de livraison : 55 rue des archives 75003 Paris</td></tr>
	<tr><td>Horaire de livraison : <?=$info['dlv_info']?></td></tr>
	<tr><td>Contact Hank pour cette commande : <?=$info['user']?></td></tr>
	<tr><td><a href="mailto:commande@hankrestaurant.com">commande@hankrestaurant.com</a> - Tel : <a href="phone:"><?=$info['user_tel']?></a></td></tr>
</table>
</td><td width="30px"></td><td style="border-left: 1px solid silver;" valign="top">
<table class="smallfont" border="0" width="100%">
	<tr><td>Date : <?=$info['date']?></td></tr>
	<tr><td>Société : <?=$info['sup_name']?> <br /> 
		Email: <a href="mailto:<?=$info['sup_email']?>"><?=$info['sup_email']?></a> <br />
		Tel: <?=$info['sup_tel']?></td></tr>
	<tr><td>Franco : <?=$info['franco']?></td></tr>
	<?if(isset($info['dlv_comt'])) {?> <tr><td><?=$info['dlv_comt']?></td></tr> <? } ?>
</table>
</td>
</tr>
</table>
<br />
<? if(!empty($info['comt'])) { ?><table width="100%"><tr class="table_bdc"><td class="table_bdc" valign="top">Commentaire : <b><font color="red"> <?=$info['comt']?></font></b></td></tr></table><? } ?>
<br />
<table width="100%">
<tr>
	<td class="table_bdc" align="center"><b>Designation</b></td>
	<td class="table_bdc" align="center"><b>Code Art.</b></td>
	<td class="table_bdc" align="center"><b>Colisage</b></td>
	<td class="table_bdc" align="center"><b>Qtte unitaire</b></td>
	<td class="table_bdc" align="center"><b>Sous total prix unitaire H.T.</b></td>
</tr>
<? foreach ($products as $key => $var) { ?>
<tr>
	<td class="table_bdc"><?=$var['name']?> <? if($var['attribut'] > 0) { echo $var['attributname']; } ?></td>
	<td class="table_bdc"><?=$var['codef']?></td>
	<td class="table_bdc"><?=$var['packaging']?> <?=$var['unitname']?></td>
	<td class="table_bdc"><?=$var['qtty']?></td>
	<td class="table_bdc"><?=($var['pric']*$var['qtty'])/1000?>€</td>
</tr>
<? } ?>
<tr><td class="table_bdc" align="left" colspan="5">Total H.T. : <?=$info['totalprice']/1000?>€</td></tr>
</table>
<span class="smallfont">Afin de faciliter votre paiement, merci de bien vouloir reporter ce numéro de BDC : <?=$info['idorder']?> sur vos factures et BL.</span>
<p class="delivery_title"><b>Conditions de livraison</b><br />
<b><font color="red">Informez votre transporteur</font></b></p>
<p class="delivery">- Livrer uniquement dans les horaires et jours déterminés : <?=$info['dlv_info']?>.<br />
- Le stationnement est délicat, mais possible sur les places de livraison, au coin de la rue ou en arrêt sur l'arrêt de bus (contactez-nous pour que nous venions vous aider pour ne pas quitter le camion). Interdiction de bloquer la rue, nous devons contrôler les marchandises.<br />
- Toutes les marchandises sont forcément contrôlées sans exception  AVANT la signature du Bon de transport. Si le livreur ne peut ou ne veut pas attendre, cette mention sera inscrite sur le BDT. Nous n'inscrirons jamais les mentions inutiles "sous réserve de truc et machin..." etc.<br />
- Merci de récupérer vos palettes, nous ne pouvons pas les stocker. Dans tous les cas, nous ne pouvons pas prendre en charge les frais de palette.<br />
- Café offert à tous les livreurs sympa! :-) </p>

   <script type="text/php">
    if ( isset($pdf) ) { 
        $pdf->page_script('
            if ($PAGE_COUNT > 1) {
                $font = Font_Metrics::get_font("Arial, Helvetica, sans-serif", "normal");
                $size = 12;
                $pageText = Page . " " . $PAGE_NUM . " sur " . $PAGE_COUNT;
                $y = 15;
                $x = 520;
                $pdf->text($x, $y, $pageText, $font, $size);
            } 
        ');
    }
</script>
</html>
