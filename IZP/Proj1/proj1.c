/**
*
* -- Prvý projekt IZP -- 
*
* -- Autor: Róbert Ďurovič
* -- Číslo: 196194
* -- Login: xdurov01
*
* -- Datum: 10/2016
* -- Názov: proj1.c
* -- Popis: Program formátuje binárne dáta do textovej podoby alebo textovú podobu dát do binárnej podoby.   
*
* -- Posledná úprava: 7.11.2016 1:44:00
*/

// Hlavičkové súbory
#include <stdio.h>					 // práca so vstupom/výstupom
#include <stdlib.h>					 // všeobecné funkcie jazyka C 
#include <stdbool.h>				 // boolovské konštanty
#include <ctype.h>           // testovanie znakov

// Konštanty
#define RIADOK 48            // max počet všetkých znakov na riadok
#define SPOLE 17             // max počet znakov v poli
#define SRIADOK 16           // max počet znakov na riadok
#define HEX 3                // počet znakov hex čísla v poli
#define BUFF 2               // jeden hex znak v poli
#define NMAX 200             // maximálna hodnota argumentu N
#define HEXMAX 31            // maximálna ascii hodnota znaku
#define POLRIADOK 24         // medzera uprostred vypisu



//  ------ FUNKCIE ------    //


// @param1 reťazec1
// @param2 reťazec2
// @return 0 == equal, not equal == -1/1
// funkcia pre porovnávanie textových reťazcov
int stringCmp (const char *a1, const char *a2) {
    const unsigned char *ptr1 = (const unsigned char *)a1;
    const unsigned char *ptr2 = (const unsigned char *)a2;

    while (*ptr1 != '\0') {
        if (*ptr2 == '\0') return  1;
        if (*ptr2 > *ptr1) return -1;
        if (*ptr1 > *ptr2) return  1;

        ptr1++;
        ptr2++;
    }

    if (*ptr2 != '\0') return -1;

    return 0;
}

// @param znak
// @return true/false
// funkcia pre overenie validity číselných parametrov
bool isNum(char num[]) {
    int l = 0;

    // kontrola záporných čísel
    if (num[0] == '-') {
        l = 1;
		}
    for (; num[l] != 0; l++)
    {
        // ak nie je číslo
        if (!isdigit(num[l])) {
            return false;
				}
    }
    return true;
}


// @param znak
// @return == číselná hodnota znaku
// funkcia pre prevod zo stringu na čísla
int toNum(char num[]) {
		int a = 0;
		int d;

		for (int i = 0; ;i++ ) {
			if (num[i] == 0) {
				break;
			}
			d = num[i] - '0';
			a *= 10;
			a += d;
		}
		return a;
}


// funkcia main
int main(int argc, char *argv[]) {


    // Premenné
    int c;                   // znak
    char pole[RIADOK];       // pole riadku
		char buff[BUFF];         // pole jedneho hex znaku
		char spole[NMAX];        // pole argumentu S
    char str_pole[SPOLE];    // pole riadku "string"
    unsigned int j = 0;      // počet riadkov
    bool stop = false;			 // zastavuje cyklus
		int counter = 0;				 // počítadlo        



	if (argc > 0) {

		// bez vstupných argumentov
		if (argc == 1) {

			// cyklus pre výpis riadkov
		  for (int i = 0; ;i++ ) {

				if (i < SRIADOK) {									
					if ((c=getchar()) != EOF) {			              		
						
			
						// jeden hex znak == 3 miesta v pamäti == medzera + hex číslo, napr. _4F
		   				sprintf(pole + i * HEX, " %.02x", c);
						
																	 
							if (c >= 0 && c <= HEXMAX) {
								 c = '.';
							}
							str_pole[i] = c;		
		  		} 
					else {
						if (i == 0) {
						 	break;
						}

						// naplní zvyšok riadku medzerami
						sprintf(pole + i * HEX, "   ");
						str_pole[i] = ' ';
			     	stop = true;
	      	}
		   }	 
		   else {
			   str_pole[SRIADOK] = 0;
			   *(pole + SRIADOK * HEX) = 0;
				
				
			    			
			 	// vytiskne adresu prvého bajtu v riadku
				printf("%.08x ", j * SRIADOK);

				// cyklus tiskne znaky v hex podobe
				for (int b = 0; b < RIADOK; b++) {
					if (b == POLRIADOK) {
						printf("  ");
					}
					else {
					printf("%c", pole[b]);
					
					}
				}
				printf(" ");

				// vytiskne reťazec
				printf(" |%s|\n", str_pole);
				
				j++;    // inkrementuje adresu
 	  	  i = -1; // EOF

				 // ukončí cyklus
				 if (stop) {
					 break;
				 }
			 }
			
		  }
			return 0;
		
		}
		
		// vnorené štruktúry s jedným vstupným argumentom		
		else if (argc == 2) {
			
			// prevod z hex do string
			if (stringCmp(argv[1], "-r") == 0) {

				while ((c = getchar()) != EOF) {
				
					// podmienka - ak je znak tisknutelny
					if (isprint(c)) {
						// podmienka - ak je hex číslo naplní pole
				    if (isxdigit(c)) {
				        buff[counter++] = c;
							
								// reset poĺa
				        if (counter == 2){
				            counter = 0;

										// prevod do hex
				            c = strtol(buff, NULL, 16);								
				            putchar(c);
				        }
							
				     }
						 else if (c == ' ') {
							 continue;
						 }
						 else {
							 printf("\nChyba! Zadaný znak nie je hexadecimálne číslo!");
							 break;
						 }
				 }
				 else {
					continue;
				 }
			}

				// printf("\n"); odriadkovanie pre -r ?
				return 0;
		}

			// hex print -> prevod stringu do hex
			else if (stringCmp(argv[1], "-x") == 0) {
			
				for (int k = 0; ; k++) {
					if (k < SRIADOK) {
						if ((c = getchar()) != EOF) {

							// vyhradené 2 miesta pre každý hex
							sprintf(pole + k * 2, "%.02x", c);				
							
						} 
						else {
							stop = true;
						}
					}
					else {
						printf("%s\n", pole);
						k = -1;

						// ukončí cyklus
						if (stop) {
							break;
						}
					}
				}

				return 0;
			}
			
			// Nápoveda
			else if (stringCmp(argv[1], "-h") == 0) {
				printf("--Nápoveda--\n\n");
				printf("Vstup bez argumentov\n");
				printf("Vypíše adresu prvého bajtu v riadku a 16 znakov v hexadecimálnej podobe, ktoré sú oddelené medzerou.\n\n");

				printf("Vstup s argumentom -r\n");
				printf("Prevedie znaky z hexadecimálnej podoby do stringu. Do vstupu zadávajte hexadecimálne hodnoty.\n\n");

				printf("Vstup s argumentom -x\n");
				printf("Prevedie znaky zo stringu do hexadecimálnej sústavy.\n\n");

				printf("Vstup s argumentami [-s M] a [-n N]\n");
				printf("Prepínač -s definuje, na ktorej adrese sa má výpis začínať. Argument M je nezáporné číslo." 
				" Prepínač -n definuje aximálnu dĺžku vstupných bajtov k spracovaniu. Argument N je kladné číslo.\n\n");
				
				printf("Vstup s argumentami [-S N]\n");
				printf("Program bude tisknúť také postupnosti, ktoré vyzerajú ako reťazec a ich dĺžka je väčšia alebo rovná N." 
				" Argument N je kladné číslo v intervale 0 < N < 200.\n\n");

				printf("Vstup s argumentom -h\n");
				printf("Vyvolá nápovedu.\n");
				return 0;
			}
		}

		
				
		// štruktúra s dvomi argumentami
		else if (argc == 3) {
			if (stringCmp(argv[1], "-S") == 0) {
				if (isNum(argv[2])) {
					if ((toNum(argv[2]) > 0) && (NMAX > toNum(argv[2]))) {
			
						int a = 0;
						while ((c = getchar()) != EOF) {
							
							
							counter++;

									if (!isprint(c)) {
											
											a = 0;

										if (counter > toNum(argv[2])) putchar('\n');
											counter = 0;
											continue;
										}
									spole[a] = c;
									if (counter == toNum(argv[2] )) {
										for(int i = 0;i < counter;i++)
										{
											putchar(spole[i]);										
										}
									
										}

									else if (counter > toNum(argv[2])) {
												putchar(c);

									}
									a++;

						}							

					}
					else {
						fprintf(stderr, "Chyba! Parameter musí byť v intervale 0 < N < 200.\n");
					}
				}
				else {
					fprintf(stderr, "Chyba! Vstupný parameter nie je číslo!\n");
				}
			}
			return 0;
		}
		
		
		// štruktúra so štyrmi argumentami
		else if (argc == 5) {

			// string
			if (stringCmp(argv[1], "-s") == 0) {
				if (isNum(argv[2])) {
					if (toNum(argv[2]) > 0) {
						if (stringCmp(argv[3], "-n") == 0) {
							if (isNum(argv[4])) {
								if (toNum(argv[4]) > 0) {

									while ((c=getchar()) != EOF) {
										counter++;
								
										if (toNum(argv[2]) == counter) {
											break;
										}
									}
									counter = 0;
									for (int i = 0; ;i++ ) {

						  			if (i < SRIADOK) {									
						  				if ((c=getchar()) != EOF && counter < toNum(argv[4])) {			              		
							           
													// jeden hex znak == 3 miesta v pamäti == medzera + hex číslo, napr. _4F
		              				sprintf(pole + i * HEX, " %.02x", c);
												
												counter++;
												 
													if (c >= 0 && c <= HEXMAX) {
														 c = '.';
													}
													str_pole[i] = c;		
						      		} 
											else {
												if (i == 0) {
												 	break;
												}

											// naplní zvyšok riadku medzerami
											sprintf(pole + i * HEX, "   ");
											str_pole[i] = ' ';
						         	stop = true;
						      		}
						 					
						  			}	 
										else {
												str_pole[SRIADOK] = 0;
											 *(pole + SRIADOK * HEX) = 0;
				
											
											
											int z = toNum(argv[2]);
											
										 	// vytiskne adresu prvého bajtu znaku v riadku
											printf("%.08x ", z);

											// cyklus pre tisk znakov v hex podobe
											for (int b = 0; b < RIADOK; b++) {
												if (b == POLRIADOK) {
													printf("  ");
												}
												else {
												printf("%c", pole[b]);
					
												}
											}
											printf(" ");

											//vytiskne reťazec
											printf(" |%s|\n", str_pole);

											j++;    // inkrementuje adresu
							 	  	  i = -1; // EOF

											// ukončí cyklus
									    if (stop) {
								          break;
									    }
								    }
			
									}
									return 0;
								}
								else {
								fprintf(stderr, "Chyba! Parameter N nie je kladné číslo!\n");
								}
							}
							else {
							fprintf(stderr, "Chyba! Parameter N nie je číslo!\n");
							}
						}
					}
					else {
						fprintf(stderr, "Chyba! Parameter M nie je kladné číslo!\n");	 
					}
				}
				else {
					fprintf(stderr, "Chyba! Parameter M nie je číslo!\n");
				}
			}
			return 0;
		}

		// Chybové hlásenie
		else {
			if (argc > 5) {
				fprintf(stderr, "Chyba! Príliš veľa argumentov!\n");
			}
			else {
				fprintf(stderr, "Vyskytla sa chyba!\n\n");
				printf("Pre vyvolanie nápovedy zadajte ./proj1 -h.\n");
			}
			return 0;
		}

	}
	return 0;
}