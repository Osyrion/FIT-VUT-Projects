# MAKEFILE for LaTeX

### Typografie a publikovani ###

# Meno: Robert Durovic
# Login: xdurov01
# Datum: 04/2017
# Projekt: proj4

### Bibliograficke citace ###

filename=proj4

all:
	latex $(filename).tex
	bibtex $(filename).aux
	latex $(filename).tex
	latex $(filename).tex 
	dvips -sPAPERSIZE=a4 $(filename).dvi
	ps2pdf -sPAPERSIZE=a4 $(filename).ps 

clean:
	-rm -f $(filename).aux $(filename).ps $(filename).dvi $(filename).log
