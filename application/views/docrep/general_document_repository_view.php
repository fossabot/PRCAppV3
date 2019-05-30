<div id = 'mainDocRep'>
</div>


<script>

   var docRepRelCode = <?php echo ($code) ?>;
   var docRepRelId   = <?php echo ($id) ?>;

   workvar = $('#mainDocRep').docRepStart({id: docRepRelId, code: docRepRelCode});
   //console.log(workvar);
   workvar.init();
   

</script>