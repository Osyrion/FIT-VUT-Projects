// scanner.c
// IFJ_projekt_2017
// Drahomir Dlabaja (xdlaba02),
// Robert Durovic (xdurov01)

#include <stdbool.h>
#include <stddef.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>

#include "token.h"
#include "scanner.h"
#include "infstr.h"
#include "error.h"

token *putback = NULL;

int is_keyword(char *str)
{	
	for (int i = keywords_start + 1 ; i < keywords_end; i++)
	{
		if (!strcmp(str, tokens[i]))
			return i;
	}

	return t_id;
}

void put_token(token **t) {
	putback = *t;
	*t = NULL;
}

token *get_token()
{
	if (putback) {
		token *tmp = putback;
		putback = NULL;
		return tmp;
	}

	// retezec pro cteni termu, inicializuje se az kdyz je to nutne
	infString s; 

	// retezec pro cteni vstupu, ktery se vytiskne v pripade erroru
	infString raw; 
	ISInit(&raw);
	
	// pociatocny stav
	states state = STATE_INIT;
	
	// znak
	int c = '\0';
	
	// znak pre string no-lowercase
	int a = '\0';

	//na zacatku scanner odignoruje vsechny konce radku, proto 1;
	static int ignore_eol = 1;

	//proemnna pro ulozeni cisla escape sekvence
	char escnum = 0;

	// aktualny riadok
	static int line = 1;

	token_type t_type;

	// while true
	while (1) {
		c = getchar();
		ISAddChar(&raw, c);
		a = c;
		c = tolower(c);

		switch (state) {
			case STATE_INIT:
				if (c == '\n') {
			    		line++;
			    		if (!ignore_eol) 
						state = STATE_RETURN_EOL;
				}
				else if (c == '/') {
					state = STATE_SLASH;
				}
				else if (c == '!') {
					state = STATE_EXCLAM;
				}
				else if (c == '\'') {
					state = STATE_LINE_C;
				}
		    		else if (c == '+') {
					t_type = t_plus;
					state = STATE_RETURN_OPERATOR;
		    		}
				else if (c == '-') {
					t_type = t_minus;
					state = STATE_RETURN_OPERATOR;
				}
				else if (c == '*') {
					t_type = t_mul;
					state = STATE_RETURN_OPERATOR;
				}
		    		else if (c == '\\') {
					t_type = t_divint;
					state = STATE_RETURN_OPERATOR;
		    		}
				else if (c == '=') {
					t_type = t_eq;
					state = STATE_RETURN_OPERATOR;
				}
				else if (c == '<') {
					state = STATE_LESS;
				}
				else if (c == '>') {
					state = STATE_GREATER;
				}
				else if (c == ';') {
					t_type = t_semicolon;
					state = STATE_RETURN_OPERATOR;
		    		}
				else if (c == '(') {
					t_type = t_lparenthesis;
					state = STATE_RETURN_OPERATOR;
				}
				else if (c == ')') {
					t_type = t_rparenthesis;
					state = STATE_RETURN_OPERATOR;
				}
				else if (c == ',') {
					t_type = t_comma;
					state = STATE_RETURN_OPERATOR;
				}
		    		else if (isalpha(c) || c == '_') {
					ISInit(&s);
					ISAddChar(&s, c);
					state = STATE_WORD;
		    		}
				else if (isdigit(c)) {
					ISInit(&s);
					ISAddChar(&s, c);
					state = STATE_NUM;
		    		}
				else if (isspace(c)) {
					// ignore space
				}	
		    		else {
					state = STATE_ERROR;
		    		}
				break; // end STATE_INIT

			case STATE_WORD:
				if (isalnum(c) || c == '_') {
					ISAddChar(&s, c);
		    		}
		    		else {
					ungetc(c, stdin);
					state = STATE_RETURN_WORD;
				}
				break; // end STATE_WORD

			case STATE_NUM:
				if (isdigit(c)) {
					ISAddChar(&s, c);
	   			}
		    		else if (c == '.') {
					ISAddChar(&s, c);
					state = STATE_DOUBLE;
	            		}
		    		else if (c == 'e') {
		        		ISAddChar(&s, c);
					state = STATE_EXPONENT1;
       		    		}
		    		else {
					ungetc(c, stdin);
					state = STATE_RETURN_INTEGER;
		    		} 
				break; // end STATE_NUM
			    
			case STATE_DOUBLE:
	   		    	if (isdigit(c)) {
					ISAddChar(&s, c);
	  		    	}
			    	else if (c == 'e') {
					ISAddChar(&s, c);
					state = STATE_EXPONENT1;
			    	}
			    	else {
					ungetc(c, stdin);
					state = STATE_RETURN_DOUBLE;
			    	}
				break; // end STATE_DOUBLE
			    
			case STATE_EXPONENT1:
		    		if (isdigit(c)) {
					ISAddChar(&s, c);
					state = STATE_EXPONENT3;
				}
			    	else if (c == '+' || c == '-') {
					ISAddChar(&s, c);
					state = STATE_EXPONENT2;
		    		}
				else {
					state = STATE_ERROR_FREE;
				}
		    		break; // end STATE_EXPONENT1

			case STATE_EXPONENT2:	
				if (isdigit(c)) {
					ISAddChar(&s, c);
					state = STATE_EXPONENT3;
				}
				else {
					state = STATE_ERROR_FREE;
				}
				break; // end STATE_EXPONENT2

			case STATE_EXPONENT3:
				if (isdigit(c)) {
					ISAddChar(&s, c);
				}
				else {
					ungetc(c, stdin);
					state = STATE_RETURN_DOUBLE;
				}
				break; // end STATE_EXPONENT3
			    
			case STATE_EXCLAM:
		    		if (c == '"') {
					ISInit(&s);
					state = STATE_STRING;
			     	}
			     	else {
					state = STATE_ERROR;
		     		}
				break; // end STATE_EXCLAM 

			case STATE_STRING:
		    		if (c == '"') {
					state = STATE_RETURN_STRING;
		    		}
		    		else if (c == '\\') {
					state = STATE_ESCAPE1;
		    		}
		    		else {
					ISAddChar(&s, a);
		    		}
				break; // end STATE_STRING

			case STATE_GREATER:
	    	    		if (c == '=') {
					t_type = t_greatereq;
					state = STATE_RETURN_OPERATOR;
 	        	    	}
	     		    	else {
				    	ungetc(c, stdin);
					t_type = t_greater;
					state = STATE_RETURN_OPERATOR;
   	     		    	}
				break; // end GRATER

			case STATE_LESS:
				if (c == '=') {
					t_type = t_lesseq;
					state = STATE_RETURN_OPERATOR;
				}
				else if (c == '>') {
					t_type = t_noteq;
					state = STATE_RETURN_OPERATOR;
				}
				else {
					ungetc(c, stdin);
					t_type = t_less;
					state = STATE_RETURN_OPERATOR;
				}	
				break; // end STATE_LESS
			
			case STATE_SLASH:
	            		if (c == '\'') {
					state = STATE_BLOCK_C;
	        	    	}
	        	    	else {
					ungetc(c, stdin);
					t_type = t_divreal;
					state = STATE_RETURN_OPERATOR;
		    		}
				break; // end STATE_SLASH

			case STATE_BLOCK_C:
		     		if (c == '\'') 
					state = STATE_END_BLK_C;
	   		     	else if (c == '\n') 
					line++;
				break; // end STATE_BLOCK_C

			case STATE_END_BLK_C:
			   	if (c == '/')
					state = STATE_INIT;
				else
					state = STATE_BLOCK_C;
				break; // end STATE_END_BLK_C
			
			case STATE_LINE_C:
				if (c == '\n') {
				    	ungetc(c, stdin);
				    	state = STATE_INIT;
			       	}
				break; // end STATE_LINE_C

			case STATE_ESCAPE1:
				if (c == '"') {
					ISAddChar(&s, '"');
					state = STATE_STRING;
	    			}
				else if (c == 'n') {
					ISAddChar(&s, '\n');
					state = STATE_STRING;
				}
				else if (c == 't') {
					ISAddChar(&s, '\t');
					state = STATE_STRING;
				}
				else if (c == '\\') {
					ISAddChar(&s, '\\');
					state = STATE_STRING;
				}
				else if (isdigit(c)) {
					escnum += c - '0';
					state = STATE_ESCAPE2;
				}
				else {
					state = STATE_ERROR_FREE;
				}
				break; // end STATE_ESCAPE1

			case STATE_ESCAPE2:
				if (isdigit(c)) {
					escnum *= 10;
					escnum += c - '0';
					state = STATE_ESCAPE3;
				}
				else {
					ungetc(c, stdin);
					ISAddChar(&s, escnum);
					state = STATE_STRING;
				}
				break; // end STATE_ESCAPE2

			case STATE_ESCAPE3:
				if (isdigit(c)) {
					escnum *= 10;
					escnum += c - '0';
					ISAddChar(&s, escnum);
				}
				else {
					ungetc(c, stdin);
					ISAddChar(&s, escnum);
				}
				state = STATE_STRING;
				break; // end STATE_ESCAPE3
	
			default:
				break;
		} // end swicth (state)

		switch (state) { // switch pro navratove hodnoty, aby se nemuselo delat ungetc dvakrat po sobe
			case STATE_RETURN_OPERATOR:
				ignore_eol = 0;
				ISFree(&raw);
				return token_ctor(line, t_type, NULL);
				break; // end STATE_RETURN_OPERATOR

			case STATE_RETURN_EOL:
				ignore_eol = 1;
				ISFree(&raw);
				return token_ctor(line, t_eol, NULL);
				break; // end STATE_RETURN_EOL

			case STATE_RETURN_WORD:
				ignore_eol = 0;
				ISFree(&raw);
				t_type = is_keyword(s.string);
	   	        	if (t_type == t_id) {
		        		return token_ctor(line, t_id, s.string); //string se uz editovat nebude, proto se predava pouze alokovane pole charu a pomocna struktura se ztraci.
				}
				else {
					ISFree(&s); //u klicoveho slova predavame pouze vyctovou hodnotu, nikoliv string, takze jej musime uvolnit
					return token_ctor(line, t_type, NULL);
				}
				break; // end RETURN_KEYSTATE_WORD

			case STATE_RETURN_INTEGER:
				ignore_eol = 0;
				ISFree(&raw);
				return token_ctor(line, t_integerval, s.string);
				break; // end STATE_RETURN_INTEGER

			case STATE_RETURN_DOUBLE:
				ignore_eol = 0;
				ISFree(&raw);
				return token_ctor(line, t_doubleval, s.string);
				break; // end STATE_RETURN_DOUBLE

			case STATE_RETURN_STRING:
				ignore_eol = 0;
				ISFree(&raw);
				return token_ctor(line, t_stringval, s.string);
				break; // end STATE_RETURN_STRING

			case STATE_ERROR:
				while (!isspace(c = getchar()))
					ISAddChar(&raw, c);
				lexical_error(line, raw.string);
				ISFree(&raw);
				return NULL;
				break; // end STATE_ERROR

			case STATE_ERROR_FREE:
				while (!isspace(c = getchar()))
					ISAddChar(&raw, c);
				lexical_error(line, raw.string);
				ISFree(&s);
				ISFree(&raw);
				return NULL;
				break; // end STATE_ERROR_FREE
			default:
				break;
		} // end switch
	} // end while	
}
