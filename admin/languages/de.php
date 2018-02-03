<?php
	$lang=new stdClass();

	//General
	$lang->save="speichern";
	$lang->delete="löschen";
	$lang->up="hoch";
	$lang->down="runter";

	//Menu
	$lang->menu=array(
		array("Start","home"),
		array("Seiten","sites"),
		array("Header","header"),
		array("Newsletter","newsletter/newsletter",array(
			array("Newsletter","newsletter/newsletter"),
			array("Empfänger","newsletter/receivers"),
			array("Einstellungen","newsletter/settings")
		)),
		array("Einstellungen","settings/users",array(
			array("Benutzer","settings/users"),
			array("Bildformate","settings/imageformats"),
			array("Cache","settings/caching")
		))
	);

	//Settings->user
	$lang->userName="Benutzername";
	$lang->rights="Rechte";
	$lang->language="Sprache";
	$lang->email="E-Mail";

	//Settings->imageformats
	$lang->imageformatName="Bildformat";
	$lang->imageformatValue="Ausschnitt";
	$lang->imageformatFitin="Einpassen";
	$lang->imageformatCut="Ausschneiden";
	$lang->imageformatWidth="Breite";
	$lang->imageformatHeight="Höhe";

	//Footer
	$lang->footer="newCMS von Lukas Wallmann";

?>
