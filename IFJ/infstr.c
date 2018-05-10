// infstr.c
// IFJ_projekt_2017
// Jozef Sabo (xsaboj00),

#include <malloc.h>
#include "infstr.h"
#include "error.h"

//inicializacia noveho retzca, pociatocne alokovanie pamate
bool ISInit(infString *s)
{
	if (!s)
	{
		intern_comp_error();
		return false;
	}

	if ((s->string = malloc(ALLOC_SIZE_INC)) == NULL) //pozn. vysdledek neni nutne v jazyce c na rozdil od c++ pretypovavat, jelikoz malloc vraci (void *). 
	{
		intern_comp_error();
		return false;
	}

   s->allocSize = ALLOC_SIZE_INC;
   s->string[0] = '\0';
   s->sLength = 0;
   return true;
}

//uvolnenie retazca z pamate
void ISFree(infString *s)
{
	if (!s)
	{
		intern_comp_error();
		return;
	}
	if (!s->string)
	{
		intern_comp_error();
		return;
	}

	free(s->string);
}

//vymazanie obsahu retezca, bez zmeny velkosti alokovanej pamate
void ISClear(infString *s)
{
	if (!s)
	{
		intern_comp_error();
		return;
	}
   s->string[0] = '\0';
   s->sLength = 0;
}

//pridanie znaku na koniec retazca, ak pamat nestaci je potrebna realokacia
bool ISAddChar(infString *s1, char c)
{
   if (s1->sLength + 1 == s1->allocSize)
   {
	   char *tmp = s1->string;
      if ((s1->string = (char*) realloc(s1->string, s1->sLength + 1 + ALLOC_SIZE_INC)) == NULL)
      {
	      free(tmp);
	      intern_comp_error();
	      return false;
      }
      s1->allocSize += ALLOC_SIZE_INC;
   }
   s1->string[s1->sLength] = c;
   s1->sLength++;
   s1->string[s1->sLength] = '\0';
   return true;
}




