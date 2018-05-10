// infstr.h
// IFJ_projekt_2017
// Jozef Sabo (xsaboj00),

//hlavickovy subor pre pracu s nekonecne dlhymi retazcami(infinity strings)

#ifndef INFSTR_H
#define INFSTR_H

#include <stdbool.h>

//pamat pre retazec sa bude alokovat/realokovat v nasobkoch tohto cisla
#define ALLOC_SIZE_INC 16 //aby se realokace neprovadela moc casto

//struktura pre nacitany retazec
typedef struct
{
  char *string;            //konkretne znaky retazca, ukoncene znakom '\0'
  int sLength;             //dlzka retazca, bez posledneho znaku '\0'
  int allocSize;           //velkost alokovanej pamate pre retazec
} infString;

bool ISInit(infString *s);
void ISFree(infString *s);
void ISClear(infString *s);
bool ISAddChar(infString *s, char c);

#endif
