// tacode.c
// IFJ_projekt_2017
// Jozef Sabo (xsaboj00),

#include <stdio.h>
#include <string.h>
#include "tacode.h"


const char *instructions[] = {

    ".IFJcode17","MOVE","CREATEFRAME","PUSHFRAME","POPFRAME","DEFVAR","CALL","RETURN","PUSHS","POPS",
	"CLEARS","ADD","SUB","MUL","DIV","ADDS","SUBS","MULS","DIVS","LT","GT","EQ","LTS","GTS","EQS","AND",
	"OR","NOT","ANDS","ORS","NOTS","INT2FLOAT","FLOAT2INT","FLOAT2R2EINT","FLOAT2R2OINT","INT2CHAR",
	"STRI2INT","INT2FLOATS","FLOAT2INTS","FLOAT2R2EINTS","FLOAT2R2OINTS","INT2CHARS","STRI2INTS","READ",
	"WRITE","CONCAT","STRLEN","GETCHAR","SETCHAR","TYPE","LABEL","JUMP","JUMPIFEQ","JUMPIFNEQ","JUMPIFEQS",
    "JUMPIFNEQS","BREAK","DPRINT"
};

char *prefixes[] = {

    "","int","float","string","bool","LF","TF","GF"

};

char *implicit_values[] = {

    "","0","0.0",""

};

void print_string(char *string)
{
    int length = strlen(string);
    for(int i =0; i < length; i++)
    {
        if(((int)string[i] >= 0 && (int)string[i] <= 32) || (int)string[i] == 35 || (int)string[i] == 92)
        {
            printf("\\");
            printf("%03d", (int)string[i]);
        }
        else
            printf("%c",string[i]);
    }
}

void print_instruction(data_type prefix, char *data)
{
    if(prefix == NOP)
        printf("%s", data);
    else if(prefix==STRING)
         {
            printf("%s@",prefixes[prefix]);
            print_string(data);
         }
         else
         {
            printf("%s@%s",prefixes[prefix], data);
         }
}

void generate(instruction_type instruction, data_type prefix1, char *data1, data_type prefix2, char *data2, data_type prefix3, char *data3)
{

    switch(instruction)
    {
        //instrukcie bez parametrov
        case FIRST_ROW_PRINT:
        case ADDS:
        case SUBS:
        case MULS:
        case DIVS:
        case LTS:
        case GTS:
        case EQS:
        case ANDS:
        case ORS:
        case NOTS:
        case INT2FLOATS:
        case FLOAT2INTS:
        case INT2CHARS:
        case FLOAT2R2EINTS:
        case FLOAT2R2OINTS:
        case STRI2INT:
        case STRI2INTS:
	case CREATEFRAME:
	case PUSHFRAME:
	case POPFRAME:
        case RETURN:
	case CLEARS:
	case BREAK:
       	if(prefix1 != NOP || data1 != NULL || prefix2 != NOP || data2 != NULL || prefix3 != NOP || data3 != NULL)
        {
                fprintf(stderr,"*!* Chybne zadane parametre pri vypisovani instrukcie *!*\n");
                break;
        }
        printf("%s\n",instructions[instruction]);
		break;

        //instrukcie s 1 parametrom
        case DEFVAR:
        case CALL:
        case PUSHS:
        case POPS:
        case WRITE:
        case LABEL:
        case JUMP:
        case JUMPIFEQS:
        case JUMPIFNEQS:
        case DPRINT:
            if(data1 == NULL || prefix2 != NOP || data2 != NULL || prefix3 != NOP || data3 != NULL)

            {
                fprintf(stderr,"*!* Chybne zadane parametre pri vypisovani instrukcie *!*\n");
                break;
            }
            printf("%s ",instructions[instruction]);
            print_instruction(prefix1,data1);
            printf("\n");
			break;

        //instrukcie s 2 parametrami
        case MOVE:
        case INT2FLOAT:
        case FLOAT2INT:
        case FLOAT2R2EINT:
        case FLOAT2R2OINT:
        case INT2CHAR:
        case READ:
        case STRLEN:
        case TYPE:
        case NOT:
            if(data1 == NULL || data2 == NULL || prefix3 != NOP || data3 != NULL)

            {
                fprintf(stderr,"*!* Chybne zadane parametre pri vypisovani instrukcie *!*\n");
                break;
            }
            printf("%s ",instructions[instruction]);
            print_instruction(prefix1,data1);
            printf(" ");
            print_instruction(prefix2,data2);
            printf("\n");
			break;

        //instrukcie s 3 parametrami

        case ADD:
        case SUB:
        case MUL:
        case DIV:
        case LT:
        case GT:
        case EQ:
        case AND:
        case OR:
        case CONCAT:
        case GETCHAR:
        case SETCHAR:
        case JUMPIFEQ:
        case JUMPIFNEQ:
            if(data1 == NULL || data2 == NULL || data3 == NULL)

            {
                fprintf(stderr,"*!* Chybne zadane parametre pri vypisovani instrukcie *!*\n");
                break;
            }
            printf("%s ",instructions[instruction]);
            print_instruction(prefix1,data1);
            printf(" ");
            print_instruction(prefix2,data2);
            printf(" ");
            print_instruction(prefix3,data3);
            printf("\n");
			break;

		default:
			break;

    }

}

void generate_length() {
	printf(
		"LABEL length\n"
		"CREATEFRAME\n"
		"PUSHFRAME\n"

		"DEFVAR LF@s\n"
		"POPS LF@s\n"

		"DEFVAR LF@retval\n"
		"DEFVAR LF@pos\n"

		"MOVE LF@pos int@0\n"
		"DEFVAR LF@count\n"
		"MOVE LF@count int@0\n"
		"DEFVAR LF@char\n"

		"JUMPIFEQ VFL3 LF@s string@\n"

		"STRLEN LF@retval LF@s\n"

		"LABEL VFL1\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"JUMPIFNEQ VFL2 LF@char string@\\\n"
		"ADD LF@count LF@count int@1\n"
		"LABEL VFL2\n"
		"ADD LF@pos LF@pos int@1\n"
		"JUMPIFNEQ VFL1 LF@retval LF@pos\n"

		"MUL LF@count LF@count int@3\n"
		"SUB LF@retval LF@retval LF@count\n"

		"JUMP VFL4\n"

		"LABEL VFL3\n"
		"MOVE LF@retval int@0\n"
		
		"LABEL VFL4\n"

		"PUSHS LF@retval\n"
		"RETURN\n"
		"\n"
	);
}

void generate_substr() {
	printf(
		"LABEL substr\n"
		"CREATEFRAME\n"
		"PUSHFRAME\n"
		
		"DEFVAR LF@n\n"
		"POPS LF@n\n"
		"DEFVAR LF@i\n"
		"POPS LF@i\n"
		"DEFVAR LF@s\n"
		"POPS LF@s\n"

		"DEFVAR LF@retval\n"
		"MOVE LF@retval string@\n"

		"DEFVAR LF@check\n"
		"DEFVAR LF@length\n"
		"DEFVAR LF@truelength\n"
		"DEFVAR LF@char\n"
		"DEFVAR LF@pos\n"
		"DEFVAR LF@truepos\n"
		"DEFVAR LF@helppos\n"
		"DEFVAR LF@begin\n"
		"DEFVAR LF@truebegin\n"
		"DEFVAR LF@end\n"
		"DEFVAR LF@trueend\n"
		
		"PUSHS LF@s\n"
		"CALL length\n"
		"POPFRAME\n"
		"POPS LF@truelength\n"

		"STRLEN LF@length LF@s\n"

		"JUMPIFEQ VFL5 LF@s string@\n"
		"LT LF@check LF@i int@1\n"
		"JUMPIFEQ VFL5 LF@check bool@true\n"
		"GT LF@check LF@i LF@truelength\n"
		"JUMPIFEQ VFL5 LF@check bool@true\n"
		"EQ LF@check LF@n int@0\n"
		"JUMPIFEQ VFL5 LF@check bool@true\n"

		"MOVE LF@truebegin LF@i\n"
		"MOVE LF@pos int@0\n"
		"MOVE LF@truepos int@1\n"

		"JUMPIFEQ VFL14 LF@i int@1\n"
		
		"LABEL VFL6\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"JUMPIFNEQ VFL7 LF@char string@\\092\n"
		"ADD LF@pos LF@pos int@3\n"
		"LABEL VFL7\n"
		"ADD LF@pos LF@pos int@1\n"
		"ADD LF@truepos LF@truepos int@1\n"
		"JUMPIFNEQ VFL6 LF@truebegin LF@truepos\n"
		
		"LABEL VFL14\n"
		"MOVE LF@begin LF@pos\n"
		
		"LT LF@check LF@n int@0\n"
		"JUMPIFEQ VFL10 LF@check bool@true\n"
		"ADD LF@trueend LF@truebegin LF@n\n"
		"SUB LF@trueend LF@trueend int@1\n"
		"GT LF@check LF@trueend LF@truelength\n"
		"JUMPIFEQ VFL10 LF@check bool@true\n"
		"JUMPIFEQ VFL16 LF@n int@1\n"
		
		"LABEL VFL9\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"JUMPIFNEQ VFL12 LF@char string@\\092\n"
		"ADD LF@pos LF@pos int@3\n"
		
		"LABEL VFL12\n"
		"ADD LF@pos LF@pos int@1\n"
		"ADD LF@truepos LF@truepos int@1\n"
		"JUMPIFNEQ VFL9 LF@trueend LF@truepos\n"
		
		"LABEL VFL16\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"JUMPIFNEQ VFL13 LF@char string@\\092\n"
		"ADD LF@pos LF@pos int@3\n"
		
		"LABEL VFL13\n"
		"MOVE LF@end LF@pos\n"
		"ADD LF@end LF@end int@1\n"
		"JUMP VFL11\n"
		
		"LABEL VFL10\n"
		"MOVE LF@end LF@length\n"
		
		"LABEL VFL11\n"
		"MOVE LF@pos LF@begin\n"
		
		"LABEL VFL8\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"CONCAT LF@retval LF@retval LF@char\n"
		"ADD LF@pos LF@pos int@1\n"
		"JUMPIFNEQ VFL8 LF@pos LF@end\n"
		
		"LABEL VFL5\n"
		
		"PUSHS LF@retval\n"
		"RETURN\n"
		"\n"
	);
}

void generate_asc()
{
	printf(
		"LABEL asc\n"
		"CREATEFRAME\n"
		"PUSHFRAME\n"
		
		"DEFVAR LF@retval\n"
		"MOVE LF@retval int@0\n"
		
		"DEFVAR LF@i\n"
		"POPS LF@i\n"
		"DEFVAR LF@s\n"
		"POPS LF@s\n"
		
		"DEFVAR LF@truelength\n"
		"DEFVAR LF@check\n"
		"DEFVAR LF@char\n"
		"DEFVAR LF@pos\n"
		"DEFVAR LF@truepos\n"
		"DEFVAR LF@intchar\n"
		
		"PUSHS LF@s\n"
		"CALL length\n"
		"POPFRAME\n"
		"POPS LF@truelength\n"

		"JUMPIFEQ VFL30 LF@s string@\n"
		"LT LF@check LF@i int@1\n"
		"JUMPIFEQ VFL30 LF@check bool@true\n"
		"GT LF@check LF@i LF@truelength\n"
		"JUMPIFEQ VFL30 LF@check bool@true\n"
		
		"MOVE LF@truepos int@1\n"
		"MOVE LF@pos int@0\n"
		
		"LABEL VFL31\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"JUMPIFNEQ VFL32 LF@char string@\\092\n"
		"ADD LF@pos LF@pos int@3\n"
		"LABEL VFL32\n"
		"ADD LF@pos LF@pos int@1\n"
		"ADD LF@truepos LF@truepos int@1\n"
		"JUMPIFNEQ VFL31 LF@truepos LF@i\n"
		
		"GETCHAR LF@char LF@s LF@pos\n"
		"JUMPIFNEQ VFL33 LF@char string@\\092\n"
		"MOVE LF@retval string@\n"
		"ADD LF@pos LF@pos int@1\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"CONCAT LF@retval LF@retval LF@char\n"
		"ADD LF@pos LF@pos int@1\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"CONCAT LF@retval LF@retval LF@char\n"
		"ADD LF@pos LF@pos int@1\n"
		"GETCHAR LF@char LF@s LF@pos\n"
		"CONCAT LF@retval LF@retval LF@char\n"
		"JUMP VFL30\n"
		
		"LABEL VFL33\n"
		"STRI2INT LF@retval LF@s LF@pos\n"
		
		"LABEL VFL30\n"
		
		"PUSHS LF@retval\n"
		"RETURN\n"
		"\n"
	);
}

void generate_chr() {
	printf(
		"LABEL chr\n"
		"CREATEFRAME\n"
		"PUSHFRAME\n"
		
		"DEFVAR LF@retval\n"
		"MOVE LF@retval string@\n"
		
		"DEFVAR LF@i\n"
		"POPS LF@i\n"
		
		"DEFVAR LF@check\n"
		"LT LF@check LF@i int@0\n"
		"JUMPIFEQ VFL20 LF@check bool@true\n"
		"GT LF@check LF@i int@255\n"
		"JUMPIFEQ VFL20 LF@check bool@true\n"
		
		"INT2CHAR LF@retval LF@i\n"
		
		"LABEL VFL20\n"
		
		"PUSHS LF@retval\n"
		"RETURN\n"
		"\n"
	);
}
