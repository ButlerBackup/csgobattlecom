<h1 class="title">{L:SERVERS_TITLE}</h1>
<style>
.main_body
{
	min-height:770px;
}
</style>
<div class="left_Bx">
<h3>Los Angeles Servers</h3>
<table width="100%" border="0">
  <tr class="headng">
    <td>Rank</td>
    <td>Gm</td>
    <td>Server Name</td>
    <td>Players</td>
    <td>Loc.</td>
    <td>P:Port</td>
    <td>Server Map</td>
  </tr>
 <?php for($i=1;$i<13;$i++)
 { ?>
  <tr class="headng1">
    <td><?php echo $i; ?></td>
    <td><img src="/app/public/images/img/gmpic.png" alt=""></td>
    <td>Lorem ispum...<span>JOIN</span></td>
    <td>40/65</td>
    <td>Loc.</td>
    <td>216.52.148.47:27015</td>
    <td>ze_pirates..</td>
  </tr>
  <?php } ?>
</table>


</div>

<div class="right_Bx">
<h3>New York Servers</h3>
<table width="100%" border="0">
  <tr class="headng">
    <td>Rank</td>
    <td>Gm</td>
    <td>Server Name</td>
    <td>Players</td>
    <td>Loc.</td>
    <td>P:Port</td>
    <td>Server Map</td>
  </tr>
 <?php for($i=1;$i<13;$i++)
 { ?>
  <tr class="headng1">
    <td><?php echo $i; ?></td>
    <td><img src="/app/public/images/img/gmpic.png" alt=""></td>
    <td>Lorem ispum...<span>JOIN</span></td>
    <td>40/65</td>
    <td>Loc.</td>
    <td>216.52.148.47:27015</td>
    <td>ze_pirates..</td>
  </tr>
  <?php } ?>
</table>
</div>



<a href="a"><img src="http://cache.www.gametracker.com/server_info/173.234.245.42:27020/b_350x20_C692108-381007-FFFFFF-000" alt="a"></a>



<?php

if (count($this->servers) > 0) {

    foreach ($this->servers as $server) {

        ?>

        <div>

            <div>

                {L:SERVERS_ID}: #<?php echo $server->id; ?>

            </div>

            <div>

                {L:SERVERS_NAME}: <?php echo $server->name; ?>

            </div>

            <div>

                {L:SERVERS_ADDR}: <?php echo $server->addr; ?>

            </div>

            <div>

                {L:SERVERS_PICTURE}: <img src="<?php echo $server->pic; ?>" alt="<?php echo $server->name; ?>"/>

            </div>

            <div>

                <textarea class="serv-code">

                    <?php echo htmlspecialchars('<a href="' . $server->addr . '"><img src="' . $server->pic . '" alt="' . $server->name . '"></a>'); ?>

                </textarea>

            </div>

        </div>

    <?php

    }

} else {

    echo '{L:SERVERS_NO_SERVER}';

}

?>