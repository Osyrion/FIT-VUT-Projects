// token.h
// IFJ_Projekt_2017
// Drahomir Dlabaja (xdlaba02),

#ifndef TOKEN_H
#define TOKEN_H

#include <stdbool.h>

extern const char *tokens[];

typedef enum {
	t_id,

	keywords_start,
	t_as,
        t_declare,
        t_dim,
        t_do,
        t_double,
        t_else,
        t_end,
        t_function,
        t_if,
        t_input,
        t_integer,
        t_loop,
        t_print,
        t_return,
        t_scope,
        t_string,
        t_then,
        t_while,
	keywords_end,

	t_lparenthesis,
	t_rparenthesis,
	t_comma,
	t_semicolon,

	operators_start,
	t_plus,
        t_minus,
        t_mul,
        t_divreal,
        t_divint,
        t_eq,
        t_noteq,
        t_less,
        t_lesseq,
        t_greater,
        t_greatereq,
	operators_end,

	t_integerval,
	t_doubleval,
	t_stringval,

	t_eol,

	token_counter,
	not_token

} token_type;

typedef struct {
	token_type type;
	int line;
	char *value;
} token;

void token_print(token *t);
token *token_ctor(int line, token_type type, char *value);
void token_dtor(token *t);

#endif
