<nav>
  <ul>
    <li class="level-one">
      <a href="<?php print base_url(); ?>trading/trading" class="first-level light-blue <?php if ($this->module_name == 'trading') print 'current'; ?>">Traderion dealing</a>      
    </li>

     <li class="level-one">
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
      <li class="current"><a href="#">TER</a></li>
      <li><a href="#">HAT</a></li>
      <li><a href="#">RIK</a></li>
      <li class="last"><a href="#">0</a></li>
    </ul>
  </div><!-- end date-time-info -->
</nav>

