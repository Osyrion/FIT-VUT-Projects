-- INP: Projekt 1
-- login: xdurov01
-- datum: 10/10/2017

library IEEE;
use IEEE.std_logic_1164.all;
use IEEE.std_logic_arith.all;
use IEEE.std_logic_unsigned.all;

entity ledc8x8 is
port (
    RESET, SMCLK   :   in std_logic;
    ROW            :   out std_logic_vector (0 to 7);
    LED            :   out std_logic_vector (0 to 7)
);
end ledc8x8;

architecture main of ledc8x8 is

    signal clock_e :   std_logic;                     -- clock enable
    signal ce_cnt  :   std_logic_vector(7 downto 0);  -- clock enable counter
    signal r_cnt   :   std_logic_vector(7 downto 0);  -- row counter

begin

    ROW <= r_cnt;

-- citac
    gen_ce: process(RESET, SMCLK)
    begin
    -- asynchronne resetovanie citaca na nulu
        if RESET = '1' then 
            ce_cnt <= "00000000";
        elsif SMCLK'event and SMCLK = '1' then
            ce_cnt <= ce_cnt + 1;
        end if;
    end process gen_ce;

    clock_e <= '1' when ce_cnt = "00000000" else '0';

-- rotacny register
    rot_r: process(RESET, SMCLK, clock_e)   
    begin
    -- asynchronne resetovanie na prvy riadok
        if RESET = '1' then
            r_cnt <= "10000000";
        elsif SMCLK'event and SMCLK = '1' and clock_e = '1' then
            r_cnt <= r_cnt(0) & r_cnt(7 downto 1);
        end if;
    end process rot_r;

-- dekoder
    decoder: process(r_cnt)
    begin 
        case r_cnt is
        -- riadok po riadku, zhora dole zobrazi inicialy na displeji 8x8
        -- svietiaca LED dióda == 0, nesvietiaca == 1
            when "10000000" => LED <= "00001111";
            when "01000000" => LED <= "01101111";
            when "00100000" => LED <= "00011111";
            when "00010000" => LED <= "01101111";
            when "00001000" => LED <= "01110001";
            when "00000100" => LED <= "11110110";
            when "00000010" => LED <= "11110110";
            when "00000001" => LED <= "11110001";
            when others => LED <= "11111111";
        end case;
    end process decoder;

end main;