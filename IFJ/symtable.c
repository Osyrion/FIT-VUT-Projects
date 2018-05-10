// symtable.c
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02), 

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <stdbool.h>

#include "error.h"
#include "symtable.h"

unsigned int hash_function (const char *str) {
	unsigned int h = 0;
	while (*str)
		h = 131*h + *str++;
	return h;
}

symtable *symtable_ctor(symtable_type type) {
	symtable *s = malloc(sizeof(symtable) + SIZE * sizeof(symtable_item *));
	if (!s) return NULL;
	
	for(size_t i = 0; i < SIZE; i++)
		s->ptr[i] = NULL;

	s->arr_size = SIZE;	
	s->type = type;
	return s;
}


symtable_item *symtable_search(symtable *s, char *key) {
	if (!s || !key)
		return NULL;

	symtable_item *item = s->ptr[hash_function(key)%(s->arr_size)];
	while (item != NULL && strcmp(item->key, key))
		item = item->next;
	return item;
}

symtable_item *symtable_insert(symtable *s, char *key) {
	if (!s || !key) return NULL;

	symtable_item *item = symtable_search(s, key);
	if (item) {
		free(key);
		return item;
	}

	unsigned int index = hash_function(key)%(s->arr_size);

	item = malloc(sizeof(symtable_item));
	if (!item) return NULL;

	item->key = key;
	item->next = NULL;
	item->type = NOT_TYPE;

	switch (s->type) {
		case LOCAL_SYMTABLE:
			item->is_const = false;
			break;
		case GLOBAL_SYMTABLE:
			item->declared = false;
			item->defined = false;
			item->used = false;
			ISInit(&(item->params));
			break;
	}

	symtable_item *tmp = s->ptr[index];
	if (!tmp) {
		s->ptr[index] = item;	
	}
	else {
		while (tmp->next)
			tmp = tmp->next;
		tmp->next = item;
	}

	return item;
}

void symtable_clear(symtable *s) {
	if (!s) return;

	for (unsigned int i = 0; i < s->arr_size; i++) {
		symtable_item *item = s->ptr[i];
		while(item) {
			symtable_item *tmp = item;
			item = item->next;

			if (tmp->key) free(tmp->key);
			if (s->type == GLOBAL_SYMTABLE) {
				if (tmp->used && !tmp->defined) {
					undefined_error();
				}
				ISFree(&(tmp->params));
			}
			free (tmp);
		}
		s->ptr[i] = NULL;
	}
}

void symtable_dtor(symtable *s) {
	if (s) {
		symtable_clear(s);
		free(s);
	}
}
