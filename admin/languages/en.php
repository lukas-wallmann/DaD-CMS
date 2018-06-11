<?php
	$lang=new stdClass();

	//General
	$lang->save="save";
	$lang->name="name";
	$lang->new="new";
	$lang->delete="delete";
	$lang->cancel="cancel";
	$lang->language="language";
	$lang->saved="saved";

	//Menu
	$lang->menu=array(
		array("Sites","sites"),
		array("Menu","menu"),
		array("Files","files"),
		array("Newsletter","newsletter/newsletter",array(
			array("Newsletter","newsletter/newsletter"),
			array("Receivers","newsletter/receivers"),
			array("Settings","newsletter/settings")
		)),
		array("Settings","settings/users",array(
			array("User","settings/users"),
			array("Cache","settings/caching"),
			array("Language","settings/languages"),
			array("E-Mail send settings","settings/email"),
		)),
		array("Theme","themes")

	);

	//menus
	$lang->newMenu="create new menu";

	//sites
	$lang->allMenus="all menus";
	$lang->newFormField="new form field";
	$lang->pleaseChooseFormFieldType="choose field type";
	$lang->newSite="new site";
	$lang->siteSettings="Site settings";
	$lang->noMenu="no menu assignment";
	$lang->menuAssignment="menu assignment";
	$lang->title="Title";
	$lang->URL="Site-URL";
	$lang->fixURL="fix site-URL";
	$lang->layout="Theme";
	$lang->metaData="Meta data";
	$lang->metaTitle="Meta title";
	$lang->metaTags="Meta keywords";
	$lang->metaDescription="Meta description";
	$lang->fixMeta="fix meta data";
	$lang->teaser="Teaser";
	$lang->teaserName="Teaser title";
	$lang->teaserPicture="Teaser image";
	$lang->teaserText="Teaser text";
	$lang->teaserPrice="Teaser price";
	$lang->teaserGroup="Teaser group";

	//Newsletter
	$lang->newNewsletter="new newsletter";
	$lang->sendNewsletter="send %%NAME%%";
	$lang->preview="preview";
	$lang->receiverlist="Receiver list";
	$lang->receivers="Receivers";
	$lang->newReceiverlist="new receiver list";
	$lang->deleteReceiverGroup="delete receiver list";
	$lang->confirmDeleteReceiverGroup="Really delete receiver list?";
	$lang->receiverImport="Import receivers form .txt or .csv";
	$lang->import="import";
	$lang->firstLineHeading="First line contains heading";
	$lang->errorSelectAtLeastEmail="Please choose at least field e-mail for import";

	//Settings->user
	$lang->userName="user name";
	$lang->rights="Rights";
	$lang->language="Language";
	$lang->email="E-Mail";
	$lang->newUser="new user";
	$lang->createUser="create user";
	$lang->password="Password";
	$lang->rightsRead="read rights";
	$lang->rightsWrite="write rights";
	$lang->newPassword="new password";
	$lang->deleteUser="delete user";
	$lang->confirmDeleteUser="really delete user?";

	//Settings->themes
	$lang->importTheme="import theme";
	$lang->newTheme="new theme";
	$lang->newThemePart="new theme part";

	$lang->plugins="Plugins";;

	$lang->themeParts="Code parts";
	$lang->themePlugins="Plugins";

	$lang->mainCode="Main code";

	//settings->languages
	$lang->standardLang="Standard language";

	//Settings->Cache
	$lang->cacheInfo="Where the sites should be chached? In database is fast if it runs on SSD while the rest not.";
	$lang->useDbCache="use database";
	$lang->useFileCache="use filesystem";

	//Settings->imageformats
	$lang->imageformatName="image format";
	$lang->imageformatValue="cut";
	$lang->imageformatFitin="fit in";
	$lang->imageformatCut="cut";
	$lang->imageformatWidth="width";
	$lang->imageformatHeight="height";

	//Footer
	$lang->footer="DaD-CMS by Lukas Wallmann";
	$lang->logout="log out";

?>
