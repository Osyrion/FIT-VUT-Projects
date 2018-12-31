-- cpu.vhd: Simple 8-bit CPU (BrainF*ck interpreter)
-- Copyright (C) 2018 Brno University of Technology,
--                    Faculty of Information Technology
-- Author(s): Robert Durovic
-- Login: xdurov01
-- Date: 11/12/2018

library ieee;
use ieee.std_logic_1164.all;
use ieee.std_logic_arith.all;
use ieee.std_logic_unsigned.all;

-- ----------------------------------------------------------------------------
--                        Entity declaration
-- ----------------------------------------------------------------------------
entity cpu is
 port (
   CLK   : in std_logic;  -- hodinovy signal
   RESET : in std_logic;  -- asynchronni reset procesoru
   EN    : in std_logic;  -- povoleni cinnosti procesoru
 
   -- synchronni pamet ROM
   CODE_ADDR : out std_logic_vector(11 downto 0); -- adresa do pameti
   CODE_DATA : in std_logic_vector(7 downto 0);   -- CODE_DATA <- rom[CODE_ADDR] pokud CODE_EN='1'
   CODE_EN   : out std_logic;                     -- povoleni cinnosti
   
   -- synchronni pamet RAM
   DATA_ADDR  : out std_logic_vector(9 downto 0); -- adresa do pameti
   DATA_WDATA : out std_logic_vector(7 downto 0); -- mem[DATA_ADDR] <- DATA_WDATA pokud DATA_EN='1'
   DATA_RDATA : in std_logic_vector(7 downto 0);  -- DATA_RDATA <- ram[DATA_ADDR] pokud DATA_EN='1'
   DATA_RDWR  : out std_logic;                    -- cteni z pameti (DATA_RDWR='1') / zapis do pameti (DATA_RDWR='0')
   DATA_EN    : out std_logic;                    -- povoleni cinnosti
   
   -- vstupni port
   IN_DATA   : in std_logic_vector(7 downto 0);   -- IN_DATA obsahuje stisknuty znak klavesnice pokud IN_VLD='1' a IN_REQ='1'
   IN_VLD    : in std_logic;                      -- data platna pokud IN_VLD='1'
   IN_REQ    : out std_logic;                     -- pozadavek na vstup dat z klavesnice
   
   -- vystupni port
   OUT_DATA : out  std_logic_vector(7 downto 0);  -- zapisovana data
   OUT_BUSY : in std_logic;                       -- pokud OUT_BUSY='1', LCD je zaneprazdnen, nelze zapisovat,  OUT_WE musi byt '0'
   OUT_WE   : out std_logic                       -- LCD <- OUT_DATA pokud OUT_WE='1' a OUT_BUSY='0'
 );
end cpu;


-- ----------------------------------------------------------------------------
--                      Architecture declaration
-- ----------------------------------------------------------------------------
architecture behavioral of cpu is


   ----------- REGISTRE -----------

   --- PC ---
   signal pc      : std_logic_vector(11 downto 0);   
   signal pc_inc  : std_logic;
   signal pc_dec  : std_logic;

   --- PTR ---
   signal ptr     : std_logic_vector(9 downto 0);    
   signal ptr_inc : std_logic;
   signal ptr_dec : std_logic;

   --- CNT ---
   signal cnt     : std_logic_vector(7 downto 0);
   signal cnt_inc : std_logic;
   signal cnt_dec : std_logic;


   -------- TEMPORARY DATA ---------

   signal temp_data : std_logic_vector(7 downto 0);



   ---------- MULTIPLEXOR ----------

   --- MX ---
   signal mx_sel  : std_logic_vector(1 downto 0);

   ---------- INSTRUKCIE ----------

   type instruction_set is (
      pointer_inc,    -- >
      pointer_dec,    -- <
      data_inc,       -- +
      data_dec,       -- -
      while_start,    -- [
      while_end,      -- ]
      print,          -- .
      load,           -- ,
      comment,        -- #
      rwr_num,        -- 0-9
      rwr_char,       -- A-F
      halt,           -- return
      other           -- other
   );
   signal instruction : instruction_set;

   ---------- AUTOMAT ----------

   type fsm_state is (
      S_INIT, S_FETCH, S_DECODE, S_PTR_INC, S_PTR_DEC, S_DATA_INC, S_DATA_INC_2, S_DATA_DEC, S_DATA_DEC_2, 
      S_PRINT, S_PRINT_2, S_LOAD, S_LOAD_2, S_COMMENT, S_COMMENT_2, S_COMMENT_3, S_COMMENT_4,
      S_HALT, S_OTHERS, S_RWR_NUM, S_RWR_CHAR,
      S_WHILE_START, S_WHILE_START_2, S_WHILE_START_3, S_WHILE_START_4, 
      S_WHILE_END, S_WHILE_END_1, S_WHILE_END_2, S_WHILE_END_3, S_WHILE_END_4, S_WHILE_END_5
   );
   signal pstate : fsm_state;  -- aktualny stav
   signal nstate : fsm_state;  -- nasledujuci stav

begin

--- PC REGISTER ---
pc_reg: process(CLK, RESET)
begin
   if (RESET = '1') then
      pc <= (others => '0');
   elsif (CLK'event and CLK = '1') then
      if (pc_inc = '1') then
         pc <= pc + 1;
      elsif (pc_dec = '1') then
         pc <= pc - 1;
      end if;
   end if;
end process;

CODE_ADDR <= pc;

--- PTR REGISTER ---
ptr_reg: process(CLK, RESET)
begin
   if (RESET = '1') then
      ptr <= (others => '0');
   elsif (CLK'event and CLK = '1') then
      if (ptr_inc = '1') then
         ptr <= ptr + 1;
      elsif (ptr_dec = '1') then
         ptr <= ptr - 1;      
      end if;
   end if;
end process;

DATA_ADDR <= ptr;

--- CNT REGISTER ---
cnt_reg: process(CLK, RESET)
begin
   if (RESET = '1') then
      cnt <= (others => '0');
   elsif (CLK'event and CLK = '1') then
      if (cnt_inc = '1') then
         cnt <= cnt + 1;
      elsif (cnt_dec = '1') then
         cnt <= cnt - 1;
      end if;
   end if;
end process;

--OUT_DATA <= DATA_RDATA;

--- MULTIPLEXOR ---
mx: process(IN_DATA, DATA_RDATA, mx_sel, CODE_DATA)
begin
   case (mx_sel) is
      when "00" => DATA_WDATA <= IN_DATA;
      when "01" => DATA_WDATA <= DATA_RDATA - 1;
      when "10" => DATA_WDATA <= DATA_RDATA + 1;
      when "11" => DATA_WDATA <= temp_data;       -- CODE_DATA;
      when others => null;
   end case;
end process;

--- DEKODER INSTRUKCII ---
decoder: process(CODE_DATA)
begin
   case CODE_DATA is
    	when X"3E" => instruction <= pointer_inc;
        when X"3C" => instruction <= pointer_dec;
        when X"2B" => instruction <= data_inc;
        when X"2D" => instruction <= data_dec;
        when X"5B" => instruction <= while_start;
        when X"5D" => instruction <= while_end;
        when X"2E" => instruction <= print;
        when X"2C" => instruction <= load;
        when X"23" => instruction <= comment;

        -- od 0 do 9
        when X"30" => instruction <= rwr_num;
        when X"31" => instruction <= rwr_num;
        when X"32" => instruction <= rwr_num;
        when X"33" => instruction <= rwr_num;
        when X"34" => instruction <= rwr_num;
        when X"35" => instruction <= rwr_num;
        when X"36" => instruction <= rwr_num;
        when X"37" => instruction <= rwr_num;
        when X"38" => instruction <= rwr_num;
        when X"39" => instruction <= rwr_num;

        -- od A do F
        when X"41" => instruction <= rwr_char;
        when X"42" => instruction <= rwr_char;
        when X"43" => instruction <= rwr_char;
        when X"44" => instruction <= rwr_char;
        when X"45" => instruction <= rwr_char;
        when X"46" => instruction <= rwr_char;

        when X"00" => instruction <= halt;

        when others => instruction <= other;
   end case;
end process;

-- -------------------------------------------------------------------------- --
--                               FSM AUTOMAT                                  --
-- -------------------------------------------------------------------------- --

--- aktualny stav
pstate_logic: process(CLK, RESET)
begin
   if (RESET = '1') then
      pstate <= S_INIT;
   elsif (CLK'event and CLK = '1') then
      if (EN = '1') then
         pstate <= nstate;        
      end if;      
   end if;
end process;

nstate_logic: process(CODE_DATA, OUT_BUSY, DATA_RDATA, IN_VLD, pstate, cnt, mx_sel, instruction)
begin
   
   -- defaultny stav
   pc_inc <= '0';
   pc_dec <= '0';

   ptr_inc <= '0';
   ptr_dec <= '0';

   cnt_inc <= '0';
   cnt_dec <= '0';

   mx_sel <= "00";

   CODE_EN <= '0';
   DATA_EN <= '0';
   --DATA_RDWR <= '1';
   OUT_WE <= '0';
   IN_REQ <= '0';

   case pstate is
   		-- pociatocny stav
        when S_INIT => 
            nstate <= S_FETCH;
            
        when S_FETCH =>
            CODE_EN <= '1';
            nstate  <= S_DECODE;

        -- instrukcie
        when S_DECODE =>
            case (instruction) is
            	when pointer_inc  => nstate <= S_PTR_INC;
            	when pointer_dec  => nstate <= S_PTR_DEC;
            	when while_start  => nstate <= S_WHILE_START;
            	when while_end    => nstate <= S_WHILE_END;
            	when data_inc => nstate <= S_DATA_INC;
            	when data_dec => nstate <= S_DATA_DEC;
            	when print    => nstate <= S_PRINT;
            	when load     => nstate <= S_LOAD;
            	when rwr_num  => nstate <= S_RWR_NUM;
            	when rwr_char => nstate <= S_RWR_CHAR;
            	when comment  => nstate <= S_COMMENT;
            	when halt     => nstate <= S_HALT;
            	when others   => nstate <= S_OTHERS;
         end case;

        -- inkrementacia hodnoty ukazovatela
        when S_PTR_INC =>
            pc_inc  <= '1';
            ptr_inc <= '1';
            nstate  <= S_FETCH;

        -- dekrementacia hodnoty ukazovatela
        when S_PTR_DEC =>
            pc_inc  <= '1';
            ptr_dec <= '1';
            nstate  <= S_FETCH;

        -- inkrementacia hodnoty bunky
        when S_DATA_INC =>
            DATA_EN   <= '1';
            DATA_RDWR <= '1';
            nstate    <= S_DATA_INC_2;

        when S_DATA_INC_2 =>
      		mx_sel    <= "10";
            DATA_EN   <= '1';
            DATA_RDWR <= '0';            
            pc_inc    <= '1';
            nstate    <= S_FETCH;

        -- dekrementacia hodnoty bunky
        when S_DATA_DEC =>
            DATA_EN   <= '1';
            DATA_RDWR <= '1';
            nstate    <= S_DATA_DEC_2;

        when S_DATA_DEC_2 =>
      		mx_sel    <= "01";
            DATA_EN   <= '1';
            DATA_RDWR <= '0';
            pc_inc    <= '1';
            nstate    <= S_FETCH;

        -- vytisknutie hodnoty aktualnej bunky
      	when S_PRINT =>
      	    DATA_EN   <= '1';
      	    DATA_RDWR <= '1';
            nstate    <= S_PRINT_2;

        when S_PRINT_2 =>
        	if (OUT_BUSY = '1') then
                nstate    <= S_PRINT_2;
            else
                OUT_DATA <= DATA_RDATA;
            	OUT_WE   <= '1';
             	pc_inc   <= '1';
              	nstate   <= S_FETCH;
           	end if;

        -- nacitanie a ulozenie hodnoty bunky
        when S_LOAD => 
       		IN_REQ <= '1';
			if (IN_VLD = '1') then
				nstate <= S_LOAD_2;
			else
				nstate <= S_LOAD;
			end if;

		when S_LOAD_2 =>
			--DATA_WDATA <= IN_DATA;
			mx_sel <= "00";
			DATA_EN <= '1';
			DATA_RDWR <= '0';
			pc_inc <= '1';
			nstate <= S_FETCH;

        -- blokovy komentar
        when S_COMMENT =>
        	-- na vstupe '#'
        	DATA_EN <= '0';
        	CODE_EN <= '1';
        	pc_inc  <= '1';
        	nstate  <= S_COMMENT_2;

        when S_COMMENT_2 =>
        	-- medzistav
            DATA_EN <= '0';
            CODE_EN <= '1';
            nstate <= S_COMMENT_3;

        when S_COMMENT_3 =>
        	-- kym nie je '#', inkrementuj PC
            if (CODE_DATA /= X"23") then 
            	nstate <= S_COMMENT;
            else
            -- na vstupe je zase '#', PC + 1 a FETCH 	
            	DATA_EN <= '0';
            	CODE_EN <= '1';
            	pc_inc  <= '1';
            	nstate  <= S_COMMENT_4;
            end if;

        -- koniec blokovych komentarov
        when S_COMMENT_4 =>
        	nstate <= S_FETCH;

        -- zaciatok vnorenych while cyklov
        when S_WHILE_START =>
            DATA_EN   <= '1';
            DATA_RDWR <= '1';
            pc_inc    <= '1';
            nstate    <= S_WHILE_START_2;

        when S_WHILE_START_2 =>
            if (DATA_RDATA = X"00") then
            	CODE_EN <= '1';
            	cnt_inc <= '1';
                nstate  <= S_WHILE_START_3;
            else
                nstate  <= S_FETCH;
            end if;

        when S_WHILE_START_3 =>
            if (cnt = X"00") then
                nstate <= S_FETCH;
            else
                CODE_EN <= '1';
                nstate  <= S_WHILE_START_4;
            end if;

        when S_WHILE_START_4 =>
            if (CODE_DATA = X"5B") then 
                cnt_inc <= '1';
            elsif (CODE_DATA = X"5D") then
                cnt_dec <= '1';
            end if;
            	pc_inc <= '1';      
                nstate <= S_WHILE_START_3;

        -- koniec vnorenych while cyklov
        when S_WHILE_END =>
            DATA_EN   <= '1';
            DATA_RDWR <= '1';
            nstate    <= S_WHILE_END_1;

        when S_WHILE_END_1 =>
            if (DATA_RDATA = X"00") then
            	pc_inc <= '1';
                nstate <= S_FETCH;
            else
                cnt_inc <= '1';
                pc_dec  <= '1';
                nstate  <= S_WHILE_END_2;
            end if;

        when S_WHILE_END_2 =>
            if (cnt = X"00") then
            	nstate <= S_FETCH;
            else
               	CODE_EN <= '1';
               	nstate  <= S_WHILE_END_3;
            end if;

        when S_WHILE_END_3 =>
            if (CODE_DATA = X"5B") then
            	cnt_dec <= '1';
            elsif (CODE_DATA = X"5D") then
                cnt_inc <= '1';
            end if;   
            nstate <= S_WHILE_END_4;

        when S_WHILE_END_4 =>
            if (cnt = X"00") then
            	pc_inc <= '1';
            else
               	pc_dec <= '1';
            end if;
            nstate <= S_WHILE_END_2;

        -- stav pre cisla 0 az 9
        when S_RWR_NUM =>
        	-- nastavim hodnoty
        	mx_sel    <= "11";
        	DATA_EN   <= '1';
        	pc_inc    <= '1';
        	-- posuv cisla
        	temp_data <= CODE_DATA(3 downto 0) & "0000";
        	nstate    <= S_FETCH;

        -- stav pre pismena A az F
        when S_RWR_CHAR =>
        	-- nastavim hodnoty
        	mx_sel    <= "11";
        	DATA_EN   <= '1';
        	pc_inc    <= '1';
        	-- posuv cisla
        	temp_data <= (CODE_DATA(3 downto 0) + std_logic_vector(conv_unsigned(9, temp_data'LENGTH)(3 downto 0))) & "0000";
        	nstate    <= S_FETCH;

        -- zastavenie programu
        when S_HALT =>
            nstate <= S_HALT;

        -- ostatne
        when S_OTHERS =>
            ptr_inc <= '1';
            nstate  <= S_FETCH;

        when others => null;

   end case;
end process;

end behavioral;
 
