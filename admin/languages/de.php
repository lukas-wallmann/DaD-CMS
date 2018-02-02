<?php
	$lang=new stdClass();
	
	//Menu
	$lang->menu=array(
		array("Start","home"),
		array("Seiten","sites"),
		array("Header","header"),
		array("Newsletter","newsletter/newsletter",array(
			array("Newsletter","newsletter/newsletter"),
			array("Empfänger","newsletter/receivers")
		)),
		array("Einstellungen","settings/users",array(
			array("Benutzer","settings/users"),
			array("Sprache","settings/language"),
			array("Bildformate","settings/imageformats"),
			array("Cache","settings/caching")
		))
	);

	//Settings->user
	$lang->userName="Benutzername";
	$lang->rights="Rechte";
	$lang->language="Sprache";

	//Footer
	$lang->footer="newCMS von Lukas Wallmann";

?>
