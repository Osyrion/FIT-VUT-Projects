// parser.h
// IFJ_Projekt_2017
// Drahomir Dlabaja (xdlaba02),

#ifndef PARSER_H
#define PARSER_H

#include <stdbool.h>
#include "token.h"
#include "semantics.h"

#define refill_token(t) token_dtor(t); t = get_token()
#define token_type_eq(t, ttype) do {if (t->type != ttype) {syntax_error(t,ttype); return false;}} while(0)

extern token *t;
extern symtable_item *tmpvar;
extern symtable_item *tmpvar2;

typedef enum {
	PLUS,
	MINUS,
	MULTIPLE,
	DIVREAL,
	DIVINT,
	LPARENTHESIS,
	RPARENTHESIS,
	ID,
	CONST,
	DOLLAR,
	NOT_SYMBOL,
	E,
} symbols;

typedef struct symbol_item {
	symbols s;
	data_type type;
	symtable_item *item;
	struct symbol_item *next;
} symbol_item;

typedef struct {
	symbol_item *top;
} symbol_stack;

int symbol_check(token *t);
bool is_terminal(symbol_item *item);
symbol_stack *symbol_stack_ctor();
void symbol_stack_dtor(symbol_stack *stack);
symbol_item *symbol_stack_top(symbol_stack *stack);
symbol_item *symbol_stack_top_terminal(symbol_stack *stack);
void symbol_stack_pop(symbol_stack *stack);
bool symbol_stack_push(symbol_stack *stack, symbols sym, ...);

data_type expr_operation(symbols op, data_type left, data_type right);
data_type stack_convert_operands(data_type left, data_type right);
bool stack_convert_type(data_type from, data_type to);

void parse();
bool rule_prog();	// <prog> 	-> <vdecl> <prog> 	| <fdecl> <prog> 	| <fdef> <prog>	| scope EOL <st-list> scope EOL
bool rule_scope();
bool rule_scopelist();
bool rule_funclist();
bool rule_iflist(symtable_item **label);
bool rule_elselist();
bool rule_looplist();
bool rule_parameters(symtable_item *func);
bool rule_parlist(symtable_item *func, int param_idx);
bool rule_fdecl();
bool rule_fdef();
bool rule_rel();
bool rule_dim();
bool rule_input();
bool rule_print();
bool rule_if();
bool rule_else(symtable_item **label);
bool rule_do();
bool rule_return();
bool rule_assign();
bool rule_term(data_type *type);
bool rule_type(data_type *type);
bool rule_expr(data_type *expr_type);	// <expr>	-> <term> | ( //dummy pro vypocet mnoziny first
bool expr_inside(symbol_stack *stack);
bool rule_call(data_type *return_type);
bool rule_callargs(symtable_item *func, int argcnt);

#endif
