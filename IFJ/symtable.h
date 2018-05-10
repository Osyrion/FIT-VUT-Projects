// symtable.h
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02),

#ifndef SYMTABLE_H
#define SYMTABLE_H

#include <stddef.h>
#include "infstr.h"

#define SIZE 128

typedef enum {
	NOT_TYPE,
	INTEGER,
	DOUBLE,
	STRING,
	BOOL,
	LF,
	TF,
	GF,
	NOP
} data_type;

typedef enum {
	GLOBAL_SYMTABLE,
	LOCAL_SYMTABLE
} symtable_type;

typedef struct symtable_item {
	char *key;
	struct symtable_item *next;
	data_type type;
	union {
		struct {
			bool is_const;
		};
		struct {
			bool declared;
			bool defined;
			bool used;
			infString params;
		};
	};
} symtable_item;

typedef struct {
	size_t arr_size;
	symtable_type type;
	symtable_item *ptr[];
} symtable;

unsigned int hash_function(const char *str);
symtable *symtable_ctor(symtable_type type);
symtable_item *symtable_search(symtable *s, char *key);
symtable_item *symtable_insert(symtable *s, char *key);
void symtable_clear(symtable *s);
void symtable_dtor(symtable *s);
#endif
