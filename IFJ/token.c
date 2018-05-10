// token.c
// IFJ_Projekt_2017
// Drahomir Dlabaja (xdlaba02),

#include <stdlib.h>
#include <stdio.h>

#include "token.h"
#include "error.h"

const char *tokens[] = {
	"id",

        "keywords_start",
	"as",		"declare",	"dim",		
	"do",		"double",	"else",		
	"end",		"function",	"if",
	"input",	"integer",	"loop",		
	"print",	"return",	"scope",	
	"string",	"then",		"while",
	"keywords_end",

	"(", ")", ",", ";",

	"operators_start",
	"+",    "-",    "*",    "/",
        "\\",   "=",    "<>",   "<",
        "<=",   ">",    "<=",
	"operators_end",

	"integer value", "double value", "string value",

	"EOL",

	"token_counter",
	"not_token"
};

void token_print(token *t)
{
	fprintf(stderr, "<%s", tokens[t->type]);
	switch(t->type)
	{
		case t_id:
		case t_stringval:
		case t_integerval:
		case t_doubleval:
			fprintf(stderr, ", %s", t->value);
			break;
		default:
			break;
	}
	fprintf(stderr, ">");
}

token *token_ctor(int line, token_type type, char *value) {
	token *t = malloc(sizeof(token));
	if (!t) {
		intern_comp_error();
		return NULL;
	}

	t->type = type;
	t->line = line;
	t->value = value;

	return t;
}

void token_dtor(token *t) {
	if (!t) return;
	if (t->value) free(t->value);
	free(t);
}
