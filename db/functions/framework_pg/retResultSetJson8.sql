CREATE OR REPLACE FUNCTION public.retResultSetJson8
(
    p_select  text,
    p_diflabels text
)

RETURNS json AS
$$
   var rowaa = [];

   if (p_diflabels == '' ) {
      rowaa = plv8.execute(p_select);
      return rowaa;
   } 

   var labels = JSON.parse(p_diflabels);

   var plan = plv8.prepare( p_select );
   var cursor = plan.cursor( );

   while (row = cursor.fetch()) {
      var newRow = {};
      for (var key in labels) {
         newRow[labels[key]] = row[key]; 
      }

      rowaa.push(newRow);
     
   }
   cursor.close();
   plan.free();


  return  rowaa;
$$
LANGUAGE plv8 IMMUTABLE STRICT;

