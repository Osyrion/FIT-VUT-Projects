# Makefile
# IFJ_projekt_2017
# Drahomir Dlabaja (xdlaba02), 


# Promenna nazvu projektu
TARGET = IFJ_projekt_2017

# Promenne pro nazvy slozek
DEPDIR = .
SRCDIR = .
INCDIR = .
OBJDIR = .
BINDIR = .
ZIPDIR = tmp
DOCDIR = doc

# Promenne pro preklad
CC = gcc
CFLAGS = -Iinclude -O0 -std=c11 -Wall -Wextra -pedantic -Werror -g
DEPFLAGS = -MT $@ -MMD -MP -MF $(DEPDIR)/$*.Td

# Pro kazdy *.c soubor ve slozce $(SRCDIR) do promenne ulozi nalezici *.o soubor ve slozce $(OBJDIR)
SRCS = $(wildcard $(SRCDIR)/*.c)
OBJS = $(addsuffix .o, $(addprefix $(OBJDIR)/, $(basename $(notdir $(SRCS)))))

#pravidla pro preklad
all: $(DEPDIR) $(BINDIR) $(OBJDIR) $(BINDIR)/$(TARGET)

$(BINDIR)/$(TARGET): $(OBJS)
	$(CC) $(CFLAGS) $(OBJS) -o $@	
 
$(OBJDIR)/%.o: $(SRCDIR)/%.c $(DEPDIR)/%.d
	$(CC) $(DEPFLAGS) $(CFLAGS) -c $< -o $@
	@mv -f $(DEPDIR)/$*.Td $(DEPDIR)/$*.d && touch $@


$(DEPDIR)/%.d: ;
.PRECIOUS: $(DEPDIR)/%.d

# Vlozi vygenerovane soubory se zavislostma
-include $(patsubst %,$(DEPDIR)/%.d,$(basename $(notdir $(SRCS))))


# Vytvori slozky pro objektove soubory a vysledny binarni soubor
$(DEPDIR):
	mkdir -p $(DEPDIR)
$(BINDIR):
	mkdir -p $(BINDIR)
$(OBJDIR):
	mkdir -p $(OBJDIR)


.PHONY: clean run zip doc

clean:
	rm -rf $(OBJDIR) $(BINDIR) $(DEPDIR) *.zip $(DOCDIR)/*.aux $(DOCDIR)/*.log 

run: $(BINDIR)/$(TARGET)
	./$^

zip: doc 
	mkdir -p $(ZIPDIR)
	cp Makefile $(ZIPDIR)
	sed -i -e 's/= \.dep/= \./g' $(ZIPDIR)/Makefile 
	sed -i -e 's/= ./= \./g' $(ZIPDIR)/Makefile 
	sed -i -e 's/= ./= \./g' $(ZIPDIR)/Makefile 
	sed -i -e 's/= ./= \./g' $(ZIPDIR)/Makefile 
	sed -i -e 's/= ./= \./g' $(ZIPDIR)/Makefile 
	zip -j xdlaba02.zip $(SRCDIR)/* $(INCDIR)/* $(ZIPDIR)/Makefile rozdeleni $(DOCDIR)/dokumentace.pdf
	rm -rf $(ZIPDIR)

doc: $(DOCDIR)/dokumentace.tex
	pdflatex -halt-on-error --jobname=$(DOCDIR)/dokumentace $^
