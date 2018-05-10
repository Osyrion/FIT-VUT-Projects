// tacode.h
// IFJ_projekt_2017
// Jozef Sabo (xsaboj00),

//hlavickovy subor pre vypis triadresneho kodu

#ifndef TACODE_H
#define TACODE_H

#include "symtable.h"

extern const char *instructions[];
extern char *prefixes[];
extern char *implicit_values[];

typedef enum {
   	//.IFJcode17
   	FIRST_ROW_PRINT,

   	//praca s ramcami, volanie funkcii

	MOVE,
	CREATEFRAME,
	PUSHFRAME,
	POPFRAME,
	DEFVAR,
	CALL,
	RETURN,

	//praca s datovym zasobnikom

	PUSHS,
	POPS,
	CLEARS,

    //aritmeticke, relacne, booleeovske a konverzne instrukce

	ADD,
	SUB,
	MUL,
	DIV,
	ADDS,
	SUBS,
	MULS,
	DIVS,
	LT,
	GT,
	EQ,
    	LTS,
	GTS,
	EQS,
	AND,
	OR,
	NOT,
	ANDS,
	ORS,
	NOTS,
    	INT2FLOAT,
	FLOAT2INT,
	FLOAT2R2EINT,
	FLOAT2R2OINT,
	INT2CHAR,
	STRI2INT,
	INT2FLOATS,
	FLOAT2INTS,
	FLOAT2R2EINTS,
	FLOAT2R2OINTS,
	INT2CHARS,
	STRI2INTS,

	//vstupno-vystupne instrukcie

	READ,
	WRITE,

	//praca s retazcami

    	CONCAT,
	STRLEN,
 	GETCHAR,
	SETCHAR,

	//praca s typmi

    	TYPE,

    //instrukcie pre riadenie toku programu

	LABEL,
    	JUMP,
	JUMPIFEQ,
 	JUMPIFNEQ,
	JUMPIFEQS,
    	JUMPIFNEQS,

    //ladiace instrukcie

	BREAK,
	DPRINT

} instruction_type;

void generate(instruction_type instruction, data_type prefix1, char *data1, data_type prefix2, char *data2, data_type prefix3, char *data3);

void generate_length();
void generate_substr();
void generate_asc();
void generate_chr();

#endif
