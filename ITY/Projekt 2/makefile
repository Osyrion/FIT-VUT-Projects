# MAKEFILE for LaTeX

### Typografie a publikovani ###

# Meno: Robert Durovic
# Login: xdurov01
# Datum: 03/2017
# Projekt: proj2

### Sazba dokumentů s matematickými výrazy ###

filename=proj2

all:
	latex $(filename) .tex
	latex $(filename) .tex
	dvips -t a4 $(filename).dvi
	ps2pdf $(filename).ps
clean:
	-rm -f $(filename).aux $(filename).ps $(filename).dvi $(filename).log
