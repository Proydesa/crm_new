<?php
include "config.php";
$date= date();
$mail = new H_Mail();
$mail->Subject(utf8_decode(''));
$mail->Body(utf8_decode('<table class="ui-widget" style="width:100%;" align="center">
<thead class="ui-widget-header">
    <tr><th>1 Inasistencia</th>
    <th>2 Inasistencia</th>
    <th>3 Inasistencia</th>
</tr></thead>
<tbody>

                        <tr data-userid="" class="ui-widget-content" data-username="">
    <td style="width:33%" class="ui-widget-content textCenter"><a href="contactos.php?v=view&amp;id=53729">Amicucci Alejandro<br><mark></mark></a></td>
    <td style="width:33%" class="ui-widget-content textCenter"><br>&nbsp;</td>
    <td style="width:33%" class="ui-widget-content textCenter"><a href="contactos.php?v=view&amp;id=100136">Cambero Maldonado Luis Gabriel<br><mark></mark></a></td>
</tr>
                        <tr data-userid="" class="ui-widget-content" data-username="">
    <td style="width:33%" class="ui-widget-content textCenter"><a href="contactos.php?v=view&amp;id=57391">Leiva Guillermo Enrique<br><mark></mark></a></td>
    <td style="width:33%" class="ui-widget-content textCenter"><br>&nbsp;</td>
    <td style="width:33%" class="ui-widget-content textCenter"><br>&nbsp;</td>
</tr>
                    </tbody>
</table>'));
$mail->AddAddress("mdiaz@proydesa.org", "Pablo");
$mail->CharSet = 'UTF-8';
$mail->Send();


?>