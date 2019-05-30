create or replace function is_time(s varchar) returns boolean as $$
begin
  perform s::time without time zone;
  return true;
exception when others then
  return false;
end;
$$ language plpgsql;