<?php
/*****************************************************************************************
** © 2013 POULAIN Nicolas – nico.public@ouvaton.org - http://tounoki.org **
** **
** Ce fichier est une partie du logiciel libre joconde2xml, licencié **
** sous licence "GPL V3". **
** La licence est décrite plus précisément dans le fichier : LICENSE.txt **
** **
** ATTENTION, CETTE LICENCE EST GRATUITE ET LE LOGICIEL EST **
** DISTRIBUÉ SANS GARANTIE D'AUCUNE SORTE **
** ** ** ** **
** This file is a part of the free software project joconde2xml,
** licensed under the "GPL V3". **
**The license is discribed more precisely in LICENSES.txt **
** **
**NOTICE : THIS LICENSE IS FREE OF CHARGE AND THE SOFTWARE IS DISTRIBUTED WITHOUT ANY **
** WARRANTIES OF ANY KIND **
*****************************************************************************************/

header('Content-type: text/xml; charset=utf-8');

$JOCONDEFIELDS = array(
	"INV",
	"ANCNUM",
	"AUTNUM",
	"NUMDEP",
	"DOMN",
	"DENO",
	"APPL",
	"TITR",
	"AUTR",
	"PAUT",
	"ECOL",
	"ATTR",
	"PERI",
	"MILL",
	"EPOQ",
	"PEOC",
	"TECH",
	"DIMS",
	"INSC",
	"PINS",
	"ONOM",
	"DESC",
	"ETAT",
	"REPR",
	"PREP",
	"DREP",
	"SREP",
	"GENE",
	"OASS",
	"HIST",
	"LIEUX",
	"PLIEUX",
	"GEOHI",
	"UTIL",
	"PUTI",
	"LIEU",
	"PLIEU",
	"PERU",
	"MILU",
	"LIEUDECV",
	"SITE",
	"COLLECTE",
	"DATEDECV",
	"NOMDECV",
	"PDEC",
	"NSDA",
	"DINV",
	"DDEP",
	"PROP",
	"ACQU",
	"PRIX",
	"MENTION",
	"PROPRI",
	"AFF",
	"DACQ",
	"DAFF",
	"AVIS",
	"DONA",
	"APTN",
	"DEPO",
	"DDPT",
	"FINDPT",
	"ADPT",
	"LOCA",
	"EXPO",
	"BIBL",
	"COMM",
	"PHOT",
	"REDA",
	"IMAGE",
	"REFIM",
	"REFMIS",
	"REF",
	"STAT",
	"COPY"
) ;



$xmlstr = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
<records>
</records>" ;
$xml = new SimpleXMLElement($xmlstr) ;

$i = -1 ;
$nbreFinal = 0 ;
if (($handle = fopen("./data.csv", "r")) !== FALSE) { // fichier csv avec | sép de champs et " délim texte
	while (($data = fgetcsv($handle, 0, "|")) !== FALSE) {
		$i++ ;		
		if ( $i <= 1 ) continue ;
		if ( $i > 2 ) continue ; // 
		if ( !is_array($data) ) continue ;
		if ( strpos($data[3], "REF" ) === false ) continue ;
		//print_r($data);
		// $data[0] cote
		// $data[1] support
		// $data[2] auteur
		// $data[3] joconde
		$lastRecord = appendJocondeInXML($xml,$data[3]) ;
		
		/*
		x _filename M0354_2-084-56_1.jpg
		_url http://www.tounoki.org/wikimedia/2006/DV5_M0354_2006_7/DV5_M0354_2006_7_jpg/M0354_2-084-56_1.jpg
		x Support DV5_M0354_2006_7
		_ext tif
		x Cote M0354_2-084-56_1
		x Auteur BERNARDOT Claude-Henry
		*/
		$lastRecord->addChild( "_filename" , $data[0].".jpg" ) ;
		$lastRecord->addChild( "cote" , $data[0] ) ;
		$lastRecord->addChild( "support" , $data[1] ) ;
		$lastRecord->addChild( "auteur" , $data[2] ) ;
		$lastRecord->addChild( "_ext" , ".jpg" ) ;
		$supportElements = explode( "_" , $data[1] ); // DV5_M0354_2006_7
		$lastRecord->addChild( "_url" , "http://www.tounoki.org/wikimedia/".$supportElements[2]."/".$data[1]."/".$data[0].".jpg" ) ;
		$lastRecord->addChild( "_urlHD" , "http://musees.cg70.fr/wikimedia/".$supportElements[2]."/".$data[1]."/".$data[1]."_tif/".$data[0].".tif" ) ;	
		
		// ajouts manuels en dur / corrections
		$lastRecord->addChild( "photo_licence" , "{{Cc-by-sa 3.0}}" ) ;
		$lastRecord->addChild( "institution" , "{{:museum:Musées départementaux de la Haute-Saône}}" ) ;
		
		$nbreFinal++ ;
		$lastRecord->addChild( "i" , $nbreFinal ) ;
	}
	
	fclose($handle);
}

// ajoute un élément complet sans image / juste pour mappage
if ( 0 == 1 ) {
	$record = $xml->addChild('record') ;
	foreach( $JOCONDEFIELDS as $jf ) {
		$record->addChild( "JOCONDE_".$jf , $jf ) ;
	}
	$record->addChild( "_url" , "http://www.tounoki.org/wikimedia/fichierTemoin.png" ) ;
	$record->addChild( "cote" , "Fichier témoin pour versements des musées départementaux de la Haute-Saône" ) ;
}

// infos générales
$xml->upload_infos->nbreFinalExport = $nbreFinal ;
$xml->upload_infos->addchild('date',date('l jS \of F Y h:i:s A')) ;

echo $xml->asXml() ;

// pour tests et essais
$data_joconde = "REF
M0354002279
REFMIS
M0354002279
REFIM
M035432_001313_P.JPG,DS1,,001313.JPG
PHOT
© BERNARDOT Claude-Henry
IMAGE
Oui
DOMN
vie sociale - culturelle ; vie domestique ; costume - accessoires du costume
INV
1951.15.23
DENO
sabot de femme (paire)
TECH
noyer (taillé, polychrome)
DIMS
L. 29 cm ; l. 9 cm ; H. 6 cm
PERI
?
MILL
1874 vers
ETAT
Bon état général ; 30/06/2005
INSC
inscription
PINS
Probablement le nom du propriétaire de l’objets : Rolin
DESC
Paire de sabots à ouverture large, au bord rouge teinté à l’extérieur. Le boût est de forme carrée arrondie. Les sabots présente un talon de 1,45 cm. L’inscription Rolin est apposée sous le pied gauche au crayon. Gland et feuille de chêne ornent l’avant de la paire.
UTIL
pratique religieuse : mariage ; chaussure
PUTI
Sabots portés le jour du mariage par la mariée, conservée par la suite. ; femme (utilisateur)
REPR
représentation végétale (chêne, feuille, gland)
PREP
décorations à l’avant de la chaussure.
STAT
propriété du département ; Haute-Saône ; don ; Musée Départemental Albert Demard
BIBL
p.53
LOCA
Champlitte ; Musée Départemental Albert Demard
COPY
© Champlitte ; Musée Départemental Albert Demard, 2005, © Service des Musées de France, 2014
REDA
POULAIN Nicolas
//" ;



function appendJocondeInXML(&$xml,$data_joconde) {
	global $JOCONDEFIELDS ;

	
	// traitement entrée
	$elements = preg_split("/[\n]+/", $data_joconde) ;
	
	/*
	echo "<pre>" ;
	print_r($elements) ;
	echo "</pre>" ;
	*/
	
	$i = 0 ;
	//while ( $elements[$i] != "//" ) {
	while ( isset($elements[$i]) && $elements[$i] != "//" ) {
			/*			
			if ( preg_match('/[A-Z]{3,6}/',trim($elements[$i]) ) ) {
				$JOCName = $elements[$i] ;
					$tab[ "JOCONDE_".$JOCName ] = $elements[$i+1] ;
					$i++ ; $i++ ;
			}
			*/
			if ( in_array($elements[$i],$JOCONDEFIELDS) ) {
				$JOCName = $elements[$i] ;
				$tab[ "JOCONDE_".$JOCName ] = "" ;
//				print_r($tab) ;
				$k = 0 ;
				while ( !in_array($elements[$i+1],$JOCONDEFIELDS) && $k < 5 && $elements[$i+1] != "//" ) {
					$tab[ "JOCONDE_".$JOCName ] .= $elements[$i+1] ;
					//echo ("JOCONDE_".$JOCName."recoit ".$elements[$i+1]."<br>") ;
					$i++ ;
					$k++ ; // sécurité
				}
			}
			else $i++ ;
			
		}
	
	/*
	echo "<pre>" ;
	print_r($tab) ;
	echo "</pre>" ;
	*/
	
	// traitements spécifiques
	if ( isset($tab['JOCONDE_DACQ']) )
		$tab['JOCONDE_DACQ_wrapped'] = "{{ProvenanceEvent|time=".$tab['JOCONDE_DACQ']."|type=acquisition|newowner=Musées départementaux de la Haute-Saône}}" ;
	if ( isset($tab['JOCONDE_DESC']) )
		$tab['JOCONDE_DESC_wrapped'] = "{{Original caption|lang=fr|1=".$tab['JOCONDE_DESC']."}}" ;
	$tab['JOCONDE_APTN'] = NULL ; // infos nominatives
	if ( isset(	$tab['JOCONDE_REFMIS'] ) )
		$tab['JOCONDE_REF_wrapped'] = "{{online databases|{{Joconde|".$tab['JOCONDE_REF']."}}}}" ;
	// dimensions
	// L. 29 cm ; l. 9 cm ; H. 6 cm
	// {{Size|unit=cm|length=179.0|height=221.0|width=107.0|depth=|diameter=|thickness=}}
	if ( isset( $tab['JOCONDE_DIMS'] ) ) {
		$dims[] = "Size" ;
		$dims[] = "unit=cm" ;
		
		$dims = array_merge( $dims , explode( ";" , str_replace(",",".",$tab['JOCONDE_DIMS']) ) ) ;
		for ( $k=1 ; $k<count($dims) ; $k++ ) {
			$dims[$k] = str_replace(" cm", "", $dims[$k] ) ;
			if ( strpos($dims[$k] , "L. " ) !== false )	$dims[$k] = trim( str_replace("L. ", "lenght=", $dims[$k] ) ) ;
			elseif ( strpos($dims[$k] , "H." ) !== false )	$dims[$k] = trim( str_replace("H. ", "height=", $dims[$k] ) ) ;
			elseif ( strpos($dims[$k] , "l." ) !== false )	$dims[$k] = trim( str_replace("l. ", "width=", $dims[$k] ) ) ;
			elseif ( strpos($dims[$k] , "P." ) !== false )	$dims[$k] = trim( str_replace("P. ", "depth=", $dims[$k] ) ) ;
			elseif ( strpos($dims[$k] , "D." ) !== false )	$dims[$k] = trim( str_replace("D. ", "diameter=", $dims[$k] ) ) ;
			elseif ( strpos($dims[$k] , "E." ) !== false )	$dims[$k] = trim( str_replace("E. ", "thickness=", $dims[$k] ) ) ;
			}
		$tab['JOCONDE_DIMS_commons'] = "{{".implode( "|", $dims)."}}" ;
	}
	
	$record = $xml->addChild('record') ;
	foreach ( $tab as $field => $data ) {
		$record->addChild($field, htmlspecialchars($data) ) ;
		}
	return $record ; // enregistrement en cours / objet simplexml
}




?>