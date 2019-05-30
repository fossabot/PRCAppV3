-- Table: public."SYSTEM_DB_UPDATES"

-- DROP TABLE public."SYSTEM_DB_UPDATES";

CREATE TABLE public."SYSTEM_DB_UPDATES"
(
  cd_system_db_updates serial NOT NULL,
  ds_system_db_updates text NOT NULL,
  dt_record timestamp with time zone NOT NULL DEFAULT now(),
  CONSTRAINT "PKSYSTEM_DB_UPDATES" PRIMARY KEY (cd_system_db_updates)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public."SYSTEM_DB_UPDATES"
  OWNER TO devshoesdemo;
GRANT ALL ON TABLE public."SYSTEM_DB_UPDATES" TO postgres;
GRANT ALL ON TABLE public."SYSTEM_DB_UPDATES" TO devshoesdemo;
GRANT ALL ON TABLE public."SYSTEM_DB_UPDATES" TO devshoesdemorole;
GRANT SELECT ON TABLE public."SYSTEM_DB_UPDATES" TO "mbReport";

-- Index: public."IDXSYSTEM_DB_UPDATES001"

-- DROP INDEX public."IDXSYSTEM_DB_UPDATES001";

CREATE UNIQUE INDEX "IDXSYSTEM_DB_UPDATES001"
  ON public."SYSTEM_DB_UPDATES"
  USING btree
  (ds_system_db_updates COLLATE pg_catalog."default");

