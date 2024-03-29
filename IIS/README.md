# iis_proj

Informační systém pro jídelny a rozvoz jídla

- PHP
- MySQL

<h1>Jídelna</h1> <!-- Nahradte názvem svého zadání -->

<h2>Uživatelé systému pro testování</h2>
<p>Uveřte prosím existující zástupce <strong>všech rolí uživatelú</strong>.</p>
<table>
<tr><th>Email</th><th>Heslo</th><th>Role</th></tr>
<tr><td>admin@jidelna.cz</td><td>admin</td><td>Administrátor</td></tr>
<tr><td>LadNov@jidelna.cz</td><td>heslo</td><td>Operátor</td></tr>
<tr><td>novak@jidelna.cz</td><td>heslo</td><td>Řidič</td></tr>
<tr><td>novy@jidelna.cz</td><td>heslo</td><td>Konzument</td></tr>
</table>

<p>(Diagram případú užití není nutné vkládat, pokud IS implementuje role a případy užití definované zadáním.)</p> 

<h2>Implementace</h2>
<p>Stručná dokumentace k implementaci, která popisuje, které části projektu (např. PHP skripty) implementují jednotlivé případy použití.</p>
<h4>Admin</h4>
<ul>
	<li>Admin/Accounts.php - Výpis všech uživatelů, kde admin může vyhledávat a následně si je rozkliknout</li>
	<li>Admin/Add_jidelna.php - Stránka pro vytváření nových jídelen, a přiřazení operátora pro nově vytvořenou jídelnu</li>
	<li>Admin/Jidelny.php - Výpis všech jídelen, může si je rozkliknout a změnit</li>
</ul>
<h4>Operátor</h4>
<ul>
	<li>Op/Add_jidlo.php - Přidá nové jídlo do databáze</li>
	<li>Op/Dat_zakazky.php - Stránka kde operátor uvidí nově příchozí zakázky, má možnost je rozkliknout a přiřadit řidiče</li>
	<li>Op/Jidelna.php - Detail jídelny, může jí měnit údaje (může změnit stav jídelny na Otevřená - Zavřená)</li>
	<li>Op/Manage_jidelnicek.php - Stránka kde se vypíše jídelníček pro určitý den (pokud je), operátor ho může změnit</li>
	<li>Op/Moje_jidelny - Výpis všech jídelen u kterých je přihlášený uživatel jako operátor, může je rozkliknout a dostat se do jidelna.php</li>
</ul>
<h4>Řidič</h4>
<ul>
	<li>Ridic/Zakazky.php - Jsou zde vidět zakázky, které jsou přihlášenému řidiči přiřazeny</li>
</ul>
<h4>Konzument</h4>
<ul>
	<li>Account/User.php - Výpis informací o useru</li>
	<li>Account/Manage_user.php - Formulář pro změnu informací v db</li>
	<li>Account/Moje_objednavky.php - Seznam objednávek přihlášeného uživatele</li>
	
</ul>
<h4>Obecné</h4>
<ul>
	<li>Account/Login.php - Pro přihlášení / odhlášení</li>
	<li>Account/Register.php - Pro registraci</li>
	<li>Index.php - Úvodní stránka kde uživatel uvidí vypsané jídelny, které jsou právě v provozu</li>
	<li>Jidlo/Jidelnicek.php - Jídelníček na jeden den jedné jídelny</li>
	<li>Jidlo/Jidlo.php, Jidlo/Objednat.php - Soubory pro objednání jídla, kde si uživatel postupně vybere kolik jakého chce jídla a následně vyplní kontaktní údaje</li>
	<li>Jidlo/Objednavka.php - Pokud je uživatel přihlášený očekává v url parametr obj s id objednávky, kterou chce vidět. Pokud není přihlášený zobrazí se textové pole do, kterého může vložit kód objednávky, kterou chce sledovat</li>
</ul>
<h3>Databáze</h3>
<img alt="schéma databáze" src="iis_db.png">
Zde vložte grafické schéma relační databáze (tabulek v databázi).

<h2>Instalace</h2>

Stručnì popište: 
<ul>
<li>postup instalace na server - vložit všechny soubory a složky (mimo složky doc a souboru iis_jidelna.sql)</li>
<li>softwarové požadavky (verze PHP apod.) - web byl testován s PHP vezemi 7.2.14 a na web hostingu je verze 7.1.33</li>
<li>jak rozbalit, konfigurovat, inicializovat databázi - vytvořit databázy a importnout data pomocí iis_jidelna.sql, v souboru functions.php změnit connection string v první funkci</li>
</ul>

<h2>Známé problémy</h2>
<p>Z časových dúvodú nebyla implementována správa uživatelú.</p>
