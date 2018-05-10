	
/* c206.c **********************************************************}
{* Téma: Dvousměrně vázaný lineární seznam
**
**                   Návrh a referenční implementace: Bohuslav Křena, říjen 2001
**                            Přepracované do jazyka C: Martin Tuček, říjen 2004
**                                            Úpravy: Kamil Jeřábek, říjen 2017
**
** Implementujte abstraktní datový typ dvousměrně vázaný lineární seznam.
** Užitečným obsahem prvku seznamu je hodnota typu int.
** Seznam bude jako datová abstrakce reprezentován proměnnou
** typu tDLList (DL znamená Double-Linked a slouží pro odlišení
** jmen konstant, typů a funkcí od jmen u jednosměrně vázaného lineárního
** seznamu). Definici konstant a typů naleznete v hlavičkovém souboru c206.h.
**
** Vaším úkolem je implementovat následující operace, které spolu
** s výše uvedenou datovou částí abstrakce tvoří abstraktní datový typ
** obousměrně vázaný lineární seznam:
**
**      DLInitList ...... inicializace seznamu před prvním použitím,
**      DLDisposeList ... zrušení všech prvků seznamu,
**      DLInsertFirst ... vložení prvku na začátek seznamu,
**      DLInsertLast .... vložení prvku na konec seznamu, 
**      DLFirst ......... nastavení aktivity na první prvek,
**      DLLast .......... nastavení aktivity na poslední prvek, 
**      DLCopyFirst ..... vrací hodnotu prvního prvku,
**      DLCopyLast ...... vrací hodnotu posledního prvku, 
**      DLDeleteFirst ... zruší první prvek seznamu,
**      DLDeleteLast .... zruší poslední prvek seznamu, 
**      DLPostDelete .... ruší prvek za aktivním prvkem,
**      DLPreDelete ..... ruší prvek před aktivním prvkem, 
**      DLPostInsert .... vloží nový prvek za aktivní prvek seznamu,
**      DLPreInsert ..... vloží nový prvek před aktivní prvek seznamu,
**      DLCopy .......... vrací hodnotu aktivního prvku,
**      DLActualize ..... přepíše obsah aktivního prvku novou hodnotou,
**      DLSucc .......... posune aktivitu na další prvek seznamu,
**      DLPred .......... posune aktivitu na předchozí prvek seznamu, 
**      DLActive ........ zjišťuje aktivitu seznamu.
**
** Při implementaci jednotlivých funkcí nevolejte žádnou z funkcí
** implementovaných v rámci tohoto příkladu, není-li u funkce
** explicitně uvedeno něco jiného.
**
** Nemusíte ošetřovat situaci, kdy místo legálního ukazatele na seznam 
** předá někdo jako parametr hodnotu NULL.
**
** Svou implementaci vhodně komentujte!
**
** Terminologická poznámka: Jazyk C nepoužívá pojem procedura.
** Proto zde používáme pojem funkce i pro operace, které by byly
** v algoritmickém jazyce Pascalovského typu implemenovány jako
** procedury (v jazyce C procedurám odpovídají funkce vracející typ void).
**/

#include "c206.h"

int solved;
int errflg;

void DLError() {
/*
** Vytiskne upozornění na to, že došlo k chybě.
** Tato funkce bude volána z některých dále implementovaných operací.
**/	
    printf ("*ERROR* The program has performed an illegal operation.\n");
    errflg = TRUE;             /* globální proměnná -- příznak ošetření chyby */
    return;
}

void DLInitList (tDLList *L) {
/*
** Provede inicializaci seznamu L před jeho prvním použitím (tzn. žádná
** z následujících funkcí nebude volána nad neinicializovaným seznamem).
** Tato inicializace se nikdy nebude provádět nad již inicializovaným
** seznamem, a proto tuto možnost neošetřujte. Vždy předpokládejte,
** že neinicializované proměnné mají nedefinovanou hodnotu.
**/

	// Prvy prvok v zozname
	L->First = NULL; 

	// Aktualny prvok v zozname 
	L->Act = NULL;

	// Posledny prvok v zozname
	L->Last = NULL; 
}

void DLDisposeList (tDLList *L) {
/*
** Zruší všechny prvky seznamu L a uvede seznam do stavu, v jakém
** se nacházel po inicializaci. Rušené prvky seznamu budou korektně
** uvolněny voláním operace free. 
**/

	// cyklus prechádza cely zoznam
	while (L->First != NULL) {

		// prvy prvok vlozime do docasneho
		tDLElemPtr temp = L->First;

		// nastavenie noveho prveho prvku na dalsi prvok vpravo
		L->First = L->First->rptr;

		// uvolnenie prvku
		free(temp);
	}

	// nastavenie zoznamu do defaultneho stavu
	L->First = NULL;
	L->Act = NULL;
	L->Last = NULL;
}

void DLInsertFirst (tDLList *L, int val) {
/*
** Vloží nový prvek na začátek seznamu L.
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/

	// alokujeme pamat pre docasny prvok
	tDLElemPtr temp = malloc(sizeof(struct tDLElem));

	// skontrolujeme ci sa naalokovala pamat pre novy prvok
	if (temp != NULL) {

		// vlozime data
		temp->data = val;

		// ukazovatel ukazuje na prvy prvok vpravo
		// ak je prvok novy potom ma hodnotu NULL
		temp->rptr = L->First;

		// nalavo nemame nic
		temp->lptr = NULL; 

		// Ak prvok v zozname nie je prvý
		if (L->First != NULL) {

			// odkaz na novy prvok vlavo
			L->First->lptr = temp; 
		}
		else {

			// odkazujeme na novy prvok, kedze je zoznam prazdny
			L->Last = temp;			
		}

		// vlozeny prvok bude prvy v zozname
		L->First = temp;
	}
	else {

		// ak nastane chyba pri alokacii pamate
		DLError();
	}
}

void DLInsertLast(tDLList *L, int val) {
/*
** Vloží nový prvek na konec seznamu L (symetrická operace k DLInsertFirst).
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/ 	
	
	// alokujeme pamat pre docasny prvok
	tDLElemPtr temp = malloc(sizeof(struct tDLElem));

	// skontrolujeme ci sa naalokovala pamat pre novy prvok
	if (temp != NULL) {

		// vlozime data
		temp->data = val;

		// nastavime ukazovatel vlavo z noveho prvku na posledny prvok
		temp->lptr = L->Last;

		// ukazovatel vpravo nastavime na NULL
		temp->rptr = NULL;

		// ak prvok nie je v zozname jediny
		if (L->First != NULL) {

			// naviazeme z posledneho prvku na novy prvok
			L->Last->rptr = temp;
		}
		else {

			// ak je v zozname jediny
			L->First = temp;
		}

		// novy prvok nastavime ako posledny
		L->Last = temp;

	}
	else {

		// ak nastane chyba pri alokacii pamate
		DLError();
	}
}

void DLFirst (tDLList *L) {
/*
** Nastaví aktivitu na první prvek seznamu L.
** Funkci implementujte jako jediný příkaz (nepočítáme-li return),
** aniž byste testovali, zda je seznam L prázdný.
**/

	// Nastavi aktivitu na prvy prvok zoznamu
	L->Act = L->First;
}

void DLLast (tDLList *L) {
/*
** Nastaví aktivitu na poslední prvek seznamu L.
** Funkci implementujte jako jediný příkaz (nepočítáme-li return),
** aniž byste testovali, zda je seznam L prázdný.
**/
	
	// Nastavi aktivitu na posledny prvok zoznamu
	L->Act = L->Last;
}

void DLCopyFirst (tDLList *L, int *val) {
/*
** Prostřednictvím parametru val vrátí hodnotu prvního prvku seznamu L.
** Pokud je seznam L prázdný, volá funkci DLError().
**/

	// ak nie je zoznam prazdny
	if (L->First != NULL) {

		// vrati hodnotu prveho prvku v zozname
		*val = L->First->data;
	}
	else {

		// inak zavola chybovu funkciu
		DLError();
	}
}

void DLCopyLast (tDLList *L, int *val) {
/*
** Prostřednictvím parametru val vrátí hodnotu posledního prvku seznamu L.
** Pokud je seznam L prázdný, volá funkci DLError().
**/

	// ak zoznam nie je prazdny
	if (L->First != NULL) {

		// vrati hodnotu posledneho prvku v zozname
		*val = L->Last->data;
	}
	else {

		// inak zavola chybovu funkciu
		DLError();
	}
}

void DLDeleteFirst (tDLList *L) {
/*
** Zruší první prvek seznamu L. Pokud byl první prvek aktivní, aktivita 
** se ztrácí. Pokud byl seznam L prázdný, nic se neděje.
**/

	// skontrolujeme ci nie je zoznam prazdny
	if (L->First != NULL) {

		// do docasneho prvku ulozime prvy prvok
		tDLElemPtr temp = L->First;

		// ak je prvy prvok aktivny
		if (L->Act == L->First) {

			// strati aktivitu
			L->Act = NULL;
		}

		// ak je jediny prvok v zozname
		if (L->First == L->Last) {

			// vynulujeme zoznam
			L->First = NULL;
			L->Act = NULL;
			L->Last = NULL;
		}
		else {

			// aktualizacia zaciatku zoznamu
			L->First = L->First->rptr;

			// ukazovatel prveho vlavo vynulujeme
			L->First->lptr = NULL;
		}

		// uvolnime prvok
		free(temp);
	}
}	

void DLDeleteLast (tDLList *L) {
/*
** Zruší poslední prvek seznamu L. Pokud byl poslední prvek aktivní,
** aktivita seznamu se ztrácí. Pokud byl seznam L prázdný, nic se neděje.
**/ 

	// skontrolujeme ci zoznam nie je prazdny
	if (L->First != NULL) {

		// do docasneho prvku ulozime posledny prvok
		tDLElemPtr temp = L->Last;

		// ak je posledny prvok aktivny
		if (L->Act == L->Last) {

			// strati aktivitu
			L->Act = NULL;
		}

		// ak je v zozname jediny prvok
		if (L->First == L->Last) {

			// vynulujeme zoznam
			L->First = NULL;
			L->Act = NULL;
			L->Last = NULL;

			return;
		}
		else {

			// nastavime novy koncovy prvok
			L->Last = L->Last->lptr;

			// pre novy koncovy prvok nastavime ukazovatel vpravo na NULL
			L->Last->rptr = NULL;
		}

		// uvolnime docasny prvok
		free(temp);
	}
}

void DLPostDelete (tDLList *L) {
/*
** Zruší prvek seznamu L za aktivním prvkem.
** Pokud je seznam L neaktivní nebo pokud je aktivní prvek
** posledním prvkem seznamu, nic se neděje.
**/

	// kontrolujeme ci je zoznam aktivny a ci posledny prvok nie je aktivny
	if (L->Act != NULL && L->Act == L->Last) {

		// kontrolujeme ci je co rusit
		if (L->Act->rptr != NULL) {

			// ukazovatel na ruseny prvok
			tDLElemPtr temp = L->Act->rptr;

			// preklenutie ruseneho prvku
			L->Act->rptr = temp->rptr;

			// ak je ruseny posledny
			if (temp == L->Last) {

				// posledny prvok nastavime ako aktivny
				L->Last = L->Act;
			}
			else {

				// prvok za zrušeným ukazuje vlavo na aktivny prvok
				temp->rptr->lptr = L->Act;
			}

			// uvolnime stary prvok
			free(temp);
		}
	}
}

void DLPreDelete (tDLList *L) {
/*
** Zruší prvek před aktivním prvkem seznamu L .
** Pokud je seznam L neaktivní nebo pokud je aktivní prvek
** prvním prvkem seznamu, nic se neděje.
**/

	// kontrolujeme ci nie je aktivny prvy prvok a ci je zoznam aktivny
	if (L->Act != NULL && L->Act == L->First) {

		// kontrolujeme ci je co rusit
		if (L->Act->lptr != NULL) {

			// ukazovatel na ruseny prvok
			tDLElemPtr temp = L->Act->lptr;

			// preklenutie ruseneho
			L->Act->lptr = temp->lptr;

			// ak je ruseny prvy
			if (temp == L->First) {

				// prvy bude aktivnym
				L->First = L->Act;
			}
			else {

				// prvok pred zrusenym ukazuje doprava na aktivny
				temp->lptr->rptr = L->Act;
			}

			// rusime prvok
			free(temp);
		}
	}
}

void DLPostInsert (tDLList *L, int val) {
/*
** Vloží prvek za aktivní prvek seznamu L.
** Pokud nebyl seznam L aktivní, nic se neděje.
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/

	// kontrolujeme ci je prvok aktivny
	if (L->Act != NULL) {

		// alokujeme pamat pre docasny prvok 
		tDLElemPtr temp = malloc(sizeof(struct tDLElem));

		// kontrolujeme ci sa pamat naalokovala spravne
		if (temp != NULL) {
		
			// vlozime data
			temp->data = val;

			// nastavenie ukazovatelov noveho prvku
			temp->rptr = L->Act->rptr;
			temp->lptr = L->Act;

			// nastavenie ukazovatela aktivneho prvku na novy prvok
			L->Act->rptr = temp;

			// ak je aktivny prvok poslednym prvkom
			if (L->Act == L->Last) {

				// zmenime na novy prvok
				L->Last = temp;
			}
			else {

				// zmenime ukazovatel nasledovnika na novy prvok
				temp->rptr->lptr = temp;
			}
		}
		else {

			// Ak nastala chyba pri alokacii
			DLError();
		}
	}
}

void DLPreInsert (tDLList *L, int val) {
/*
** Vloží prvek před aktivní prvek seznamu L.
** Pokud nebyl seznam L aktivní, nic se neděje.
** V případě, že není dostatek paměti pro nový prvek při operaci malloc,
** volá funkci DLError().
**/
	
	// kontrolujeme ci je prvok aktivny
	if (L->Act != NULL) {

		// alokujeme pamat pre docasny prvok 
		tDLElemPtr temp = malloc(sizeof(struct tDLElem));

		// kontrolujeme ci sa pamat naalokovala spravne
		if (temp != NULL) {
		
			// vlozime data
			temp->data = val;

			// nastavenie ukazovatelov noveho prvku
			temp->rptr = L->Act;
			temp->lptr = L->Act->lptr;

			// nastavenie ukazovatela aktivneho prvku na novy prvok
			L->Act->lptr = temp;

			// ak je aktivny prvok poslednym prvkom
			if (L->Act == L->Last) {

				// zmenime na novy prvok
				L->First = temp;
			}
			else {

				// zmenime ukazovatel nasledovnika na novy prvok
				temp->rptr->lptr = temp;
			}
		}
		else {

			// Ak nastala chyba pri alokacii
			DLError();
		}
	}
}

void DLCopy (tDLList *L, int *val) {
/*
** Prostřednictvím parametru val vrátí hodnotu aktivního prvku seznamu L.
** Pokud seznam L není aktivní, volá funkci DLError ().
**/

	if (L->Act != NULL) {

		// vrati hodnotu aktivneho prvku zoznamu
		*val = L->Act->data;
	}
	else {

		// inak zavola chybovu funkciu
		DLError();
	}
}

void DLActualize (tDLList *L, int val) {
/*
** Přepíše obsah aktivního prvku seznamu L.
** Pokud seznam L není aktivní, nedělá nic.
**/

	// skontrolujeme ci je zoznam aktivny
	if (L->Act != NULL) {

		// ak ano, prepise obsah aktivneho prvku
		L->Act->data = val;
	}
}

void DLSucc (tDLList *L) {
/*
** Posune aktivitu na následující prvek seznamu L.
** Není-li seznam aktivní, nedělá nic.
** Všimněte si, že při aktivitě na posledním prvku se seznam stane neaktivním.
**/
	
	// skontrolujeme ci je zoznam aktivny
	if (L->Act != NULL) {

		// skontrolujeme ci nie je aktivny posledny prvok
		if (L->Act != L->Last) {

			// ak nie, posunie aktivitu na nasledujuci prvok
			L->Act = L->Act->rptr;
		}
		else {

			// ak je aktivny posledny prvok, zoznam sa stane neaktivny
			L->Act = NULL;
		}
	}
}


void DLPred (tDLList *L) {
/*
** Posune aktivitu na předchozí prvek seznamu L.
** Není-li seznam aktivní, nedělá nic.
** Všimněte si, že při aktivitě na prvním prvku se seznam stane neaktivním.
**/
	
	// skontrolujeme ci je zoznam aktivny
	if (L->Act != NULL) {

		// skontrolujeme ci nie je aktivny prvy prvok
		if (L->Act != L->First) {

			// ak nie, posunie aktivitu na predchadzajuci prvok
			L->Act = L->Act->lptr;
		}
		else {

			// ak je aktivny prvy prvok, zoznam sa stane neaktivny
			L->Act = NULL;
		}
	}
}

int DLActive (tDLList *L) {
/*
** Je-li seznam L aktivní, vrací nenulovou hodnotu, jinak vrací 0.
** Funkci je vhodné implementovat jedním příkazem return.
**/

	return (L->Act != NULL);
}

/* Konec c206.c*/
