# MAKEFILE for LaTeX

### Typografie a publikovani ###

# Meno: Robert Durovic
# Login: xdurov01
# Datum: 02/2017
# Projekt: proj1



filename=proj1

all:
	latex $(filename) .tex
	dvips -t a4 $(filename).dvi
	ps2pdf $(filename).ps
clean:
	-rm -f $(filename).aux $(filename).ps $(filename).dvi $(filename).log
