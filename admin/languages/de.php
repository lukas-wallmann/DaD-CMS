<?php
	$lang=new stdClass();
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
	$lang->footer="newCMS von Lukas Wallmann";
?>