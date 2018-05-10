// parser_precedence.c
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02),

#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <stdarg.h>

#include "parser.h"
#include "error.h"
#include "scanner.h"
#include "semantics.h"
#include "tacode.h"

int symbol_check(token *t) {
	switch (t->type) {
		case t_plus:
			return PLUS;
		case t_minus:
			return MINUS;
		case t_mul:
			return MULTIPLE;
		case t_divreal:
			return DIVREAL;
		case t_divint:
			return DIVINT;
		case t_lparenthesis:
			return LPARENTHESIS;
		case t_rparenthesis:
			return RPARENTHESIS;
		case t_id:
			return ID;
		case t_integerval:
		case t_doubleval:
		case t_stringval:
			return CONST;
		default:
			return DOLLAR;
	}
}

symbol_stack *symbol_stack_ctor() {
	symbol_stack *tmp = malloc(sizeof(symbol_stack));
	tmp->top = NULL;
	symbol_stack_push(tmp, DOLLAR, NULL);
	return tmp;
}

void symbol_stack_dtor(symbol_stack *stack) {
	if (!stack) {
		intern_comp_error();
		return;
	}

	while(stack->top)
		symbol_stack_pop(stack);

	free(stack);
}

symbol_item *symbol_stack_top(symbol_stack *stack) {
	if (!stack) {
		intern_comp_error();
		return NULL;
	}
	return stack->top;
}

symbol_item *symbol_stack_top_terminal(symbol_stack *stack) {
	if (!stack) {
		intern_comp_error();
		return NULL;
	}

	symbol_item *item = stack->top;
	while (item->s == E) {
		item = item->next;
	}

	return item;
}	

void symbol_stack_pop(symbol_stack *stack) {
	if (!stack) {
		intern_comp_error();
		return;
	}

	if (stack->top) {
		symbol_item *tmp = stack->top;
		stack->top = tmp->next;
		free(tmp);
	}
}

bool symbol_stack_push(symbol_stack *stack, symbols s, ...) {
	va_list args;
	
	if (!stack) {
		intern_comp_error();
		return false;
	}

	symbol_item *tmp = malloc(sizeof(symbol_item));
	if (!tmp) {
		intern_comp_error();
		return false;
	}

	tmp->next = stack->top;
	stack->top = tmp;

	tmp->s = s;

	va_start(args, s);

	if (s == E) {
		tmp->type = va_arg(args, data_type);
	}
	else {
		tmp->item = va_arg(args, symtable_item *);
	}

	va_end(args);
	return true;
}


bool rule_expr(data_type *expr_type) {
	symbol_stack *stack = symbol_stack_ctor();
	if (!stack) return false;

	bool is_okay = expr_inside(stack);

	if (expr_type) *expr_type = symbol_stack_top(stack)->type;

	symbol_stack_dtor(stack);
	return is_okay;
}

bool expr_inside(symbol_stack *stack) {
	static const char prec_table[11][11] = {
	//	    +   -   *   /   \   (   )   ID CON  $  err
	/*  +  */ {'>','>','<','<','<','<','>','<','<','>',' '},
	/*  -  */ {'>','>','<','<','<','<','>','<','<','>',' '},
	/*  *  */ {'>','>','>','>','>','<','>','<','<','>',' '},
	/*  /  */ {'>','>','>','>','>','<','>','<','<','>',' '},
	/*  \  */ {'>','>','<','<','>','<','>','<','<','>',' '},
	/*  (  */ {'<','<','<','<','<','<','=','<','<',' ',' '},
	/*  )  */ {'>','>','>','>','>',' ','>',' ',' ','>',' '},
	/*  ID */ {'>','>','>','>','>',' ','>',' ',' ','>',' '},
	/* CNST*/ {'>','>','>','>','>',' ','>',' ',' ','>',' '},
	/*  $  */ {'<','<','<','<','<','<',' ','<','<',' ',' '},
	/* err */ {' ',' ',' ',' ',' ',' ',' ',' ',' ',' ',' '},
	};

	symbols symbol;
	symtable_item *item; // pro ruzna vyziti
	data_type expr_type = NOT_TYPE;
	
	refill_token(t);
	do {
		symbol = symbol_check(t);
		switch (prec_table[symbol_stack_top_terminal(stack)->s][symbol]) {
			case '>':
				switch (symbol_stack_top(stack)->s) {
					case E:
						expr_type = symbol_stack_top(stack)->type;

						symbol_stack_pop(stack);
						symbols operator = symbol_stack_top(stack)->s;

						symbol_stack_pop(stack);
						if(symbol_stack_top(stack)->s != E) {
							syntax_error(t, not_token);
							return false;
						}

						expr_type = expr_operation(operator, symbol_stack_top(stack)->type, expr_type);
						if (!expr_type) return false;

						symbol_stack_pop(stack);
						symbol_stack_push(stack, E, expr_type);
						
						break; // end E

					case RPARENTHESIS:
						symbol_stack_pop(stack);
						if (symbol_stack_top(stack)->s != E) {
							syntax_error(t, not_token);
							return false;
						}

						expr_type = symbol_stack_top(stack)->type;
						
						symbol_stack_pop(stack);
						if (symbol_stack_top(stack)->s != LPARENTHESIS) {
							syntax_error(t, not_token);
							return false;
						}

						symbol_stack_pop(stack);
						symbol_stack_push(stack, E, expr_type);
						break; // end case RPARENTHESIS

					case CONST:
					case ID:
						item = symbol_stack_top(stack)->item;
						symbol_stack_pop(stack);
						symbol_stack_push(stack, E, item->type);
						
						generate(PUSHS, get_prefix(item), get_key(item), NOP, NULL, NOP, NULL);	
						
						break;

					default:
						syntax_error(t, not_token);
						return false;
				}
				break; // end '>'
			
			case '=':
			case '<':
				switch (symbol) {
					case CONST:
						item = local_add(t);
						symbol_stack_push(stack, CONST, item);
						break;

					case ID:
						item = local_search(t);
						if (!item) {
							put_token(&t);
							data_type return_type;
							if (!rule_call(&return_type)) return false;
							if (symbol_stack_top(stack)->s != DOLLAR) {
								syntax_error(t, not_token);
								return false;
							}
							symbol_stack_push(stack, E, return_type);
							return true;
						}

						symbol_stack_push(stack, ID, item);
						break;
	
					default:
						symbol_stack_push(stack, symbol, NULL);
						break;

				}
				refill_token(t);
				break; // end '=' '<'

			case ' ':
				syntax_error(t, not_token);	
				return false;
				break; // end ' '
		} // end switch
	} while (symbol != DOLLAR || symbol_stack_top_terminal(stack)->s != DOLLAR);
	
	put_token(&t);
	return true;
}

bool rule_call(data_type *return_type) {
	int argcnt = 0;
	symtable_item *func;
	symtable_item *item;

	refill_token(t);
	token_type_eq(t, t_id);

	func = global_search(t);
	if (!func) {
		// semanticka chyba, nedeklarovana funkce;
		undefined_error();
		return false;
	}

	func->used = true;
	*return_type = func->type;

	refill_token(t);
	token_type_eq(t, t_lparenthesis);

	refill_token(t);
	if (t->type == t_rparenthesis) {
		if (global_get_paramcnt(func) != argcnt) {
			// semanticka chyba, malo argumentu
			type_error();
			return false;
		}
		return true;
	}

	item = (t->type == t_id) ? local_search(t) : local_add(t);

	if (!item) {
		//neznama promenna
		undefined_error();
		return false;
	}
	       	

	generate(PUSHS, get_prefix(item), get_key(item), NOP, NULL, NOP, NULL);
	
	if (!stack_convert_type(item->type, global_get_paramtype(func, argcnt))) return false;

	
	argcnt++;
	refill_token(t);
	while (t->type != t_rparenthesis) {
		token_type_eq(t, t_comma);
		refill_token(t);
		item = (t->type == t_id) ? local_search(t) : local_add(t);
		if (!item) {
			//neznama promenna
			undefined_error();
			return false;
		}
		
		generate(PUSHS, get_prefix(item), get_key(item), NOP, NULL, NOP, NULL);
	       	
		if (!stack_convert_type(item->type, global_get_paramtype(func, argcnt))) return false;
		
		refill_token(t);
		argcnt++;
	}

	if (global_get_paramcnt(func) != argcnt) {
		type_error();
		return false;
	}

	generate(CALL, NOP, func->key, NOP, NULL, NOP, NULL);
	generate(POPFRAME, NOP, NULL, NOP, NULL, NOP, NULL);

	return true;
}

data_type expr_operation(symbols operator, data_type left, data_type right) {
        data_type return_type;
	switch (operator) {
		case MINUS:
                case MULTIPLE:
                case DIVREAL:
                case DIVINT:
			if (left == STRING || right == STRING) {
				type_error();
				return NOT_TYPE;
			}
			break;
		default:
			break;
	}

	switch (operator) {
		case MINUS:
		case MULTIPLE:
		case PLUS:
			return_type = stack_convert_operands(left, right);
			break;

		case DIVREAL:
                       	if (right == INTEGER)
			    	generate(INT2FLOATS, NOP, NULL, NOP, NULL, NOP, NULL);
                      
			if (left == INTEGER) {
		             	generate(POPS, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
				generate(FLOAT2R2EINTS, NOP, NULL, NOP, NULL, NOP, NULL);
				generate(PUSHS, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
			}
		                
			return_type = DOUBLE;
			break;

		case DIVINT:
			if (left != INTEGER || right != INTEGER) {
				type_error();
				return NOT_TYPE;
			}
			return_type = INTEGER;
			break;

		default:
			break;
	}

	switch (operator) {
		case PLUS:
			if (return_type == STRING) {
				generate(POPS, get_prefix(tmpvar2), get_key(tmpvar2), NOP, NULL, NOP, NULL);
				generate(POPS, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
				generate(CONCAT, get_prefix(tmpvar), get_key(tmpvar), get_prefix(tmpvar), get_key(tmpvar), get_prefix(tmpvar2), get_key(tmpvar2));
				generate(PUSHS, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
			}

			else {
				generate(ADDS, NOP, NULL, NOP, NULL, NOP, NULL);
			}

			break;

		case MINUS:
			generate(SUBS, NOP, NULL, NOP, NULL, NOP, NULL);
			break;

		case MULTIPLE:
			generate(MULS, NOP, NULL, NOP, NULL, NOP, NULL);
			break;

		case DIVREAL:
		case DIVINT:
			generate(DIVS, NOP, NULL, NOP, NULL, NOP, NULL);
			break;

		default:
			break;
	}

	return return_type;
}

