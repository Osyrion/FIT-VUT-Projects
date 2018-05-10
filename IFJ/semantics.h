// semantics.h
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02),

#ifndef SEMANTICS_H
#define SEMANTICS_H

#include "symtable.h"
#include "token.h"

bool semantics_init();
void semantics_free();

int reverse_int(int num);
void append_str_int(int num, infString *s);

char *generate_tmp_id();
char *generate_label_id();
char *generate_arg_id(int cnt);

data_type get_prefix(symtable_item *item);
char *get_key(symtable_item *item);

symtable_item *local_add(token *t);
symtable_item *local_search(token *t);
void local_clear();

symtable_item *global_add(token *t);
symtable_item *global_search(token *t);
void global_add_paramtype(symtable_item *func, data_type type);
data_type global_get_paramtype(symtable_item *func, int num);
int global_get_paramcnt(symtable_item *func);

void generate_builtin_functions();

#endif
