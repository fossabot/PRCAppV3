<?php 
header("Cache-Control: no-store, no-cache"); //Tell the browser to not cache this page (don't store it in the internet temp folder).
header("Content-type: text/javascript"); //Let the browser think that this is a Javascript page.

echo ($javascript);


?>
