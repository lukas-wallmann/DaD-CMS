<?php
	$lang=new stdClass();

	//General
	$lang->save="speichern";
	$lang->name="Name";
	$lang->new="neu";
	$lang->delete="löschen";
	$lang->cancel="abbrechen";
	$lang->language="Sprache";

	//Menu
	$lang->menu=array(
		array("Seiten","sites"),
		array("Menü","menu"),
		array("Dateien","files"),
		array("Newsletter","newsletter/newsletter",array(
			array("Newsletter","newsletter/newsletter"),
			array("Empfänger","newsletter/receivers"),
			array("Einstellungen","newsletter/settings")
		)),
		array("Einstellungen","settings/users",array(
			array("Benutzer","settings/users"),
			array("Cache","settings/caching"),
			array("Sprachen","settings/languages"),
			array("E-Mail Sendeeinstellung","settings/email"),
		)),
		array("Design","settings/themes")

	);

	//menus
	$lang->newMenu="Neues Menü erstellen";

	//sites
	$lang->allMenus="alle Menüs";
	$lang->newFormField="Neues Formularfeld";
	$lang->pleaseChooseFormFieldType="Bitte Feldtype auswählen";
	$lang->newSite="neue Seite";
	$lang->siteSettings="Seiteneinstellungen";
	$lang->noMenu="ohne Menüzuordnung";
	$lang->menuAssignment="Menüzuordnung";
	$lang->title="Titel";
	$lang->URL="Seiten-URL";
	$lang->fixURL="fixiere Seiten-URL";
	$lang->layout="Design";
	$lang->metaData="Meta Daten";
	$lang->metaTitle="Metatitel";
	$lang->metaTags="Meta Keywords";
	$lang->metaDescription="Metabeschreibung";
	$lang->teaser="Teaser";
	$lang->teaserName="Teasertitel";
	$lang->teaserPicture="Teaserbild";
	$lang->teaserText="Teasertext";
	$lang->teaserPrice="Teaserpreis";
	$lang->teaserGroup="Teasergruppe";

	//Newsletter
	$lang->newNewsletter="neuer Newsletter";
	$lang->receiverlist="Empfängergruppe";
	$lang->receivers="Empfänger";
	$lang->newReceiverlist="neue Empfängergruppe";
	$lang->deleteReceiverGroup="Empfängergruppe löschen";
	$lang->confirmDeleteReceiverGroup="Empfängergruppe wirklich löschen?";
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

	//Settings->themes
	$lang->importTheme="Design importiern";
	$lang->newTheme="Neues Design";
	$lang->newThemePart="Neues Design Teilstück";

	$lang->plugins="Plugins";
	$lang->pluginsVariants="Plugin Varianten";
	$lang->newPlugin="Neues Plugin";
	$lang->newJavascript="Neues Javascript";
	$lang->newCSS="Neues CSS";
	$lang->importPlugin="Plugin importieren";
	$lang->syncPlugin="Plugin syncronisieren";

	$lang->themeParts="Code Teile";
	$lang->themePlugins="Plugins";

	$lang->mainCode="Hauptcode";

	//settings->languages
	$lang->standardLang="Standard Sprache";

	//Settings->Cache
	$lang->cacheInfo="Damit nicht jede Seite immer generiert werden muss, werden die Seiten zwischengespeichert um schneller aufgerufen werden zu können. Sie haben die Wahl zwischen Datenbank und Dateisystem. Wenn sie beispielsweise eine SSD Datenbank zur Verfügung haben aber nur einen normalen Webspace so ist die Datenbank schneller. Andernfalls ist meist die Speicherung als Datei schneller";
	$lang->useDbCache="Datenbank nutzen";
	$lang->useFileCache="Dateien nutzen";

	//Settings->imageformats
	$lang->imageformatName="Bildformat";
	$lang->imageformatValue="Ausschnitt";
	$lang->imageformatFitin="Einpassen";
	$lang->imageformatCut="Ausschneiden";
	$lang->imageformatWidth="Breite";
	$lang->imageformatHeight="Höhe";

	//Footer
	$lang->footer="DaD-CMS von Lukas Wallmann";
	$lang->logout="ausloggen";

?>
