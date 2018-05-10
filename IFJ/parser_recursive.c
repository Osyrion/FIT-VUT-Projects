// parser_recursive.c
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02),

#include <stdio.h>
#include <stdbool.h>
#include <stdarg.h>
#include <stdlib.h>
#include <string.h>

#include "parser.h"
#include "scanner.h"
#include "error.h"
#include "semantics.h"
#include "tacode.h"

//TODO smazat nasledujici radky, aby si to neprecetl kontrolujuci a nepridal to do testu
//FIXME typove konverze
//FIXME vestavene fce
//TODO uklizeni stacku
//TODO kontrolovat konstanty v tabulce symbolu, aby se nepletly s identifikatory


token *t = NULL;
symtable_item *tmpvar; // protoze WRITE neumi pracovat se zasobnikem -_- (teda neprisel jsem na to jak by mohl (zatim))
symtable_item *tmpvar2; // protoze CONCAT neumi pracovat se zasobnikem a potrebuje dva operandy -_-

void parse() {
	semantics_init();

	generate(FIRST_ROW_PRINT, NOP, NULL, NOP, NULL, NOP, NULL);
	generate(JUMP, NOP, "$$main", NOP, NULL, NOP, NULL);
	
	generate_builtin_functions();	
	
	rule_prog();
	token_dtor(t);
	semantics_free();
}

bool rule_prog() {
	refill_token(t);
	switch (t->type) {
		case t_declare:
			if (!rule_fdecl()) return false;
			if (!rule_prog()) return false;
			break;
		case t_function:
			if (!rule_fdef()) return false;
			if (!rule_prog()) return false;
			break;
		case t_scope:
			if (!rule_scope()) return false;
			break;
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}
	return true;
} // end rule_prog

bool rule_scope() {
	tmpvar = local_add(NULL);
	tmpvar2 = local_add(NULL);
	
	generate(LABEL, NOP, "$$main", NOP, NULL, NOP, NULL);
	generate(CREATEFRAME, NOP, NULL, NOP, NULL, NOP, NULL);
	generate(PUSHFRAME, NOP, NULL, NOP, NULL, NOP, NULL);
	generate(DEFVAR, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
	generate(DEFVAR, get_prefix(tmpvar2), get_key(tmpvar2), NOP, NULL, NOP, NULL);
	
	refill_token(t);
	token_type_eq(t, t_eol);

	// <st-list>
	if (!rule_scopelist()) return false;

	// scope
	refill_token(t);
	token_type_eq(t, t_scope);

	// EOL
	refill_token(t);
	token_type_eq(t, t_eol);

	generate(POPFRAME, NOP, NULL, NOP, NULL, NOP, NULL);

	return true;
}

bool rule_scopelist() {
	refill_token(t);
	switch (t->type) {
		case t_input:
			if (!rule_input()) return false;
			break;
		case t_print:
			if (!rule_print()) return false;
			break;
		case t_if:
			if (!rule_if()) return false;
			break;
		case t_do:
			if (!rule_do()) return false;
			break;
		case t_dim:
			if (!rule_dim()) return false;
			break;
		case t_id:
			put_token(&t);
			if (!rule_assign()) return false;
			break;
		case t_end:
			return true;
			break;
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}
	
	if (!rule_scopelist()) return false;
	return true;

} // end rule_scopelist

bool rule_funclist() { 
	refill_token(t);
	switch (t->type) {
		case t_input:
			if (!rule_input()) return false;
			break;
		case t_print:
			if (!rule_print()) return false;
			break;
		case t_if:
			if (!rule_if()) return false;
			break;
		case t_do:
			if (!rule_do()) return false;
			break;
		case t_dim:
			if (!rule_dim()) return false;
			break;
		case t_id:
			put_token(&t);
			if (!rule_assign()) return false;
			break;
		case t_return:
			if (!rule_return()) return false;
			break;
		case t_end:
			return true;
			break;
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}
	
	if (!rule_funclist()) return false;
	return true;

} // end rule_funclist

bool rule_iflist(symtable_item **label) {
	refill_token(t);
	switch (t->type) {
		case t_input:
			if (!rule_input()) return false;
			break;
		case t_print:
			if (!rule_print()) return false;
			break;
		case t_if:
			if (!rule_if()) return false;
			break;
		case t_do:
			if (!rule_do()) return false;
			break;
		case t_id:
			put_token(&t);
			if (!rule_assign()) return false;
			break;
		case t_else:
			if (!rule_else(label)) return false;
			return true;
			break;
		case t_end:
			return true;
			break;
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}

	if (!rule_iflist(label)) return false;
	return true;

} // end rule_iflist


bool rule_elselist() {
	refill_token(t);
	switch (t->type) {
		case t_input:
			if (!rule_input()) return false;
			break;
		case t_print:
			if (!rule_print()) return false;
			break;
		case t_if:
			if (!rule_if()) return false;
			break;
		case t_do:
			if (!rule_do()) return false;
			break;
		case t_id:
			put_token(&t);
			if (!rule_assign()) return false;
			break;
		case t_end:
			return true;
			break;
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}

	if (!rule_elselist()) return false;
	return true;
} // end rule_elselist

bool rule_looplist() {
	refill_token(t);
	switch (t->type) {
		case t_input:
			if (!rule_input()) return false;
			break;
		case t_print:
			if (!rule_print()) return false;
			break;
		case t_if:
			if (!rule_if()) return false;
			break;
		case t_do:
			if (!rule_do()) return false;
			break;
		case t_id:
			put_token(&t);
			if (!rule_assign()) return false;
			break;
		case t_loop:
			return true;
			break;
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}

	if (!rule_looplist()) return false;
	return true;
} // end rule_looplist

bool rule_parameters(symtable_item *func) {
	symtable_item *param;
	int param_idx = 0;
	refill_token(t);
	switch (t->type) {
		case t_id:
			if (func->defined)
				param = local_add(t);
			

			refill_token(t);
			token_type_eq(t, t_as);

			data_type type;
			if (!rule_type(&type)) return false;
			
			if (!func->declared) {
				global_add_paramtype(func, type);
			}
			else if (global_get_paramtype(func, param_idx) != type) {
				// semanticka chyba, spatny typ nulteho parametru
				type_error();
				return false;
			}

			param_idx++;

		       	if (!rule_parlist(func, param_idx)) return false;
			
			if (func->defined) {
				param->type = type;
				generate(DEFVAR, get_prefix(param), get_key(param), NOP, NULL, NOP, NULL);
				generate(POPS, get_prefix(param), get_key(param), NOP, NULL, NOP, NULL);
			}
			
			break;
		
		case t_rparenthesis:
			if (global_get_paramcnt(func) != param_idx) {
				// semanticka chyba, malo parametru;
				other_error();
				return false;
			}
			break;
		
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}

	return true;
} // end rule_parameters

bool rule_parlist(symtable_item *func, int param_idx) {
	symtable_item *param;
	refill_token(t);
	switch (t->type) {
		case t_comma:
			refill_token(t);
			token_type_eq(t, t_id);
			
			if (func->defined)
				param = local_add(t);

			refill_token(t);
			token_type_eq(t, t_as);

			data_type type;
			if (!rule_type(&type)) return false;
			
			if (!func->declared) {
				global_add_paramtype(func, type);
			}
			else if (global_get_paramtype(func, param_idx) != type) {
				// semanticka chyba, spatny typ parametru
				type_error();
				return false;
			}

			param_idx++;

			if (!rule_parlist(func, param_idx)) return false;
			
			if (func->defined) {
				param->type = type;
				generate(DEFVAR, get_prefix(param), get_key(param), NOP, NULL, NOP, NULL);
				generate(POPS, get_prefix(param), get_key(param), NOP, NULL, NOP, NULL);
			
			}
			break;

		case t_rparenthesis:
			if (global_get_paramcnt(func) != param_idx) {
				// semanticka chyba, spatny pocet parametru
				other_error();
				return false;
			}
			break;

		default:
			syntax_error(t, not_token);
			return false;
			break;
	}
	return true;
} // end rule_parlist

bool rule_fdecl() {
	refill_token(t);
	token_type_eq(t, t_function);

	refill_token(t);
	token_type_eq(t, t_id);

	if (global_search(t)) {
		// semantcka chyba, funkce je jiz deklarovana
		redefinition_error();
		return false;
	}
	symtable_item *item = global_add(t);

	refill_token(t);
	token_type_eq(t, t_lparenthesis);

	if (!rule_parameters(item)) return false;

	refill_token(t);
	token_type_eq(t, t_as);

	data_type type;
	if (!rule_type(&type)) return false;
	item->type = type;

	refill_token(t);
	token_type_eq(t, t_eol);

	item->declared = true;

	return true;
} //end rule_fdecl

bool rule_fdef() {
	refill_token(t);
	token_type_eq(t, t_id);

	symtable_item *func = global_add(t);
	if (func->defined) {
		// semanticka chyba, funkce jiz definovana
		redefinition_error();
		return false;
	}

	func->defined = true;
	
	tmpvar = local_add(NULL);
	tmpvar2 = local_add(NULL);
	
	generate(LABEL, NOP, func->key, NOP, NULL, NOP, NULL);
	generate(CREATEFRAME, NOP, NULL, NOP, NULL, NOP, NULL);
	generate(PUSHFRAME, NOP, NULL, NOP, NULL, NOP, NULL);
	generate(DEFVAR, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
	generate(DEFVAR, get_prefix(tmpvar2), get_key(tmpvar2), NOP, NULL, NOP, NULL);

	refill_token(t);
	token_type_eq(t, t_lparenthesis);

	if (!rule_parameters(func)) return false;

	refill_token(t);
	token_type_eq(t, t_as);

	data_type type;
	if (!rule_type(&type)) return false;
	
	if (!func->declared) {
		func->type = type;
	}
	else if (func->type != type) {
		// semanticka chyba, spatna navratovy typ
		type_error();
		return false;
	}

	refill_token(t);
	token_type_eq(t, t_eol);

	if (!rule_funclist()) return false;

	refill_token(t);
	token_type_eq(t, t_function);

	refill_token(t);
	token_type_eq(t, t_eol);

	func->declared = true;

	generate(PUSHS, func->type, implicit_values[func->type], NOP, NULL, NOP, NULL);
	generate(RETURN, NOP, NULL, NOP, NULL, NOP, NULL);

	local_clear();

	return true;
} // end rule_fdef

bool rule_rel() {
	token_type operator;
	refill_token(t);
	// <rel> -> ( <rel> )
	if (t->type == t_lparenthesis) { // zajsituje a kontroluje moznost libovolneno mnozstvi zavorek u relace
		if (!rule_rel()) return false;

		refill_token(t);
		token_type_eq(t, t_rparenthesis);
	}
	else {
		data_type ltype, rtype;

		put_token(&t);
		// <rel> -> <term> <reloperator> <term>
		if (!rule_term(&ltype)) return false;

		refill_token(t);
		operator = t->type;

		if (!rule_term(&rtype)) return false;

		if (!stack_convert_operands(ltype, rtype)) return false;

		switch (operator) {
			case t_eq:
				generate(EQS, NOP, NULL, NOP, NULL, NOP, NULL);
				break;
			case t_noteq:
				generate(EQS, NOP, NULL, NOP, NULL, NOP, NULL);
				generate(NOTS, NOP, NULL, NOP, NULL, NOP, NULL);
				break;
			case t_less:
				generate(LTS, NOP, NULL, NOP, NULL, NOP, NULL);
				break;
			case t_lesseq:
				generate(GTS, NOP, NULL, NOP, NULL, NOP, NULL);
				generate(NOTS, NOP, NULL, NOP, NULL, NOP, NULL);
				break;
			case t_greater:
				generate(GTS, NOP, NULL, NOP, NULL, NOP, NULL);
				break;
			case t_greatereq:
				generate(LTS, NOP, NULL, NOP, NULL, NOP, NULL);
				generate(NOTS, NOP, NULL, NOP, NULL, NOP, NULL);
				break;
			default:
				syntax_error(t, not_token);
				return false;
				break;
		}
	}

	return true;

} // end rule_rel

bool rule_dim() {
	refill_token(t);
	token_type_eq(t, t_id);

	if (local_search(t)) {
		// semanticka chyba, promenna jiz deklarovana
		redefinition_error();
		return false;
	}
	
	symtable_item *item = local_add(t);
	
	refill_token(t);
	token_type_eq(t, t_as);

	data_type type;
	if (!rule_type(&type)) return false;
	item->type = type;
	
	generate(DEFVAR, get_prefix(item), get_key(item), NOP, NULL, NOP, NULL);
	
	refill_token(t);
	if (t->type == t_eq) {
		data_type expr_type;
		if (!rule_expr(&expr_type)) return false;
		refill_token(t);

		if (!stack_convert_type(expr_type, item->type)) return false;
		
		generate(POPS, get_prefix(item), get_key(item), NOP, NULL, NOP, NULL);
	}
	else {
		generate(MOVE, get_prefix(item), get_key(item), item->type, implicit_values[item->type], NOP, NULL);
	}

	token_type_eq(t, t_eol);
	return true;
} // end rule_dim

bool rule_input() {
	refill_token(t);
	token_type_eq(t, t_id);

	symtable_item *item = local_search(t);
	if (!item) {
		// semanticka chyba, neni deklarovano
		undefined_error();
		return false;
	}
	
	refill_token(t);
	token_type_eq(t, t_eol);

	generate(WRITE, STRING, "? ", NOP, NULL, NOP, NULL);
	generate(READ, get_prefix(item), get_key(item), NOP, prefixes[item->type], NOP, NULL);

	return true;
} // end rule_input

bool rule_print() {
	refill_token(t);
	switch(t->type) {
		case t_eol:
			return true;
			break;
		default:
			put_token(&t);
			if (!rule_expr(NULL)) return false;

			generate(POPS, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);

			generate(WRITE, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
		
			refill_token(t);
			token_type_eq(t, t_semicolon);
	
			if (!rule_print()) return false;
	}
	return true;
} // end rule_print

bool rule_if() {

	symtable_item *label = local_add(NULL);

	if (!rule_rel()) return false;

	generate(PUSHS, BOOL, "true", NOP, NULL, NOP, NULL);
	generate(JUMPIFNEQS, NOP, get_key(label), NOP, NULL, NOP, NULL);

	refill_token(t);
	token_type_eq(t, t_then);

	refill_token(t);
	token_type_eq(t, t_eol);

	if (!rule_iflist(&label)) return false;

	refill_token(t);
	token_type_eq(t, t_if);

	refill_token(t);
	token_type_eq(t, t_eol);
	
	generate(LABEL, NOP, get_key(label), NOP, NULL, NOP, NULL);

	return true;
} // end rule_if

bool rule_else(symtable_item **label) {
	refill_token(t);
	token_type_eq(t, t_eol);

	symtable_item *tmplab = *label;
	*label = local_add(NULL);
	
	generate(JUMP, NOP, get_key(*label), NOP, NULL, NOP, NULL);
	generate(LABEL, NOP, get_key(tmplab), NOP, NULL, NOP, NULL);

	if (!rule_elselist()) return false;

	return true;
}

bool rule_do() {
	
	symtable_item *label_end = local_add(NULL);
	symtable_item *label_loop = local_add(NULL);
	
	refill_token(t);
	token_type_eq(t, t_while);

	generate(LABEL, NOP, get_key(label_loop), NOP, NULL, NOP, NULL);

	if (!rule_rel()) return false;

	generate(PUSHS, BOOL, "true", NOP, NULL, NOP, NULL);	
	generate(JUMPIFNEQS, NOP, get_key(label_end), NOP, NULL, NOP, NULL);
	
	refill_token(t);	
	token_type_eq(t, t_eol);

	if (!rule_looplist()) return false;

	refill_token(t);
	token_type_eq(t, t_eol);
	
	generate(JUMP, NOP, get_key(label_loop), NOP, NULL, NOP, NULL);
	generate(LABEL, NOP, get_key(label_end), NOP, NULL, NOP, NULL);
	
	return true;
} // end rule_do

bool rule_return() {
	if (!rule_expr(NULL)) return false;

	generate(RETURN, NOP, NULL, NOP, NULL, NOP, NULL);
		
	refill_token(t);	
	token_type_eq(t, t_eol);
	return true;
} // end rule_return

bool rule_assign() {
	refill_token(t);
	token_type_eq(t, t_id);

	symtable_item *item = local_search(t);
	if (!item) {
		// semanticka chyba, promenna neni deklarovana
		undefined_error();
		return false;
	}

	refill_token(t);
	token_type_eq(t, t_eq);

	data_type expr_type;

	if (!rule_expr(&expr_type)) return false;

	if (!stack_convert_type(expr_type, item->type)) return false;

	generate(POPS, get_prefix(item), get_key(item), NOP, NULL, NOP, NULL);

	refill_token(t);	
	token_type_eq(t, t_eol);

	return true;
} // end rule_assign

bool rule_term(data_type *type) {
	symtable_item *item;
	refill_token(t);
	switch (t->type) {
		case t_id:
			item = local_search(t);
			if (!item) {
				// semanticka chyba, nedeklarovany identifikator;
				undefined_error();
				return false;
			}
			break;
		case t_integerval:
		case t_doubleval:
		case t_stringval:
			item = local_add(t);
			break;
		default:
			syntax_error(t, not_token);
			return false;
			break;
	}

	*type = item->type;
	generate(PUSHS, get_prefix(item), get_key(item), NOP, NULL, NOP, NULL);
	return true;
}

bool rule_type(data_type *type) {
	refill_token(t);
	switch (t->type) {
		case t_integer:
			*type = INTEGER;
			break;
		case t_double:
			*type = DOUBLE;
			break;
		case t_string:
			*type = STRING;
			break;
		default:
			*type = NOT_TYPE;
			syntax_error(t, not_token);
			return false;
	}

	return true;
}

bool stack_convert_type(data_type from, data_type to) {
	if (from == INTEGER && to == DOUBLE) {
		generate(INT2FLOATS, NOP, NULL, NOP, NULL, NOP, NULL);
	}
	else if (from == DOUBLE && to == INTEGER) {
		generate(FLOAT2R2EINTS, NOP, NULL, NOP, NULL, NOP, NULL);
	}
	else if (from != to) {
		type_error();
		return false;
	}

	return true;
}

data_type stack_convert_operands(data_type left, data_type right) {
	if (right == INTEGER && left == DOUBLE) {
		generate(INT2FLOATS, NOP, NULL, NOP, NULL, NOP, NULL);
		return DOUBLE;
        }
        else if (right == DOUBLE && left == INTEGER) {
		generate(POPS, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
		generate(INT2FLOATS, NOP, NULL, NOP, NULL, NOP, NULL);
		generate(PUSHS, get_prefix(tmpvar), get_key(tmpvar), NOP, NULL, NOP, NULL);
		return DOUBLE;
	}
        else if (right != left) {
		type_error();
		return false;
	}

	return left;
}
