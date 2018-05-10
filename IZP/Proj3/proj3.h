/*
 * @file proj3.h
 *
 * @version 1.0
 */

/*! \mainpage Dokumentacia k projektu cislo 3: Jednoducha zhlukova analyza
 *
 * @author Robert Durovic (xdurov01)
 * 
 * @date 12/2016
 * [TOC]
 * \section intro_sec Popis projektu
 * - - -
 * Zhlukova analyza (tiez clusterova analyza, anglicky cluster analysis) je viacrozmerna
 * statisticka metoda, ktora sa pouziva ku klasifikacii objektov. Sluzi k triedeniu jednotiek
 * do skupin (zhlukov) tak, ze jednotky nalezici do rovnakej skupiny su si podobnejsie nez
 * objekty z ostatnych skupin.
 *
 * Zhlukovu analyzu prevadzame na dvojrozmernych objektoch. Kazdy objekt je definovany
 * celym cislom. Objekty su ulozene v textovom subore.
 *
 * \subsection Implement_sec Implementacne detaily
 *
 * Vstupne data su ulozene v textovom subore. Prvy riadok suboru je vyhradeny pre pocet
 * objektov v subore a ma format:
 * ~~~~~~~~~~~~~~~{.c}
 * count=N
 * ~~~~~~~~~~~~~~~
 * kde cislo je pocet objektov v subore. Nasleduje na kazdom riadku definicia jedneho objektu.
 * Pocet riadkov suboru je vyhradeny pre pocet objektov v subore a ma format:
 * ~~~~~~~~~~~~~~~{.c}
 * OBJID X Y
 * ~~~~~~~~~~~~~~~
 * kde OBJID je v ramci suboru jednoznacny celociselny identifikator,
 * X a Y su suradnice objektu, taktiez cele cisla. Plati 0 <= X <= 1000, 0 <= Y <= 1000.
 *
 * \section install_sec Spustenie programu
 *
 * \subsection specs_sec Preklad zdrojoveho suboru
 *  
 * Program bude prekladany s nasledujucimi argumentami:
 * ~~~~~~~~~~~~~~~{.c}
 * $ gcc -std=c99 -Wall -Wextra -Werror -DNDEBUG proj3.c -o proj3 -lm
 * ~~~~~~~~~~~~~~~
 * - Definicia makra NDEBUG (argument -NDEBUG) je z dovpdu anulovania efektu ladiacich
 * informacii
 * - Prepojenie s matematickou kniznicou (argument -lm) je z dovodu vypoctu vzdialenosti objektov.
 *
 * \subsection syntax_specs Syntax spustenia
 *
 * Program sa spusta v nasledujucej podobe:
 * ~~~~~~~~~~~~~~~{.c}
 * ./proj3 SOUBOR [N]
 * ~~~~~~~~~~~~~~~
 * Argumenty programu:
 * - SOUBOR je nazov suboru so vstupnymi datami
 * - N je volitelny argument definujuci cielovy pocet zhlukov N > 0.
 * Pri absencii argumentu je vychodzia hodnota 1.
 */

/** 
 * \defgroup declaration Datove typy
 * @title nieco
 * @{
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

/**
 * @}
 */

/** - Konstanta XMAX udava maximalnu hodnotu pre suradnicu X (plati vztah X <= 1000).
 *  - Konstanta YMAX udava maximalnu hodnotu pre suradnicu Y (plati vztah Y <= 1000).
 *  - Konstanta CLUSTER_CHUNK ma hodnotu 10 a je vhodna pre realokaciu.
 *
 *  \defgroup const Konstanty
 * @{
 */

#define XMAX 1000
#define YMAX 1000

extern const int CLUSTER_CHUNK;

/**
 * @}
 */

/**
 * @brief    funkcie pre inicializaciu, odstranenie a zlucenie zhlukov
 *
 * @defgroup cluster_func
 * @title    inicializacne, mazacie a zlucovacie funkcie
 * @{
 */ 

/**
 * @brief   Inicializacia zhluku
 *
 * \param   *c  objekt
 * \param   cap kapacita objektu
 *
 * \return  (void) vytvori zhluk
 *
 * @note    Inicializace shluku 'c'. Alokuje pamet pro cap objektu (kapacitu).
 *          Ukazatel NULL u pole objektu znamena kapacitu 0.
 * @warning Ak sa pamat pre pole objektov nenaalokuje, kapacita bude 0
 *
 */
class initialisation {
void init_cluster(struct cluster_t *c, int cap);
};

/**
 * @brief   Vycistenie zhluku
 * 
 * @param   @*c   objekt
 * 
 * @return  (void) vytvri zhluk
 * 
 * @note    Odstraneni vsech objektu shluku a inicializace na prazdny shluk.
 * @warning --
 *
 */

class clear_cluster {
void clear_cluster(struct cluster_t *c);
};

/**
 * @brief   zmena velkosti zhluku
 *
 * @param   struct cluster_t *c    objekt
 * @param   int new_cap            kapacita objektu
 *
 * @return  hodnota kapacity zhluku
 *
 * @note    Zmena kapacity shluku 'c' na kapacitu 'new_cap'.
 * @warning --
 * 
 */

class resize_cluster{
struct cluster_t *resize_cluster(struct cluster_t *c, int new_cap);
};

/**
 * @brief   prida objekt do zhluku
 * @param   struct cluster_t *c    objekt
 * @param   struct obj_t obj       kapacita objektu
 *
 * @return  (void) vrati rozsireny zhluk
 *
 * @note    Prida objekt 'obj' na konec shluku 'c'. Rozsiri shluk, pokud se do nej objekt nevejde.
 * @warning --
 * 
 */

class append_cluster {
void append_cluster(struct cluster_t *c, struct obj_t obj);
};

/**
 * @brief    spoji dva zhluky do jedneho
 *
 * @param    struct cluster_t *c1    zhluk 1
 * @param    struct cluster_t *c2    zhluk 2
 *
 * @return   (void) vrati novy zhluk
 *
 * @note     Do shluku 'c1' prida objekty 'c2'. Shluk 'c1' bude v pripade nutnosti rozsiren.
 *           Objekty ve shluku 'c1' budou serazny vzestupne podle identifikacniho cisla.
 *           Shluk 'c2' bude nezmenen.
 * @warning  --
 * 
 */

class merge_clusters {
void merge_clusters(struct cluster_t *c1, struct cluster_t *c2);
};

/**
 * @brief   Odstrani zhluk
 *
 * @param   struct cluster_t *carr    pole zhlukov carr
 * @param   int narr    							pole narr poloziek
 * @param   int idx                   index
 *
 * @return  vracia novy pocet zhlukov v poli
 *
 * @note    Odstrani shluk z pole shluku 'carr'. Pole shluku obsahuje 'narr' polozek
 *          (shluku). Shluk pro odstraneni se nachazi na indexu 'idx'. Funkce vraci novy
 *          pocet shluku v poli.
 * @warning --
 * 
 */

class remove_cluster {
int remove_cluster(struct cluster_t *carr, int narr, int idx);
};

/**
 * @}
 */

/**
 * @brief    Funkcie pre vypocet vzdialenosti zhlukov, ich susedov a zoradenie
 *
 * @defgroup calculate clusters
 * @title    calc_clusters
 * @{
 */

/**
 * @brief   Vypocita vzdialenost dvoch bodov
 *
 * @param   struct obj_t *o1    objekt 1
 * @param   struct obj_t *o2    objekt 2				
 *
 * @return  vracia vyslednu hodnotu - vzdialenost dvoch objektov (float)
 *
 * @note    Vypocita Euklidovsku vzdialenost dvoch bodov na zaklade suradnic podla vztahu 
 *          \f$\sqrt{(x_2-x_1)^2+(y_2-y_1)^2}\f$.
 *
 * @warning --
 * 
 */

class obj_distance {
float obj_distance(struct obj_t *o1, struct obj_t *o2);
};

/**
 * @brief   Pocita vzdialenost dvoch zhlukov
 *
 * @param   struct cluster_t *c1    zhluk 1
 * @param   struct cluster_t *c2		zhluk 2
 *
 * @return  vracia vyslednu hodnotu dvoch zhlukov (float)
 *
 * @note    --
 * @warning --
 * 
 */

class cluster_distance {
float cluster_distance(struct cluster_t *c1, struct cluster_t *c2);
};

/**
 * @brief   Vyhlada dve najpodobnejsie zhluky v poli
 *
 * @param   struct cluster_t *carr    pole zhlukov carr
 * @param   int narr    							pole narr poloziek
 * @param   int *c1                   zhluk 1
 * @param   int *c2                   zhluk 2
 *
 * @return  (void) vrati dve najblizsie zhluky
 *
 * @note    Funkce najde dva nejblizsi shluky. V poli shluku 'carr' o velikosti 'narr'
 *          hleda dva nejblizsi shluky. Nalezene shluky identifikuje jejich indexy v poli
 *          'carr'. Funkce nalezene shluky (indexy do pole 'carr') uklada do pameti na
 *          adresu 'c1' resp. 'c2'.
 * @warning --
 * 
 */

class find_neighbours {
void find_neighbours(struct cluster_t *carr, int narr, int *c1, int *c2);
};

/**
 * @brief   Radenie zhlukov
 *
 * @param   struct cluster_t *c1    zhluk
 *
 * @return  (void) vrati zoradene objekty v zhluku
 *
 * @note    pomocna funkce pro razeni shluku
 * @warning --
 * 
 */

class sort_cluster {
void sort_cluster(struct cluster_t *c);
};

/**
 * @}
 */

/**
 * @brief    Funkcie pre nacitanie zhlukov zo suboru a tisk
 *
 * @defgroup load_print
 * @title    load_print_clusters
 * @{
 */

/**
 * @brief   Tiskne zhluky
 *
 * @param   struct cluster_t *c1    zhluk
 *
 * @return  (void) vytiskne zhluky
 *
 * @note    Tisk shluku 'c' na stdout.
 * @warning --
 *
 */

class print_cluster {
void print_cluster(struct cluster_t *c);
};

/**
 * @brief   Nacita zhluky zo suboru
 *
 * @param   char *filename           nazov suboru
 * @param   struct cluster_t **arr   pole zhlukov
 *
 * @return  vracia (int) pocet nacitanych zhlukov
 *
 * @note    Ze souboru 'filename' nacte objekty. Pro kazdy objekt vytvori shluk a ulozi
 *          jej do pole shluku. Alokuje prostor pro pole vsech shluku a ukazatel na prvni
 *          polozku pole (ukalazatel na prvni shluk v alokovanem poli) ulozi do pameti,
 *          kam se odkazuje parametr 'arr'. Funkce vraci pocet nactenych objektu (shluku).
 *          V pripade nejake chyby uklada do pameti, kam se odkazuje 'arr', hodnotu NULL.
 *
 * @warning Ak sa subor neotvori alebo ak sa data nenacitaju korektne, vypise chybu na stdout. 
 * 
 */

class load_clusters {
int load_clusters(char *filename, struct cluster_t **arr);
};

/**
 * @brief   Tiskne pole zhlukov
 *
 * @param   struct cluster_t *c1    zhluk
 *
 * @return  (void) vytiskne zhluky
 *
 * @note    Tisk pole shluku. Parametr 'carr' je ukazatel na prvni polozku (shluk).
 *          Tiskne se prvnich 'narr' shluku.
 * @warning --
 * 
 */

class print_clusters {
void print_clusters(struct cluster_t *carr, int narr);
}

/**
 * @}
 */
