-- INP: Projekt 1
-- login: xdurov01
-- datum: 26/10/2018


library IEEE;
use IEEE.std_logic_1164.all;
use IEEE.std_logic_arith.all;
use IEEE.std_logic_unsigned.all;

entity ledc8x8 is
port (
    RESET, SMCLK  :  in std_logic;
    ROW           :  out std_logic_vector (0 to 7);
    LED           :  out std_logic_vector (0 to 7)
);
end ledc8x8;

architecture main of ledc8x8 is
    signal clock_e : std_logic;                      -- clock enable 1
    signal ce      : std_logic;                      -- clock enable 2
    signal ce_cnt  : std_logic_vector(0 to 11);      -- clock enable counter; vypocet 7372800/256/8 = DEC(3600) = BIN(111000010000)
    signal r_cnt   : std_logic_vector(7 downto 0);   -- riadky
    signal s_state : std_logic_vector(0 to 20);      -- vypocet doby jedneho stavu 7372800/4 = DEC(1843200) = BIN(111000010000000000000)
    signal state   : std_logic_vector(3 downto 0);   -- kod stavu
begin

-- citac
gen_ce: process(RESET, SMCLK)
    begin
    -- asynchronne resetovanie citaca na nulu
        if RESET = '1' then
            ce_cnt <= "000000000000";
        elsif SMCLK'event and SMCLK = '1' then
            if ce_cnt = "111000010000" then
                ce_cnt <= "000000000000";
            else
                ce_cnt <= ce_cnt + 1;
            end if;
        end if;
    end process gen_ce;

    -- tu nastavim clock enable
    clock_e <= '1' when ce_cnt = "111000010000" else '0';

-- zmena stavu
switch_state: process(RESET, SMCLK)
    begin
    -- asynchronne resetovanie citaca na nulu
        if RESET = '1' then 
            s_state <= "000000000000000000000";
        elsif SMCLK'event and SMCLK = '1' then
            -- ak s_state dosiahne tuto hodnotu resetuje na nulu a bude pocitat odznova
            if s_state = "111000010000000000000" then
                s_state <= "000000000000000000000";
            else
                s_state <= s_state + 1;
            end if;
        end if;
    end process switch_state;

    -- signal ce musi byt '1' az ked s_state napocita danu hodnotu
    ce <= '1' when s_state = "111000010000000000000" else '0';

-- rotacny register pre riadky
rot_r: process(RESET, SMCLK, clock_e)   
    begin
    -- asynchronne resetovanie na prvy riadok
        if RESET = '1' then
            r_cnt <= "10000000";
        elsif SMCLK'event and SMCLK = '1' and clock_e = '1' then
            -- tu rotujem riadky
            r_cnt <= r_cnt(0) & r_cnt(7 downto 1);
        end if;
    end process rot_r;

-- rotacny register pre stavy
rot_s: process(RESET, SMCLK, ce)   
    begin
    -- asynchronne resetovanie na prvy riadok
        if RESET = '1' then
            state <= "1000";
        elsif SMCLK'event and SMCLK = '1' and ce = '1' then
            -- tu rotujem stavy
            state <= state(0) & state(3 downto 1);
        end if;
    end process rot_s;

-- dekoder (stavy & riadky)
decoder: process(r_cnt, state)
    begin
    -- riadok po riadku, zhora dole zobrazi inicialy na displeji 8x8
    -- svietiaca LED dióda == 0, nesvietiaca == 1 
    if state = "1000" then
        case r_cnt is
        -- na displeji sa zobrazi 'R'
            when "10000000" => LED <= "00000111";
            when "01000000" => LED <= "01111011";
            when "00100000" => LED <= "01111011";
            when "00010000" => LED <= "01111011";
            when "00001000" => LED <= "00000111";
            when "00000100" => LED <= "01111011";
            when "00000010" => LED <= "01111101";
            when "00000001" => LED <= "01111110";
            when others => LED <= "11111111";
        end case;
    -- na displeji sa zobrazi 'D'
    elsif state = "0010" then
        case r_cnt is
            when "10000000" => LED <= "00000011";
            when "01000000" => LED <= "01111101";
            when "00100000" => LED <= "01111110";
            when "00010000" => LED <= "01111110";
            when "00001000" => LED <= "01111110";
            when "00000100" => LED <= "01111110";
            when "00000010" => LED <= "01111101";
            when "00000001" => LED <= "00000011";
            when others => LED <= "11111111";
        end case;
    -- displej nesvieti
    else
        case r_cnt is
            when "10000000" => LED <= "11111111";
            when "01000000" => LED <= "11111111";
            when "00100000" => LED <= "11111111";
            when "00010000" => LED <= "11111111";
            when "00001000" => LED <= "11111111";
            when "00000100" => LED <= "11111111";
            when "00000010" => LED <= "11111111";
            when "00000001" => LED <= "11111111";
            when others => LED <= "11111111";
        end case;
    end if;
    end process decoder;

-- priradim medzisignal riadkov
ROW <= r_cnt;

end main;
