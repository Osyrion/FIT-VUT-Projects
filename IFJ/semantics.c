// semantics.c
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02),

#include <stdlib.h>
#include <string.h>

#include "symtable.h"
#include "semantics.h"
#include "token.h"
#include "error.h"
#include "parser.h"
#include "tacode.h"

symtable *g_symtable;
symtable *l_symtable;

bool semantics_init() {
	g_symtable = symtable_ctor(GLOBAL_SYMTABLE);
	if (!g_symtable) return false;

	l_symtable = symtable_ctor(LOCAL_SYMTABLE);
	if (!l_symtable) return false;

	return true;
}

void semantics_free() {
	symtable_dtor(g_symtable);
	symtable_dtor(l_symtable);
}

int reverse_int(int num) {
	int reversed = 0;
	for (int i = num; i != 0; i = i/10) {
		reversed = reversed * 10 + i % 10;
	}
	return reversed;
}

void append_str_int(int num, infString *s) {
	for (int i = reverse_int(num); i != 0; i = i / 10) {
		ISAddChar(s, i % 10 + '0');
	}
}

char *generate_tmp_id() {
	static int num = 1;
	infString s;
	ISInit(&s);
	ISAddChar(&s, '%');
	ISAddChar(&s, 't');
	ISAddChar(&s, 'm');
	ISAddChar(&s, 'p');
	append_str_int(num, &s);
	num++;
	return s.string;
}

char *generate_label_id() {
	static int num = 1;
	infString s;
	ISInit(&s);
	ISAddChar(&s, '%');
	ISAddChar(&s, 'l');
	ISAddChar(&s, 'a');
	ISAddChar(&s, 'b');
	ISAddChar(&s, 'e');
	ISAddChar(&s, 'l');
	append_str_int(num, &s);
	num++;
	return s.string;
}

char *generate_arg_id(int num) {
	infString s;
	ISInit(&s);
	ISAddChar(&s, '%');
	ISAddChar(&s, 'a');
	ISAddChar(&s, 'r');
	ISAddChar(&s, 'g');
	append_str_int(num, &s);
	return s.string;
}

data_type get_prefix(symtable_item *item) {
	if (item->is_const)
		return item->type;
	else
		return LF;	
}

char *get_key(symtable_item *item) {
	return item->key;
}

symtable_item *local_add(token *t) {
	char *key;
	bool is_const = false;
	data_type type = NOT_TYPE;

	if (!t) {
		key = generate_tmp_id();
	}
	else {
		switch (t->type) {
			case t_id:
				type = NOT_TYPE;
				is_const = false;
				break;
			case t_stringval:
				type = STRING;
				is_const = true;
				break;
			case t_integerval:
				type = INTEGER;
				is_const = true;
				break;
			case t_doubleval:
				type = DOUBLE;
				is_const = true;
				break;
			default:
				intern_comp_error();
				return NULL;
				break;
		}
		key = t->value;
		t->value = NULL;

	}

	if (symtable_search(g_symtable, key)) return NULL;
	
	symtable_item *item = symtable_insert(l_symtable, key);
	item->type = type;
	item->is_const = is_const;
	return item;	
}

symtable_item *local_search(token *t) {
	if (!t) return false;	
	return symtable_search(l_symtable, t->value);
}

void local_clear() {
	symtable_clear(l_symtable);
}

symtable_item *global_add(token *t) {
	if (!t) return false;

	char *key = t->value;
	t->value = NULL;

	return symtable_insert(g_symtable, key);
}

symtable_item *global_search(token *t) {
	if (!t) return false;
	return symtable_search(g_symtable, t->value);
}

void global_add_paramtype(symtable_item *func, data_type type) {
	ISAddChar(&(func->params), type);
}

data_type global_get_paramtype(symtable_item *func, int num) {
	if (num > func->params.sLength)
		return NOT_TYPE;
	else
		return func->params.string[num];
}

int global_get_paramcnt(symtable_item *func) {
	return func->params.sLength;
}

void generate_builtin_functions() {
	char *funcname = malloc(strlen("length")+1);
	strcpy(funcname,"length");

	generate_length();
        symtable_item *func = symtable_insert(g_symtable, funcname);
	global_add_paramtype(func, STRING);
	func->type = INTEGER;
	func->declared = true;
	func->defined = true;

	funcname = malloc(strlen("substr")+1);
	strcpy(funcname,"substr");

	generate_substr();
        func = symtable_insert(g_symtable, funcname);
	global_add_paramtype(func, STRING);
	global_add_paramtype(func, INTEGER);
	global_add_paramtype(func, INTEGER);
	func->type = STRING;
	func->declared = true;
	func->defined = true;
	
	funcname = malloc(strlen("asc")+1);
	strcpy(funcname,"asc");

	generate_asc();
        func = symtable_insert(g_symtable, funcname);
	global_add_paramtype(func, STRING);
	global_add_paramtype(func, INTEGER);
	func->type = INTEGER;
	func->declared = true;
	func->defined = true;

	funcname = malloc(strlen("chr")+1);
	strcpy(funcname,"chr");
	
	generate_chr();
        func = symtable_insert(g_symtable, funcname);
	global_add_paramtype(func, INTEGER);
	func->type = STRING;
	func->declared = true;
	func->defined = true;
}
