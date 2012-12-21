<script> var S = 0; </script>
<script> var PNL_CCY = 1 ; </script> 

<nav>
  <ul>
    <li class="level-one">
      <a href="<?php print base_url(); ?>trading/trading" class="first-level light-blue <?php if ($this->module_name == 'trading') print 'current'; ?>">Traderion dealing</a>      
    </li>

     <li class="level-one" id="clients_menu">
      <a href="<?php print base_url(); ?>trading/clients" class="first-level light-blue <?php if ($this->module_name == 'clients') print 'current'; ?>">Clients</a>
    </li>

     <li class="level-one">
      <a href="<?php print base_url(); ?>trading/blotters" class="first-level light-blue <?php if ($this->module_name == 'blotters') print 'current'; ?>">Blotters</a>
    </li>
    
    <li class="level-one">
      <a href="<?php print base_url(); ?>econ" class="first-level light-blue <?php if ($this->module_name == 'econ') print 'current'; ?>">Econ</a>
    </li>
    
    <li class="level-one">
      <a href="<?php print base_url(); ?>news" class="first-level light-blue <?php if ($this->module_name == 'news') print 'current'; ?>">News</a>
    </li>
  </ul>
  <div class="date-time-info trading-page">
    <ul>
      <li class="first light-blue"><a href="#">PnL</a></li>
      <li id='cr1' class="current"><a href="#" onclick='makePNL(1);'>TER</a></li>
      <li id='cr3'><a href="#" onclick='makePNL(3);'>HAT</a></li>
      <li id='cr2'><a href="#" onclick='makePNL(2);'>RIK</a></li>
      <li class="last"><a href="#" id='pnlval'>0</a></li>
    </ul>
  </div><!-- end date-time-info -->
</nav>

<script>
	
function makePNL(v) {
	
	PNL_CCY = v ; 
	$('.current').removeClass('current');
	$('#cr'+v).addClass('current');
}

</script>

