// error.c
// IFJ_projekt_2017
// Jozef Sabo (xsaboj00),


#include <stdio.h>
#include <stdlib.h>
#include <stdarg.h>
#include "token.h"
#include "error.h"

int error_value = 0;

// chyba v programu v rámci lexikální analýzy (chybná struktura aktuálního lexému)
void lexical_error(int line, char *string)
{
    fprintf(stderr, "*!* Chyba programu v ramci lexikalnej analyzy sa nachadza na riadku cislo: '%d'. Lexem, ktory sposobil chybu: '%s'. *!*\n", line, string);
    error_value = 1;
}

//chyba v programu v rámci syntaktické analýzy (chybná syntaxe programu)
void syntax_error(token *unexpected, token_type expected)
{
    fprintf(stderr, "*!* Chyba programu v ramci syntaktickej analyzy sa nachadza na riadku cislo: '%d'. Token, ktory sposobil chybu: ", unexpected->line);
    token_print(unexpected);

    if (expected != not_token)
    {
	token t;
	t.type = expected;
	t.value = NULL;
    	
	fprintf(stderr, " Ocakavany token bol: ");
	token_print(&t);
    }
    fprintf(stderr," *!*\n");
    error_value = 2;
}


//sémantická chyba v programu – nedefinovaná funkce/promenná, pokus o redefinici funkce/promenné
void undefined_error()
{
    fprintf(stderr, "*!* Nedefinovany identifikator. *!*\n");
    error_value=3;
}

void redefinition_error()
{
    fprintf(stderr, "*!* Pokus o redefinici identifikatoru. *!*\n");
    error_value=3;
}

//sémantická chyba typové kompatibility v aritmetických, retezcových a relacních výrazech, príp. špatný pocetci typ parametru u volání funkce
void type_error()
{
    fprintf(stderr, "*!* Semanticka chyba typovej kompatibility. *!*\n");
    error_value=4;
}

//ostatní sémantické chyby
void other_error()
{
    fprintf(stderr, "*!* Ostatna semanticka chyba. *!*\n");
    error_value=6;
}

//interní chyba prekladace tj. neovlivnená vstupním programem (napr. chyba alokace pameti)
void intern_comp_error()
{
    fprintf(stderr, "*!* Interna chyba prekladace. *!*\n");
    error_value=99;
}

//laiace vypisy smerujuce na stderr
void log_msg(const char *msg, ...)
{
	va_list args;
    va_start(args, msg);
    fprintf(stderr, "LADIACI_VYPIS: ");
    vfprintf(stderr, msg, args);
    va_end(args);
}

