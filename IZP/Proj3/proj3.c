/**
 * Projekt: Jednoducha shlukova analyza
 *
 * Meno: Robert Durovic
 * Login: xdurov01
 * Nazov: proj3.c
 * Datum: 12/2016
 *
 * Popis:
 * http://is.muni.cz/th/172767/fi_b/5739129/web/web/clsrov.html
 *
 */


#include <stdio.h>
#include <stdlib.h>
#include <assert.h>
#include <math.h>     // sqrtf, powf
#include <limits.h>   // INT_MAX
#include <string.h>   
#include <errno.h>    // chybove hlasenia

#define XMAX 1000     // Limit pre suradnicu X
#define YMAX 1000     // Limit pre suradnicu Y

/*****************************************************************
 * Ladici makra. Vypnout jejich efekt lze definici makra
 * NDEBUG, napr.:
 *   a) pri prekladu argumentem prekladaci -DNDEBUG
 *   b) v souboru (na radek pred #include <assert.h>
 *      #define NDEBUG
 */


#ifdef NDEBUG
#define debug(s)
#define dfmt(s, ...)
#define dint(i)
#define dfloat(f)
#else

// vypise ladici retezec
#define debug(s) printf("- %s\n", s)

// vypise formatovany ladici vystup - pouziti podobne jako printf
#define dfmt(s, ...) printf(" - "__FILE__":%u: "s"\n",__LINE__,__VA_ARGS__)

// vypise ladici informaci o promenne - pouziti dint(identifikator_promenne)
#define dint(i) printf(" - " __FILE__ ":%u: " #i " = %d\n", __LINE__, i)

// vypise ladici informaci o promenne typu float - pouziti
// dfloat(identifikator_promenne)
#define dfloat(f) printf(" - " __FILE__ ":%u: " #f " = %g\n", __LINE__, f)

#endif

/*****************************************************************
 * Deklarace potrebnych datovych typu:
 *
 * TYTO DEKLARACE NEMENTE
 *
 *   struct obj_t - struktura objektu: identifikator a souradnice
 *   struct cluster_t - shluk objektu:
 *      pocet objektu ve shluku,
 *      kapacita shluku (pocet objektu, pro ktere je rezervovano
 *          misto v poli),
 *      ukazatel na pole shluku.
 */

struct obj_t {
    int id;
    float x;
    float y;
};

struct cluster_t {
    int size;
    int capacity;
    struct obj_t *obj;
};

/*
 Inicializace shluku 'c'. Alokuje pamet pro cap objektu (kapacitu).
 Ukazatel NULL u pole objektu znamena kapacitu 0.
*/
void init_cluster(struct cluster_t *c, int cap)
{
    assert(c != NULL);
    assert(cap >= 0);
		
		// alokacia pamate pre 'cap' (kapacitu) clusteru
		c->obj = malloc(cap * sizeof(struct obj_t));

		// Ak NULL, potom cap (kapacita) je 0
		if (!c->obj) {
			cap = 0;
		}
		else {
			// Ak je vsetko v poriadku, inicializuje sa zhluk 'c'
			c->capacity = cap;
			c->size = 0;			
		}
}

/*
 Odstraneni vsech objektu shluku a inicializace na prazdny shluk.
 */
void clear_cluster(struct cluster_t *c)
{
		// uvolnenie pamate
		free(c->obj);		

		// inicializacia na prazdny zhluk
		c->size = 0;
		c->capacity = 0;
		c->obj = NULL;
}

/// Chunk of cluster objects. Value recommended for reallocation.
const int CLUSTER_CHUNK = 10;

/*
 Zmena kapacity shluku 'c' na kapacitu 'new_cap'.
 */
struct cluster_t *resize_cluster(struct cluster_t *c, int new_cap)
{
    // TUTO FUNKCI NEMENTE
    assert(c);
    assert(c->capacity >= 0);
    assert(new_cap >= 0);

    if (c->capacity >= new_cap)
        return c;

    size_t size = sizeof(struct obj_t) * new_cap;

    void *arr = realloc(c->obj, size);
    if (arr == NULL)
        return NULL;

    c->obj = arr;
    c->capacity = new_cap;
    return c;
}

/*
 Prida objekt 'obj' na konec shluku 'c'. Rozsiri shluk, pokud se do nej objekt
 nevejde.
 */
void append_cluster(struct cluster_t *c, struct obj_t obj)
{
		int capacity = c->capacity;		
		int size = c->size;

		// Ak je velkost vacsia ako kapacita, zvacsi kapacitu
    while (size >= capacity) {
			capacity += CLUSTER_CHUNK;			
		}

		// zvacsi o vypocitanu kapacitu
		resize_cluster(c, capacity);

		// prida objekt na koniec pola zhlukov
		c->obj[c->size++] = obj;
}

/*
 Seradi objekty ve shluku 'c' vzestupne podle jejich identifikacniho cisla.
 */
void sort_cluster(struct cluster_t *c);

/*
 Do shluku 'c1' prida objekty 'c2'. Shluk 'c1' bude v pripade nutnosti rozsiren.
 Objekty ve shluku 'c1' budou serazny vzestupne podle identifikacniho cisla.
 Shluk 'c2' bude nezmenen.
 */
void merge_clusters(struct cluster_t *c1, struct cluster_t *c2)
{
    assert(c1 != NULL);
    assert(c2 != NULL);

		int size = 0;

		while (size < c2->size) {
			// prida objekty 'c2', v pripade potreby kapacitu zvacsuje vo funkcii 'append_cluster'
			append_cluster(c1, c2->obj[size++]);
		}
}

/**********************************************************************/
/* Prace s polem shluku */

/*
 Odstrani shluk z pole shluku 'carr'. Pole shluku obsahuje 'narr' polozek
 (shluku). Shluk pro odstraneni se nachazi na indexu 'idx'. Funkce vraci novy
 pocet shluku v poli.
*/
int remove_cluster(struct cluster_t *carr, int narr, int idx)
{
    assert(idx < narr);
    assert(narr > 0);

		// cyklus pre odstranenie zhlukov z pola
		while (idx < narr) {
			// vymazanie clusteru z pola zhlukov
			clear_cluster(&carr[idx]);
			// zjednotenie clusterov do jedneho
			merge_clusters(&carr[idx], &carr[idx + 1]);
			
			idx++;
		}

		// vracia novy pocet poloziek v poli 
		return narr - 1;

}
/*
 Pocita Euklidovskou vzdalenost mezi dvema objekty.
 */
float obj_distance(struct obj_t *o1, struct obj_t *o2)
{
    assert(o1 != NULL);
    assert(o2 != NULL);

    // Euklidovska vzdialenost def. vztahom:
		// E_DIST(A,B) = sqrt( (a1 - b1)^2 + (a2 - b2)^2 )
		
		float a, b, sum, e_dist;
  
		// vypocet a umocnenie na druhu, kde 'x' a 'y' su suradnice objektu
		a = powf((o1->x - o2->x), 2);
		b = powf((o1->y - o2->y), 2);

		sum = a + b;

		// odmocnenie suctu
		e_dist = sqrtf(sum);

		// vracia vyslednu vzdialenost
		return e_dist;
}

/*
 Pocita vzdalenost dvou shluku.
*/
float cluster_distance(struct cluster_t *c1, struct cluster_t *c2)
{
    assert(c1 != NULL);
    assert(c1->size > 0);
    assert(c2 != NULL);
    assert(c2->size > 0);

    // pre skrateny zapis 
 		struct obj_t *obj1 = c1->obj;  
		struct obj_t *obj2 = c2->obj;
		int M = c1->size;
		int N = c2->size;
		
		// defaultne nastavena vzdialenost
		float e_dist_default = 0;

		// vypocet euklidovskej vzdialenosti
		float euclid_dist;

		// prechadza vsetky neprazdne clustery
		for (int l = 0; l < (M*N); l++) {			

			// pocita vzdialenost dvoch objektov v zhlukoch
			euclid_dist = obj_distance(&obj1[l/N], &obj2[l%N]);

			// podmienka pre najdenie najvzdialenejsich objektov
			if (e_dist_default < euclid_dist) {

				// ak je vzdialenost vacsia, prepise sa premenna
				e_dist_default = euclid_dist;
			}
			else {
				continue;
			}
		}

		// funkcia vrati najvaciu vzdialenost medzi objektami
		return e_dist_default;
}

/*
 Funkce najde dva nejblizsi shluky. V poli shluku 'carr' o velikosti 'narr'
 hleda dva nejblizsi shluky. Nalezene shluky identifikuje jejich indexy v poli
 'carr'. Funkce nalezene shluky (indexy do pole 'carr') uklada do pameti na
 adresu 'c1' resp. 'c2'.
*/
void find_neighbours(struct cluster_t *carr, int narr, int *c1, int *c2)
{
    assert(narr > 0);

    float def_distance = cluster_distance(&carr[0], &carr[1]);

    for (int a = 0; a < narr; a++) {
      for (int b = a + 1; b < narr; b++) {
				// hladanie dvoch najpodobnejsich zhlukov
        float distance = cluster_distance(&carr[a], &carr[b]);
        if (def_distance >= distance) {
					// vlozi hodnotu do def_distance
          def_distance = distance;
					// indexy
          *c1 = a;
          *c2 = b;
        }
				else { 
					continue; 
				}
      }
    }
}


// pomocna funkce pro razeni shluku
static int obj_sort_compar(const void *a, const void *b)
{
    // TUTO FUNKCI NEMENTE
    const struct obj_t *o1 = a;
    const struct obj_t *o2 = b;
    if (o1->id < o2->id) return -1;
    if (o1->id > o2->id) return 1;
    return 0;
}

/*
 Razeni objektu ve shluku vzestupne podle jejich identifikatoru.
*/
void sort_cluster(struct cluster_t *c)
{
    // TUTO FUNKCI NEMENTE
    qsort(c->obj, c->size, sizeof(struct obj_t), &obj_sort_compar);
}

/*
 Tisk shluku 'c' na stdout.
*/
void print_cluster(struct cluster_t *c)
{
    // TUTO FUNKCI NEMENTE
    for (int i = 0; i < c->size; i++)
    {
        if (i) putchar(' ');
        printf("%d[%g,%g]", c->obj[i].id, c->obj[i].x, c->obj[i].y);
    }
    putchar('\n');
}

/*
 Ze souboru 'filename' nacte objekty. Pro kazdy objekt vytvori shluk a ulozi
 jej do pole shluku. Alokuje prostor pro pole vsech shluku a ukazatel na prvni
 polozku pole (ukalazatel na prvni shluk v alokovanem poli) ulozi do pameti,
 kam se odkazuje parametr 'arr'. Funkce vraci pocet nactenych objektu (shluku).
 V pripade nejake chyby uklada do pameti, kam se odkazuje 'arr', hodnotu NULL.
*/
int load_clusters(char *filename, struct cluster_t **arr)
{
	  assert(arr != NULL);

		FILE *fr;
			
		// pocet objektov v subore
		int count;

		// hodnoty pre objekt object_c:

			// identifikĂĄtor 'id' objektu
			int id; 

			// suradnice objektu 'x' a 'y' pre vypocet vzdialenosti
			float x, y; 

		// pocitadlo pre sken riadkov v subore
		int counter = 0;

		// otvori subor na citanie
    fr = fopen(filename, "r");
		
			// Ak subor sa subor nepodarilo otvorit
			if (!fr) {
				fprintf(stderr, "Chyba! Subor sa nepodarilo otvorit!\n");

				*arr = NULL;
				return 0;
			}  
			// Ak sa podari nacitat hodnotu z prveho riadka
			else if (fscanf(fr, "count=%d", &count) == 1) {

				// Ak je hodnota kladne cislo, nacita hodnoty zo suboru
				if (count > 0) {

					// Ak je vsetko v poriadku, alokuje pamat pre 'arr'
					*arr = malloc(count * sizeof(struct cluster_t));
					
					// Ak sa nepodarilo naalokovat pamat, vypise chybu
					if (!*arr) {
						fprintf(stderr, "Chyba! Pamat nebola naalokovana!\n");
						return 0;
					}
					else {
						// cyklus pre nacitanie vsetkych hodnot
						while (counter < count) {
							// Ak nenacita vsetky tri hodnoty z riadku, vypise chybu
							if ((fscanf(fr, "%d %g %g", &id, &x, &y)) == 3) {
								// overovanie hodnoty suradnice 'x', musi byt v intervale 0 <= X <= 1000
								if (x >= 0 || x <= XMAX) {
									// overovanie hodnoty suradnice 'y', musi byt v intervale 0 <= Y <= 1000
									if (y >= 0 || y <= YMAX) {
										// inicializacia zhluku
										init_cluster(&(*arr)[counter], 5);

										// vytvorenie objektu
										struct obj_t object_c = {id, x, y};

										// priradenie objektu do zhluku
										append_cluster(&(*arr)[counter], object_c);

										counter++;
									}
									else {
										fprintf(stderr, "Chyba! Suradnica 'y' musi byt v limite 0 <= Y <= 1000!\n");

										*arr = NULL;
										return 0;
									}
								}
								else {
									fprintf(stderr, "Chyba! Suradnica 'x' musi byt v limite 0<= X <= 1000!\n");
						
									*arr = NULL;
									return 0;
								}
							}
							else {
								fprintf(stderr, "Chyba pri nacitani parametrov!\n");

								*arr = NULL;
								return 0;
							}
						}
					}
				}
				else {
					fprintf(stderr, "Chyba! Hodnota 'count' nie je kladne cislo!\n");

					*arr = NULL;
					return 0;
				}
			}
			else {
				fprintf(stderr, "Neocakavana chyba!\n");

				*arr = NULL;
				return 0;
			}
			
		// uzavretie suboru
		fclose(fr);

		// vracia pocet objektov
		return count;
}

/*
 Tisk pole shluku. Parametr 'carr' je ukazatel na prvni polozku (shluk).
 Tiskne se prvnich 'narr' shluku.
*/
void print_clusters(struct cluster_t *carr, int narr)
{
    printf("Clusters:\n");
    for (int i = 0; i < narr; i++)
    {
        printf("cluster %d: ", i);
        print_cluster(&carr[i]);
    }
}

// funkcia pre prevod a overenie validity ciselneho argumentu
int isNum(char* num) {
    int numb;
		char *pntr;

		// prevod na cislo
		numb = strtoul(num, &pntr, 10);

			// Ak nie je cislo
			if (strlen(pntr) != 0) {
				fprintf(stderr, "Chyba! Argument 'N' nie je cislo!\n");
				return 0;
			}
			// ak je hodnota mensia ako 0
			else if (numb < 0) {
				fprintf(stderr, "Chyba! Bolo zadane zaporne cislo!\n");
				return 0;
			}
			// Ak je hodnota rovna nule
			else if (numb == 0) {
				fprintf(stderr, "Chyba! Ciselny argument je nula!\n");
				return 0;
			}
		
		// vracia cislo
		return numb;
}

// funkcia pre vypocet zhlukovania 
// @param1: ciselny argument, @param2: 'filename' 
void calc_clusters(int num_clusters, char* argv) {
		struct cluster_t *clusters;

		// identifikatory 'id' objektov
		int c1, c2;
		int counter = 0;

		// pocet objektov
		int count = load_clusters(argv, &clusters);

		if (count >= num_clusters) {
			while (count > num_clusters) {
				// najde najblizsi zhluk
				find_neighbours(clusters, count, &c1, &c2);

				// zjednoti zhluky
				merge_clusters(&clusters[c1], &clusters[c2]);
				
				// odstrani nadbytocny cluster
				remove_cluster(clusters, count, c2);
		
				count--;
			}

			print_clusters(clusters, count);
		
			while (counter < num_clusters) {
				// uvolni pamat a odstrani vsetky objekty zhluku
				clear_cluster(&clusters[counter]);
				counter++;
			}

			// uvolni pamat
			free(clusters);
		}
		else {
			fprintf(stderr, "Chyba! Zadane cislo je vacsie nez %d (pocet objektov v subore)!\n", count);
		}
}

// funkcia main
int main(int argc, char *argv[])
{
		// ciselny parameter, vstupny udaj		
		int num_clusters;

		if (argc > 1) {
			if (argc == 2) {
				// ak je bez argumentu 'N', defaultne 1
				num_clusters = 1;
	
				// vypocet zhlukovania s parametrom '1'
				calc_clusters(num_clusters, argv[1]);
					
				return 0;
			}
			else if (argc == 3) {
				// Napoveda
				if (strcmp(argv[2],"-h") == 0) {
					fprintf(stdout, "**** Napoveda ****\n\n");
					fprintf(stdout, "Pre spravne fungovanie programu zadajte vstup vo formate:\n"
													"./proj3 'filename' 'N', kde:\n\n*filename - nazov suboru\n"
													"*N - cele cislo, ktore je najmenej 1, maximum urcuje pocet objektov v subore.\n");
					return 0;
				}
				// overovanie ciselneho parametru
				else if (isNum(argv[2])) {
					// parameter 'N' - isNum
					num_clusters = isNum(argv[2]);
					
					//vypocet zhlukovania s parametrom 'isNum'
					calc_clusters(num_clusters, argv[1]);
		
					return 0;
				}
				else {
					// chybove hlasenie je vo funkcii isNum
					return 0;
				}
			}
			else {
				fprintf(stderr, "Chyba! Prilis vela argumentov!\n");
				return 0;
			}
		}
		else {
			fprintf(stderr, "Chyba! Nebol zadany ziadny argument!\n");
			return 0;
		}

	return 0;
}
