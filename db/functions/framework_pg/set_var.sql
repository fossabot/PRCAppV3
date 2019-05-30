CREATE OR REPLACE FUNCTION public.set_var
(
    p_var_name varchar,
    p_var_value varchar
)
RETURNS void AS
$$
    DECLARE
    v_cnt integer;
    BEGIN
        SELECT Count (pc.relname)
        into v_cnt
        FROM pg_catalog.pg_class pc, pg_namespace pn
        WHERE pc.relname ='session_var_tbl'
        AND pc.relnamespace=pn.oid
        AND pn.oid=pg_my_temp_schema ();
        
        if v_cnt = 0 then
            execute ' CREATE TEMPORARY TABLE session_var_tbl (var_name varchar (100) not null, var_value varchar (100)) ON COMMIT preserve ROWS';
        end if;
        update session_var_tbl set
        var_value = p_var_value
        where var_name = p_var_name;

        if not FOUND then
            insert into session_var_tbl (var_name, var_value)
            values (p_var_name, p_var_value);
        end if;
    END;
$$
LANGUAGE plpgsql;
