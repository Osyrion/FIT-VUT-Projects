// error.h
// IFJ_projekt_2017
// Jozef Sabo (xsaboj00),

//hlavickovy subor pre vypis chybovych, varovacich a ladiacich hlaseni

#ifndef ERROR_H
#define ERROR_H

#include "token.h"

//globalne premmenne, hodnoty su definovane v error.c
extern int error_value;

void lexical_error(int line, char *string);
void syntax_error(token *unexpected, token_type expected);
void undefined_error();
void redefinition_error();
void type_error();
void other_error();
void intern_comp_error();
void log_msg(const char *msg, ...);

#endif
