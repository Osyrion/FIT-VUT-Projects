// main.c
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02), Jozef Sabo (xsaboj00),

#include <stdio.h>

#include "parser.h"
#include "error.h"



int main()
{
/*
    generate(FIRST_ROW_PRINT,NOPREFIX,NULL,NOPREFIX,NULL,NOPREFIX,NULL);
    generate(WRITE,STRING,"retazec s lomitkom \\ a\nnovym#radkem",NOPREFIX,NULL,NOPREFIX,NULL);
    //naschval chyba
    generate(ADD,LF,"abc",INTEGER,"15",NOPREFIX,NULL);
    generate(ADD,TF,"abc",INTEGER,"15",GF,"def");
*/
	parse();
	return error_value;
}

