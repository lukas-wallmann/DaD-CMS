<?php
	$lang=new stdClass();

	//General
	$lang->save="speichern";
	$lang->delete="löschen";
	$lang->up="hoch";
	$lang->down="runter";
	$lang->cancel="abbrechen";

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

	//Newsletter
	$lang->receiverlist="Empfängergruppe";
	$lang->newReceiverlist="neue Empfängergruppe";
	$lang->receiverImport="Importiere Empfänger aus .txt oder .csv Datei";
	$lang->import="importiern";
	$lang->firstLineHeading="Die erste Zeile enthält Überschriften";
	$lang->errorSelectAtLeastEmail="Bitte mindestens Feld Email für import auswählen";

	//Settings->user
	$lang->userName="Benutzername";
	$lang->rights="Rechte";
	$lang->language="Sprache";
	$lang->email="E-Mail";
	$lang->newUser="neuer Benutzer";
	$lang->createUser="Benutzer erstellen";
	$lang->password="Passwort";
	$lang->rightsRead="Rechte lesen";
	$lang->rightsWrite="Rechte schreiben";
	$lang->newPassword="Neues Passwort";
	$lang->deleteUser="Benutzer löschen";
	$lang->confirmDeleteUser="Benutzer wirklich löschen";

	//Settings->imageformats
	$lang->imageformatName="Bildformat";
	$lang->imageformatValue="Ausschnitt";
	$lang->imageformatFitin="Einpassen";
	$lang->imageformatCut="Ausschneiden";
	$lang->imageformatWidth="Breite";
	$lang->imageformatHeight="Höhe";

	//Footer
	$lang->footer="newCMS von Lukas Wallmann";
	$lang->logout="ausloggen";
?>
