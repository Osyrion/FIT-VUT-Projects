---------- Projekt IDS 2020 ----------

-- Róbert Ďurovič [xdurov01]


------------- DROP TABLE -------------

DROP TABLE Fyzicka_osoba CASCADE CONSTRAINTS;
DROP TABLE Zamestnanec CASCADE CONSTRAINTS;
DROP TABLE Objednavka CASCADE CONSTRAINTS;
DROP TABLE Ucastnik CASCADE CONSTRAINTS;
DROP TABLE Lektor CASCADE CONSTRAINTS;
DROP TABLE Kurz CASCADE CONSTRAINTS;
DROP TABLE Firma CASCADE CONSTRAINTS;
DROP TABLE Termin CASCADE CONSTRAINTS;
DROP TABLE Faktura CASCADE CONSTRAINTS;
DROP TABLE Zakaznik CASCADE CONSTRAINTS;
DROP TABLE Miestnost CASCADE CONSTRAINTS;
DROP TABLE Certifikat CASCADE CONSTRAINTS;


----------- DROP SEQUENCE ------------

DROP SEQUENCE ZAMESTNANEC_SEQ;
DROP SEQUENCE CERTIFIKAT_SEQ;
DROP SEQUENCE FAKTURA_SEQ;


------- DROP MATERIALIZED VIEW -------

DROP MATERIALIZED VIEW KurzInfo;


------------ CREATE TABLE ------------

CREATE TABLE Fyzicka_osoba (
	klient_id NUMBER GENERATED BY DEFAULT AS IDENTITY (START WITH 1  INCREMENT BY 1) NOT NULL,
	meno VARCHAR(64),
	priezvisko VARCHAR(64)
);

CREATE TABLE Zakaznik (
	klient_id NUMBER GENERATED BY DEFAULT AS IDENTITY (START WITH 1  INCREMENT BY 1) NOT NULL,
	telefon NUMBER,
	email VARCHAR(64) NOT NULL,
	heslo VARCHAR(64) NOT NULL
);

CREATE TABLE Zamestnanec (
	zamestnanec_id NUMBER NOT NULL PRIMARY KEY, 
	meno VARCHAR(64), 
	priezvisko VARCHAR(64) NOT NULL, 
	telefon NUMBER, 
	email VARCHAR(64)
);

CREATE TABLE Firma (
	klient_id NUMBER GENERATED BY DEFAULT AS IDENTITY (START WITH 1  INCREMENT BY 1) NOT NULL,
	firma VARCHAR(64),
	ICO NUMBER, 
	DIC NUMBER
);

CREATE TABLE Miestnost (
	cislo_miestnosti NUMBER NOT NULL,
	cena_prenajom NUMBER, 
	kapacita NUMBER,
	adresa VARCHAR(64) NOT NULL,
	mesto VARCHAR(64), 
	PRIMARY KEY (cislo_miestnosti, adresa),
	CHECK (kapacita BETWEEN 1 and 5000)
);

CREATE TABLE Lektor (
	lektor_id NUMBER NOT NULL PRIMARY KEY,
	meno VARCHAR(64), 
	priezvisko VARCHAR(64) NOT NULL, 
	email VARCHAR(64), 
	telefon NUMBER, 
	kvalifikacia VARCHAR(64)
);

CREATE TABLE Objednavka (
	objednavka_id NUMBER NOT NULL PRIMARY KEY, 
	klient_id NUMBER NOT NULL,
	datum_platby DATE NOT NULL,
	datum_terminu DATE NOT NULL,
	kurz_id NUMBER NOT NULL
);

CREATE TABLE Termin (
	datum_terminu DATE NOT NULL,
	kurz_id NUMBER NOT NULL,
	cislo_miestnosti NUMBER NOT NULL, 
	obsadenost NUMBER, 
	uzavierka_registracie DATE,
	adresa VARCHAR(64) NOT NULL
);

CREATE TABLE Ucastnik (
	rodne_cislo NUMBER(10) NOT NULL, 
	meno VARCHAR(64), 
	priezvisko VARCHAR(64) NOT NULL, 
	telefon NUMBER, 
	email VARCHAR(64), 
	adresa VARCHAR(64),
	objednavka_id NUMBER NOT NULL,
	PRIMARY KEY (rodne_cislo)
);

CREATE TABLE Kurz (
	kurz_id NUMBER NOT NULL,
	lektor_id NUMBER, 
	uroven VARCHAR(64), 
	nazov VARCHAR(64), 
	lekcie NUMBER, 
	cena_kurzu NUMBER, 
	typ VARCHAR(64),
	prerekvizity VARCHAR(64), 
	obsah_kurzu VARCHAR(64),
	PRIMARY KEY (kurz_id)
);

CREATE TABLE Faktura (
	faktura_id NUMBER NOT NULL PRIMARY KEY,
	zamestnanec_id NUMBER,
	objednavka_id NUMBER
);

CREATE TABLE Certifikat (
	seriove_cislo NUMBER NOT NULL PRIMARY KEY,
	rodne_cislo NUMBER(10),
	zamestnanec_id NUMBER
);

------------- SEQUENCE ---------------

CREATE SEQUENCE CERTIFIKAT_SEQ INCREMENT BY 1 START WITH 1 NOMAXVALUE MINVALUE 1 NOCYCLE;
CREATE SEQUENCE FAKTURA_SEQ INCREMENT BY 1 START WITH 1 NOMAXVALUE MINVALUE 1 NOCYCLE;


------------ ALTER TABLE -------------

ALTER TABLE Zakaznik ADD CONSTRAINT pk_klient_id PRIMARY KEY(klient_id);

ALTER TABLE Fyzicka_osoba ADD CONSTRAINT pk_klient_id_2 PRIMARY KEY(klient_id);
ALTER TABLE Firma ADD CONSTRAINT pk_klient_id_3 PRIMARY KEY(klient_id);

ALTER TABLE Kurz ADD( FOREIGN KEY (lektor_id) REFERENCES Lektor (lektor_id));

ALTER TABLE Termin ADD( FOREIGN KEY (kurz_id) REFERENCES Kurz (kurz_id));
ALTER TABLE Termin ADD( FOREIGN KEY (cislo_miestnosti, adresa) REFERENCES Miestnost (cislo_miestnosti, adresa));
ALTER TABLE Termin ADD( PRIMARY KEY (datum_terminu, kurz_id));

ALTER TABLE Objednavka ADD( FOREIGN KEY (klient_id) REFERENCES Zakaznik (klient_id));
ALTER TABLE Objednavka ADD( FOREIGN KEY (datum_terminu, kurz_id) REFERENCES Termin (datum_terminu, kurz_id));

ALTER TABLE Ucastnik ADD( FOREIGN KEY (objednavka_id) REFERENCES Objednavka (objednavka_id));

ALTER TABLE Certifikat ADD( FOREIGN KEY (rodne_cislo) REFERENCES Ucastnik (rodne_cislo));
ALTER TABLE Certifikat ADD( FOREIGN KEY (zamestnanec_id) REFERENCES Zamestnanec (zamestnanec_id));

ALTER TABLE Faktura ADD( FOREIGN KEY (objednavka_id) REFERENCES Objednavka (objednavka_id));
ALTER TABLE Faktura ADD( FOREIGN KEY (zamestnanec_id) REFERENCES Zamestnanec (zamestnanec_id));


------------ INSERT INTO -------------

-- Miestnost --
INSERT INTO Miestnost(cislo_miestnosti, cena_prenajom, kapacita, adresa, mesto) 
VALUES(01, 11000, 511, 'Bozetechova 22', 'Brno');

INSERT INTO Miestnost(cislo_miestnosti, cena_prenajom, kapacita, adresa, mesto) 
VALUES(02, 15000, 635, 'Hradni 64', 'Praha');

INSERT INTO Miestnost(cislo_miestnosti, cena_prenajom, kapacita, adresa, mesto) 
VALUES(03, 7800, 645, 'Hanacka 98', 'Olomouc');

INSERT INTO Miestnost(cislo_miestnosti, cena_prenajom, kapacita, adresa, mesto) 
VALUES(04, 10800, 467, 'Skacelova 18', 'Brno');

INSERT INTO Miestnost(cislo_miestnosti, cena_prenajom, kapacita, adresa, mesto) 
VALUES(05, 22300, 750, 'Letni 190', 'Praha');


-- LEKTOR --
INSERT INTO Lektor(lektor_id, meno, priezvisko, email, telefon, kvalifikacia) 
VALUES(01, 'Jan', 'Novak', 'jan.novak@vutbr.cz', 82917182781, 'SQL specialista');

INSERT INTO Lektor(lektor_id, meno, priezvisko, email, telefon, kvalifikacia) 
VALUES(02, 'Petr', 'Hemzal', 'petr.hemzal@muni.cz', 829282817, 'Web Developer');

INSERT INTO Lektor(lektor_id, meno, priezvisko, email, telefon, kvalifikacia) 
VALUES(03, 'Daniel', 'Baksa', 'dan.baksa@mendelu.cz', 7261829372, 'Python programator');


-- ZAMESTNANEC --
INSERT INTO Zamestnanec(zamestnanec_id, meno, priezvisko) 
VALUES(01, 'Oliver', 'Polnicky');

INSERT INTO Zamestnanec(zamestnanec_id, meno, priezvisko, telefon) 
VALUES(02, 'Filip', 'Reus', 829763726);

INSERT INTO Zamestnanec(zamestnanec_id, meno, priezvisko, email) 
VALUES(03, 'Karel', 'Hala', 'karel.hala@firma.cz');


-- FYZICKA OSOBA --
INSERT INTO Fyzicka_osoba
VALUES(01, 'Adam', 'Rovny');

INSERT INTO Fyzicka_osoba
VALUES(03, 'Eva', 'Strelecka');


-- FIRMA --
INSERT INTO Firma
VALUES(02, 'EmbedIT', 8292781, 8262618);


-- ZAKAZNIK --
INSERT INTO Zakaznik(klient_id, telefon, email, heslo) 
VALUES(01, 837827826, 'reichel@mail.ru', 'reich567');

INSERT INTO Zakaznik(klient_id, telefon, email, heslo) 
VALUES(02, 728268362, 'novotny@yandex.com', 'novot425');

INSERT INTO Zakaznik(klient_id, telefon, email, heslo) 
VALUES(03, 739538268, 'filipovsky@seznam.cz', 'filip927');


-- KURZ --
INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(1, 01, 'pokrocila', 'SQL Databaze', 13, 33684, 'distancne', 'SQL', 'Problematika SQL Databaz');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(2, 01, 'profesionalna', 'SQL pre developerov', 15, 55625, 'prezencne', 'SQL a PL/SQL', 'Profesionalne vyuzitie SQL');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(3, 02, 'zaciatocnicka', 'Uvod do HTML', 10, 6350, 'distancne', 'obsluha PC', 'Tvorba webstranok');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(4, 03, 'pokrocila', 'Python skriptovanie', 20, 15850, 'prezencne', 'Python', 'Testovanie v Pythone');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(5, 02, 'zaciatocnicka', 'CSS', 12, 4950, 'prezencne', 'obsluha PC', 'Stylovanie v CSS');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(6, 02, 'stredne pokrocila', 'JavaScript', 33, 22350, 'prezencne', 'HTML a CSS', 'Tvorba dynamickych webstranok');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(7, 01, 'stredne pokrocila', 'Uvod do Oracle', 18, 34970, 'prezencne', 'SQL', 'Administracia DB');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(8, 03, 'stredne pokrocila', 'Python programovanie', 26, 19750, 'prezencne', 'programovanie', 'Pokrocile programovanie v Pythone');

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(9, 03, 'pokrocila', 'Python a data', 64, 38250, 'prezencne', 'Python', 'Machine learning');


-- TERMIN --
INSERT INTO Termin(datum_terminu, kurz_id, cislo_miestnosti, obsadenost, uzavierka_registracie, adresa) 
VALUES(TO_DATE('18:00 05/05/2018', 'hh24:mi dd/mm/yy'), 1, 01, 255, TO_DATE('10:00 22/04/2018', 'hh24:mi dd/mm/yy'), 'Bozetechova 22');

INSERT INTO Termin(datum_terminu, kurz_id, cislo_miestnosti, obsadenost, uzavierka_registracie, adresa) 
VALUES(TO_DATE('17:22 12/12/2019', 'hh24:mi dd/mm/yy'), 1, 01, 152, TO_DATE('10:00 22/04/2018', 'hh24:mi dd/mm/yy'), 'Bozetechova 22');

INSERT INTO Termin(datum_terminu, kurz_id, cislo_miestnosti, obsadenost, uzavierka_registracie, adresa) 
VALUES(TO_DATE('18:00 05/06/2018', 'hh24:mi dd/mm/yy'), 1, 04, 177, TO_DATE('10:00 22/04/2018', 'hh24:mi dd/mm/yy'), 'Skacelova 18');

INSERT INTO Termin(datum_terminu, kurz_id, cislo_miestnosti, obsadenost, uzavierka_registracie, adresa) 
VALUES(TO_DATE('10:00 01/01/2020', 'hh24:mi dd/mm/yy'), 2, 02, 215, TO_DATE('07:00 01/02/2020', 'hh24:mi dd/mm/yy'), 'Hradni 64');

INSERT INTO Termin(datum_terminu, kurz_id, cislo_miestnosti, obsadenost, uzavierka_registracie, adresa) 
VALUES(TO_DATE('12:00 02/02/2020', 'hh24:mi dd/mm/yy'), 3, 03, 115, TO_DATE('09:15 10/01/2020', 'hh24:mi dd/mm/yy'), 'Hanacka 98');


-- OBJEDNAVKA --
INSERT INTO Objednavka(objednavka_id, klient_id, datum_platby, datum_terminu, kurz_id) 
VALUES(01, 01, TO_DATE('08:00 01/01/2020', 'hh24:mi dd/mm/yy'), TO_DATE('12:00 02/02/2020', 'hh24:mi dd/mm/yy'), 3);

INSERT INTO Objednavka(objednavka_id, klient_id, datum_platby, datum_terminu, kurz_id) 
VALUES(02, 03, TO_DATE('12:00 03/01/2020', 'hh24:mi dd/mm/yy'), TO_DATE('10:00 01/01/2020', 'hh24:mi dd/mm/yy'), 2);

INSERT INTO Objednavka(objednavka_id, klient_id, datum_platby, datum_terminu, kurz_id) 
VALUES(03, 02, TO_DATE('08:00 22/04/2018', 'hh24:mi dd/mm/yy'), TO_DATE('18:00 05/05/2018', 'hh24:mi dd/mm/yy'), 1);


-- UCASTNIK --
INSERT INTO Ucastnik(rodne_cislo, meno, priezvisko, telefon, email, adresa, objednavka_id) 
VALUES(8292728918, 'Andrej', 'Slovak', 829278261, 'a.slovak@centrum.sk', 'Fikulkova 35, Brno 635 00', 03);

INSERT INTO Ucastnik(rodne_cislo, meno, priezvisko, telefon, email, adresa, objednavka_id) 
VALUES(9200272526, 'Eliska', 'Gretzlova', 729272781, 'elza.gretzl@centrum.cz', 'Fibichova 267, Olomouc 625 78', 02);

INSERT INTO Ucastnik(rodne_cislo, meno, priezvisko, telefon, email, adresa, objednavka_id) 
VALUES(8292782700, 'Samuel', 'Peroutka', 863571578, 'sam.peroutka@email.cz', 'Hlavni 77, Praha 622 00', 01);

INSERT INTO Ucastnik(rodne_cislo, meno, priezvisko, telefon, email, adresa, objednavka_id) 
VALUES(6272837281, 'Karolina', 'Vozecka', 123451234, 'kara.voz@email.cz', 'Vedlejsi 74, Karlovy Vary 612 00', 01);

INSERT INTO Ucastnik(rodne_cislo, meno, priezvisko, telefon, email, adresa, objednavka_id) 
VALUES(9373528178, 'Igor', 'Fajnorka', 432112345, 'igor.fajnorka@email.cz', 'Vondrkova 66, Ostrava 662 00', 01);

INSERT INTO Ucastnik(rodne_cislo, meno, priezvisko, telefon, email, adresa, objednavka_id) 
VALUES(0383628268, 'Michal', 'Rovinka', 555554444, 'm.rovinka@email.cz', 'Nemecka 56, Chomutov 693 00', 01);


-- FAKTURA --
INSERT INTO Faktura(faktura_id, zamestnanec_id, objednavka_id) 
VALUES(FAKTURA_SEQ.NEXTVAL, 01, 02);

INSERT INTO Faktura(faktura_id, zamestnanec_id, objednavka_id) 
VALUES(FAKTURA_SEQ.NEXTVAL, 02, 01);

INSERT INTO Faktura(faktura_id, zamestnanec_id, objednavka_id) 
VALUES(FAKTURA_SEQ.NEXTVAL, 02, 03);


-- CERTIFIKAT --
INSERT INTO Certifikat(seriove_cislo, rodne_cislo, zamestnanec_id) 
VALUES(CERTIFIKAT_SEQ.NEXTVAL, 8292728918, 01);

INSERT INTO Certifikat(seriove_cislo, rodne_cislo, zamestnanec_id)
VALUES(CERTIFIKAT_SEQ.NEXTVAL, 9200272526, 03);

INSERT INTO Certifikat(seriove_cislo) 
VALUES(CERTIFIKAT_SEQ.NEXTVAL);


------------ SQL DOTAZY --------------

-- [1] dotaz nad tromi tabulkami: Vypise zamestnanca, ktory vydal konkretny certifikat so seriovym cislom a ucastnika, ktory tento certifikat ziskal
SELECT Z.priezvisko AS vydal, D.seriove_cislo AS cislo_certifikatu, U.priezvisko AS ziskal
FROM Zamestnanec Z, Certifikat D, Ucastnik U
WHERE Z.zamestnanec_id = D.zamestnanec_id and D.rodne_cislo = U.rodne_cislo;

-- [1] dotaz nad dvomi tabulkami: Vypise kurz a lektora, ktory ho vyucuje a taktiez vypise uroven a cenu tohto kurzu
SELECT L.priezvisko AS lektor, K.nazov AS nazov_kurzu, K.uroven, K.cena_kurzu AS cena_kurzu
FROM Lektor L, Kurz K
WHERE L.lektor_id = K.lektor_id;

-- [2] dotaz nad dvomi tabulkami: Vypise vsetky terminy a k nim aj nazvy a typy (prezencni/distancni) a urovne kurzov, ktore sa v dane terminy vyucuju
SELECT K.nazov, K.typ, K.uroven, T.datum_terminu AS datum
FROM Termin T, Kurz K
WHERE T.kurz_id = K.kurz_id;

-- [1] dotaz obsahujuci predikat EXISTS: Vypise meno zamestnanca, ktory nevydal ziadny certifikat
SELECT U.priezvisko
FROM Zamestnanec U
WHERE NOT EXISTS (
	SELECT P.zamestnanec_id
	FROM Certifikat P
	WHERE P.zamestnanec_id = U.zamestnanec_id
);

-- [1] dotaz obsahujuci predikat IN s vnorenym SELECTom: Vypise meno a priezvisko Ucastnika, ktory sa zucastnil kurzu v roku 2019
SELECT H.meno, H.priezvisko
FROM Ucastnik H
WHERE H.objednavka_id
IN (
	SELECT B.objednavka_id
	FROM Objednavka B
	WHERE B.datum_terminu BETWEEN '01-01-2019' and '31-12-2019'
);

-- [1] dotaz s klauzulou GROUP BY a agregatnou funkciou: Vypise najnizsiu cenu pre kazdy typ kurzu (prezencni a distancni)
SELECT K.typ, min(K.cena_kurzu)
FROM Kurz K
GROUP BY K.typ;

-- [2] dotaz s klauzulou GROUP BY a agregatnou funkciou: Vypise priemernu cenu za prenajom miestnosti v kazdom meste, kde sa moze konat vyuka
SELECT M.mesto, avg(M.cena_prenajom)
FROM Miestnost M
GROUP BY M.mesto;


------------- PROCEDURY --------------

SET serveroutput ON;

-- vypocet percentualnej vytazenosti miestnostni v danom meste
CREATE OR REPLACE PROCEDURE perc_obsadenost(mesto IN VARCHAR)
IS
CURSOR capable IS SELECT * FROM Termin NATURAL JOIN Miestnost;
	tmp capable%ROWTYPE;
	overall_percentage NUMBER;
	capacity NUMBER;
	cap_classroom NUMBER;
BEGIN
	overall_percentage := 0;
	cap_classroom := 0;
	capacity := 0;
	OPEN capable;
	LOOP
		FETCH capable INTO tmp;
		EXIT WHEN capable%NOTFOUND;

		IF (tmp.mesto = mesto) THEN
			capacity := capacity + tmp.kapacita;
			cap_classroom := cap_classroom + tmp.obsadenost;
		END IF;

	END LOOP;

	-- vypocet percenta
	overall_percentage := cap_classroom / capacity;
	overall_percentage := overall_percentage * 100;
	-- zaokruhlenie
	overall_percentage := ROUND(overall_percentage);

	CLOSE capable;
	dbms_output.put_line('V meste ' || mesto || ' je v ramci kurzov obsadenych celkovo ' || cap_classroom || ' miest z ' || capacity || ' dostupnych, co predstavuje ' || overall_percentage || ' percentnu obsadenost.');
	EXCEPTION
	WHEN ZERO_DIVIDE THEN
	dbms_output.put_line('Nie je dostupna ziadna miestnost!');
	WHEN OTHERS THEN
	Raise_Application_Error(-20005, 'Nastala chyba!');
END;
/

-- ukazka procedury
EXECUTE perc_obsadenost('Brno');



-- vypocita priemernu cenu jednej lekcie
CREATE OR REPLACE PROCEDURE cena_lekcie(nazov IN VARCHAR)
IS
CURSOR price IS SELECT * FROM Kurz;
	tmp price%ROWTYPE;
	num_lectures NUMBER;
	overall_price NUMBER;
	hour_price NUMBER;
BEGIN
num_lectures := 0;
overall_price := 0;
hour_price := 0;
OPEN price;
LOOP
	FETCH price into tmp;
	EXIT WHEN price%NOTFOUND;

	IF (tmp.nazov = nazov) THEN
		num_lectures := num_lectures + tmp.lekcie;
		overall_price := overall_price + tmp.cena_kurzu;
	END IF;
END LOOP;

hour_price := overall_price / num_lectures;

-- zaokruhlenie
hour_price := ROUND(hour_price);

CLOSE price;
dbms_output.put_line('V kurze ' || nazov || ' je priemerna sadzba za lekciu ' || hour_price || ' czk.');
EXCEPTION
WHEN ZERO_DIVIDE THEN
dbms_output.put_line('Pre dany kurz nemozno sadzbu za lekciu dopocitat!');
WHEN OTHERS THEN
Raise_Application_Error(-20005, 'Nastala chyba!');
END;
/

-- ukazka prvej procedury
EXECUTE cena_lekcie('JavaScript');


-------------- TRIGGER ---------------

-- validacia rodneho cisla
CREATE OR REPLACE TRIGGER trigger_r_c
AFTER INSERT OR UPDATE ON Ucastnik
FOR EACH ROW
DECLARE
    r_c Ucastnik.rodne_cislo%TYPE;
    den NUMBER(2);
    mesic NUMBER(2);
    rok NUMBER(2);
    datum DATE;
BEGIN
    r_c := :NEW.rodne_cislo;
    rok   := r_c / 100000000;	     
    mesic := MOD((r_c / 1000000), 100); 
    den   := MOD((r_c / 10000), 100);   
    	   
   	-- ak jde o zenu
    IF (mesic > 50) THEN
  		mesic := mesic - 50;
    END IF; 
   

    -- nie je delitelne 11
    IF (MOD(r_c, 11) <> 0) THEN
  		Raise_Application_Error (-20203, 'Chybne zadane rodne cislo!');
    END IF;
   
    -- kontrola platniho data
    BEGIN
  		datum := den||'.'||mesic||'.'||rok;
    EXCEPTION
  	WHEN OTHERS THEN
   	    Raise_Application_Error (-20204, 'Chybne zadane rodne cislo!');
	END;
END;	

/
show errors


-- validacia datumu terminu
CREATE OR REPLACE TRIGGER trigger_valid_date
	AFTER INSERT OR UPDATE ON Termin
	FOR EACH ROW
BEGIN
	IF :new.datum_terminu is null OR 
	   :new.datum_terminu < SYSDATE OR
	   :new.datum_terminu > :new.uzavierka_registracie OR
	   :new.datum_terminu = TO_DATE('24/12/2019', 'DD/MM/YYYY') OR
	   :new.datum_terminu = TO_DATE('25/12/2019', 'DD/MM/YYYY')
	THEN
		Raise_Application_Error(-20001, 'Chyba v datume terminu!');
	END IF;
END;

/
show errors



-- autoinkrementacia id zamestnanca v pripade, ze nie je definovane
CREATE SEQUENCE ZAMESTNANEC_SEQ;

CREATE OR REPLACE TRIGGER trigger_zam_id
	BEFORE INSERT OR UPDATE ON Zamestnanec
	FOR EACH ROW
BEGIN
	IF :new.zamestnanec_id is null THEN
	   :new.zamestnanec_id := ZAMESTNANEC_SEQ.NEXTVAL;
	END IF;
END;

/
show errors


--------- UDELENENIE PRAV -----------

GRANT ALL ON Objednavka TO xtester1;
GRANT ALL ON Miestnost TO xtester1;
GRANT ALL ON Zakaznik TO xtester1;
GRANT ALL ON Lektor TO xtester1;
GRANT ALL ON Kurz TO xtester1;
GRANT ALL ON Termin TO xtester1;
GRANT ALL ON Faktura TO xtester1;
GRANT ALL ON Ucastnik TO xtester1;
GRANT ALL ON Certifikat TO xtester1;
GRANT ALL ON Zamestnanec TO xtester1;

GRANT EXECUTE ON cena_lekcie TO xtester1;
GRANT EXECUTE ON perc_obsadenost TO xtester1;


----------- EXPLAIN PLAN -------------

EXPLAIN PLAN FOR
SELECT meno, priezvisko, count(kurz_id) AS pocet_kurzov, SUM(cena_kurzu) AS celkova_cena
FROM Lektor, Kurz
WHERE Lektor.lektor_id = Kurz.lektor_id
GROUP BY meno, priezvisko;

-- SELECT plan_table_output FROM TABLE(DBMS_XPLAN.display);

CREATE INDEX kurz_index ON Kurz (kurz_id, cena_kurzu);

EXPLAIN PLAN FOR
SELECT meno, priezvisko, count(kurz_id) AS pocet_kurzov, SUM(cena_kurzu) AS celkova_cena
FROM Lektor, Kurz
WHERE Lektor.lektor_id = Kurz.lektor_id
GROUP BY meno, priezvisko;

SELECT plan_table_output FROM TABLE(DBMS_XPLAN.display());

DROP INDEX kurz_index;


--------  MATERIALIZED VIEW ----------

CREATE MATERIALIZED VIEW LOG ON Kurz WITH PRIMARY KEY, ROWID(uroven) INCLUDING NEW VALUES;

CREATE MATERIALIZED VIEW KurzInfo
CACHE                       -- optimalizacia citania pohladu
BUILD IMMEDIATE             -- naplnenie pohladu po vytvoreni
REFRESH FAST ON COMMIT      -- aktualizacia pohladov podla zaznamov po potvrdeni zmien v tabulkach
ENABLE QUERY REWRITE        -- pouzitie materializovaneho pohladu optimalizatorom
AS SELECT K.uroven, count(K.uroven) as urovne FROM Kurz K GROUP BY K.uroven;

GRANT ALL ON KurzInfo TO xtester1;

SELECT * FROM KurzInfo;

INSERT INTO Kurz(kurz_id, lektor_id, uroven, nazov, lekcie, cena_kurzu, typ, prerekvizity, obsah_kurzu) 
VALUES(10, 01, 'zaciatocnicka', 'MS SQL', 15, 23883, 'prezencne', 'MS SQL', 'Problematika MS SQL Databaz');

COMMIT;

SELECT * FROM KurzInfo;

DELETE FROM Kurz WHERE kurz_id = '10';

COMMIT;

SELECT * FROM KurzInfo;

-- DROP MATERIALIZED VIEW KurzInfo;

--------------------------------------
--------- TESTY TRIGGEROV ------------

/*

-- test triggeru c.1 -- nevalidny datum - vyhodi chybu [OK]
INSERT INTO Termin(datum_terminu, kurz_id, cislo_miestnosti, obsadenost, uzavierka_registracie, adresa) 
VALUES(TO_DATE('12:05 24/12/2015', 'hh24:mi dd/mm/yy'), 2, 02, 15, TO_DATE('12:05 25/12/2018', 'hh24:mi dd/mm/yy'), 'Hradni 64');

SELECT * FROM Termin;

-- test triggeru c.2 -- rodne cislo v nespravnom formate - vyhodi chybu [OK]
INSERT INTO Ucastnik(rodne_cislo, meno, priezvisko, telefon, email, adresa, objednavka_id) 
VALUES(8191, 'Jan', 'Kozak', 09076899766, 'kozak@jan.to', 'Bratislavska 01, Brno 696 35', 03);

SELECT * FROM Ucastnik;

*/

--------------------------------------
--------------------------------------
