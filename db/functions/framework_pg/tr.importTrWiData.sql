CREATE OR REPLACE FUNCTION tr.importTrWiData()
  RETURNS void AS
$$
DECLARE
  q              record;
  vcd_tr_wi_data bigint;

BEGIN


  -- import TR data;
  FOR q IN SELECT DISTINCT "TestProcedureNumber",
                           "TestProcedureName",
                           "GoalUnits",
                           "Efficiency",
                           "responsiblity",
                           "MinGoal",
                           "MaxGoal",
                           "UpdatedDate"

           FROM "TR_IMP_TMP_WI"

    LOOP

      vcd_tr_wi_data = NULL;


      SELECT cd_tr_wi_data INTO vcd_tr_wi_data FROM tr."TR_WI_DATA" where cd_tr_wi_data = q."TestProcedureNumber";
      -- Insert
      IF NOT FOUND THEN

          INSERT INTO tr."TR_WI_DATA"
            (cd_tr_wi_data,
             ds_test_procedure_name,
             ds_goal_units,
             nr_efficiency,
             ds_responsiblity,
             nr_min_goal,
             nr_max_goal,
             dt_update)
            values (q."TestProcedureNumber",
                    q."TestProcedureName",
                    q."GoalUnits",
                    q."Efficiency",
                    q."responsiblity",
                    q."MinGoal",
                    q."MaxGoal",
                    q."UpdatedDate");
      ELSE
        -- update
            UPDATE tr."TR_WI_DATA"
            SET ds_test_procedure_name=q."TestProcedureName",
                ds_goal_units=q."GoalUnits",
                nr_efficiency=q."Efficiency",
                ds_responsiblity=q."responsiblity",
                nr_min_goal=q."MinGoal",
                nr_max_goal=q."MaxGoal",
                dt_update=q."UpdatedDate"
            WHERE cd_tr_wi_data = q."TestProcedureNumber";

      END IF;

    END LOOP;
END

$$ LANGUAGE plpgsql;

ALTER FUNCTION tr.importTrWiData() SET search_path = pg_catalog, public, rfq, tr, tti;
