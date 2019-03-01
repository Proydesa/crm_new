<!--#INCLUDE file="include/i_connection.asp" -->
<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname){
	if (! window.focus)return true;
		var href;
		if (typeof(mylink) == 'string')
		href=mylink;
	else
		href=mylink.href;
		incidente=window.open(href, windowname, 'width=500,height=300,scrollbars=yes,left=300,top=200');
	return false;
}
//-->;

</SCRIPT>

<?php
/*
Dim objFSO

Dim objTextStream

const strFileName = "F:\WEBROOT\proydesa\www\hd\feriados.txt"
const fsoForReading = 1

'STATUS------------
'1 = INC - NO CONFORMADO
'2 = A - ABIERTO
'3 = ACT - ACT
'4 = COB - CIERRE OBSERVADO
'5 = PND - PENDIENTE
'6 = CEC - CIERRE EN CURSO
'7 = CA - CIERRE AUTOMATICO
'8 = CO - CIERRE OPERADOR
'9 = CV - CIERRE VENCIDO
'10 = CNC - CIERRE NO CONFORMADO
'------------------



'Actualizamos numero de pagina
If Request.QueryString("pag")<>"" Then
   Session("pagina")=Request.QueryString("pag")
Else
   Session("pagina")=1
End If

'Constantes ADO VBScript
Const adCmdText = &H0001
Const adOpenStatic = 3

'====================================================================================
If Session("user")<> "" Then
	If Session("user") <> "()" Then
		sSQL = Replace(sSQL,"WHERE","WHERE tblUsers_1.sid=" & Session("user") & " AND ")
	End If
End If
'====================================================================================
If Session("rep")<> "" Then
	If Session("rep") <> "()" Then
		sSQL = Replace(sSQL,"WHERE","WHERE tblUsers.sid=" & Session("rep") & " AND ")
	End If
End If
'====================================================================================
If Session("acad")<> "" Then
	If Session("acad") <> "()" Then
		sSQL = Replace(sSQL,"WHERE","WHERE departments.department_id=" & Session("acad") & " AND ")
	End If
End If
'====================================================================================
If Session("prior") <> "" Then
	If Session("prior") <> "()" Then
		sSQL = Replace(sSQL,"WHERE","WHERE priority.priority_id =" & Session("prior") & " AND ")
	End If
End If
'====================================================================================
If Session("status") <> "" Then
	If Session("status") <> "()" Then
		sSQL = Replace(sSQL,"WHERE","WHERE status.status_id =" & Session("status") & " AND ")
	End If
End If
'====================================================================================
If Session("cat") <> "" Then
	If Session("cat") <> "()" Then
		sSQL = Replace(sSQL,"WHERE","WHERE categories.category_id =" & Session("cat") & " AND ")
	End If
End If
'====================================================================================

Set RS=Server.CreateObject("ADODB.RecordSet")
RS.Open sSQL,oCon_HelpDesk,adOpenStatic 

'resultados por pagina a elegir arbitrariamente
num_registros = 10

If Not (RS.BOF And RS.EOF) Then
	'Dimensionamos las paginas y determinamos la pagina actual
	RS.PageSize=num_registros
	RS.AbsolutePage=Session("pagina")
Else
	Session("status")=""
	Session("prior")=""
	Session("acad")=""
	Session("rep")=""
	Session("user")=""
	Session("cat")=""
	%>
	<div align="center"class="reg">No hay resultados<br />
	<a target="_parent" href="helpdesk.asp">Volver</a></div>
	<%
	response.End
End If
%>
*/
?>
<h2 class="ui-widget">Red Proydesa - Reporte Help Desk</h2>

<table class="ui-widget">
	<tr class="ui-widget-header">
    <th >ID</th>
		<th>Academia<br />
      <select style="width:150px;font-size:10px" name="ad" onChange="top.location.href = this.options[this.selectedIndex].value;" ID="Select2">
        <option value="helpdesk.asp?acad=()">Todas</option>
        <?php foreach($academias as $academia): ?>
					<option value="<?= $academia['id']; ?>"><?= $academia['name']; ?></option>
				<?php endforeach; ?>
      </select>
    </th>
    <th>Título</th>
    <th>Usuario<br />
      <select style="width:150px;font-size:10px" name="ad" onChange="top.location.href = this.options[this.selectedIndex].value;" ID="Select1">
        <option value="helpdesk.asp?user=()">Todos</option>
        <%=ArmarCombo("Usuarios")%>
      </select>
    </th>
    <th>Representante<br />
      <select style="width:130px;font-size:10px" name="ad" onChange="top.location.href = this.options[this.selectedIndex].value;" ID="Select3">
        <option value="helpdesk.asp?rep=()">Todos</option>
        <%=ArmarCombo("Representantes")%>
      </select>
    </th>
    <th>Fecha de Ingreso</th>
    <th>Prioridad<br />
      <select style="width:75px;font-size:10px" name="ad" onChange="top.location.href = this.options[this.selectedIndex].value;" ID="Select4">
        <option value="helpdesk.asp?prior=()">Todas</option>
        <%=ArmarCombo("Prioridades")%>
      </select>
    </th>
    <th>Estado<br />
      <select style="width:75px;font-size:10px" name="ad" onChange="top.location.href = this.options[this.selectedIndex].value;" ID="Select5">
        <option value="helpdesk.asp?status=()">Todos</option>
        <%=ArmarCombo("Estados")%>
      </select>
    </th>
    <th>Categoría<br />
      <select style="width:130px;font-size:10px" name="ad2" onChange="top.location.href = this.options[this.selectedIndex].value;" id="ad">
        <option value="helpdesk.asp?cat=()">Todas</option>
        <%=ArmarCombo("Categorias")%>
      </select>
    </th>
		<th>Antig&uuml;edad<br> (Horas)</th>
    <th>HD</th>
  </tr>
	<?php if($rows): ?>
		<?php foreach($rows as $row): ?>
			<tr class="ui-widget-content">
				<td><?= $row["id"]; ?></td>
				<td><?= $LMS->GetField("mdl_proy_academy", "name", $row["acad"]); ?></td>
				<td><?= $row["subject"]; ?></a></td>
				<td><?= $LMS->GetField("mdl_user", "CONCAT(firstname, ' ', lastname)", $row["userid"]); ?></td>
				<td><?= $LMS->GetField("mdl_user", "CONCAT(firstname, ' ', lastname)", $row["assignto"]); ?></td>
				<td><?= date("d-m-Y h:m:s", $row['startdate']); ?></td>
				<td><?= $row["priority"]; ?></td>
				<td><?= $row["status"]; ?></td>
				<td><?= $row["category"]; ?></td>
				<td><?= CalcularHoras($row['startdate'], time()); ?></td>
				<td><a target="_blank" href="http://localhost/hulk/hd.php?v=details&id=<?= $row["id"]; ?>">>></a></td>
		  </tr>
		<?php endforeach; ?>
	<?php endif; ?>
	<tr>
		<td colspan="11" align="right">Página
			<%While i<RS.PageCount
				i=i+1
				If i=int(Session("pagina")) Then%>
					<a target="_self" href="body.asp?pag=<%=i%>"><b><%=i%></b></a>
				<%Else%>
					<a target="_self" href="body.asp?pag=<%=i%>"><%=i%></a>
				<%End If
			Wend%>
		</td>
  </tr>
</table>

<?php

//********************************************************************************************************
//Calcula las horas que un incidente está abierto (sin fines de semana ni feriados)
//********************************************************************************************************

function CalcularHoras($inicio, $fin){
	
	//Calculo Restar Fines de Semana=====================================
	/*
	echo date("N",$HoraInicial)."<br />";
	$i = date("d-m-Y h:m:s", $HoraInicial);
	$HoraFinal = date("d-m-Y h:m:s", $HoraFinal);
	
	echo $i."<br />";
	echo $HoraFinal;
	*/
	/*
	'WeekDay
	'1 = vbSunday - Sunday (default)
	'2 = vbMonday - Monday
	'3 = vbTuesday - Tuesday
	'4 = vbWednesday - Wednesday
	'5 = vbThursday - Thursday
	'6 = vbFriday - Friday
	'7 = vbSaturday - Saturday
	*/

	//echo strtotime($i)."<br />";	
	//echo strtotime(date("Y-m-d h:m:s", strtotime($i))." +1 day");	
	$i = $inicio;
	
	while ($i < $fin){
		if(date("N", $i) < 6){ //Si no es sábado (6) o domingo (7)
			//Chequeo si es el día que se creó el incidente o el que estoy ejecutando la pagina
			if((date("d",$i) == date("d",$inicio)) && (date("m",$i) == date("m",$inicio)) && (date("Y",$i) == date("Y", $inicio))){
				$total += 86400 - ((date("h", $inicio)*3600) + (date("m",$inicio)*60) + (date("s",$inicio))); 
			}elseif((date("d",$i) == date("d",$fin)) && (date("m",$i) == date("m",$fin)) && (date("Y",$i) == date("Y", $fin))){
				$total += (date("h", $fin)*3600) + (date("m",$fin)*60) + (date("s",$fin));
			}else{
				$total += 86400; 
			}
		}
		/*
		Switch (){
			Case 1: //Domingo
				//Chequeo si es el día que se creó el incidente o el que estoy ejecutando la pagina
				If((mday($i) == mday($HoraInicial)) And (mon($i) == mon($HoraInicial)) And (year($i) == year($HoraInicial))){
					$HorasARestar += (24 - hours($HoraInicial));
				}ElseIf((mday($i) == mday(HoraFinal)) And (mon($i) == mon($HoraFinal)) And (year($i) == year($HoraFinal))){
					$HorasARestar += hours($HoraFinal);
				}Else{
					$HorasARestar += 24;
				}
				break;
			
			default:
				
				break;
		}
		*/
		//Agrego un día
		$i += 86400;
		//echo "*".date("d-m-Y", strtotime($i))."*<br />";
		//echo "HorasARestar = #".$HorasARestar."#<br />";
	}
	$horas = floor($total/3600);
	$minutos = floor(($total-($horas*3600))/60);
	$segundos = $total-($horas*3600)-($minutos*60);
	echo $horas.'h:'.$minutos.'m:'.$segundos.'s';	
	
}
/*
==========================================================================

'Restar Feriados (sacados de un txt)=======================================
Set objFSO = Server.CreateObject("Scripting.FileSystemObject")

If objFSO.FileExists(strFileName) Then
	'El archivo es encontrado y mostramos el contenido
	Set objTextStream = objFSO.OpenTextFile(strFileName, fsoForReading)
	'Paso por cada línea de archivo para chequear la fecha
	While Not objTextStream.AtEndOfStream
		'Chequeo si la fecha está entre la fecha de ingreso y ahora
		'Saco Dia, Mes y Año
		Temp = objTextStream.ReadLine()
		Dia = Int(Left(Temp,2))
		Mes = Int(Mid(Temp,4,2))
		Ano = Int(Right(Temp,4))
		'Chequeo el año
		If Ano >= Year(HoraInicial) And Ano <= Year(HoraFinal) Then
			If Mes >= Month(HoraInicial) And Mes <= Month(HoraFinal) Then
				If Dia >= Day(HoraInicial) And Dia <= Day(HoraFinal) Then
					'Chequeo si es el día que se creó el incidente o el que estoy ejecutando la pagina
					If (Dia = Day(HoraInicial)) And (Mes = Month(HoraInicial)) And (Ano = Year(HoraInicial)) Then
						HorasARestar = HorasARestar + (24 - Hour(HoraInicial))
					ElseIf (Dia = Day(HoraFinal)) And (Mes = Month(HoraFinal)) And (Ano = Year(HoraFinal)) Then
						HorasARestar = HorasARestar + Hour(HoraFinal)
					Else
						HorasARestar = HorasARestar + 24
					End If
				End If
			End If
		End If
	Wend
	objTextStream.Close
	Set objTextStream = Nothing
Else
	'el archivo no existe
	Response.Write "No se encontró el archivo " & strFileName
End If

'Clean up
Set objFSO = Nothing
'================================================================================

CalcularHoras = DateDiff("H",HoraInicial,HoraFinal) - HorasARestar

End Function


'********************************************************************************************************
'********************************************************************************************************
Function ArmarCombo(ByVal Tipo)

sSQLu = "SELECT DISTINCT #Tipo#" & _
	    "FROM dbo.tblUsers INNER JOIN dbo.priority INNER JOIN dbo.problems INNER JOIN " & _
        "dbo.tblUsers tblUsers_1 ON dbo.problems.uid = tblUsers_1.uid ON dbo.priority.priority_id = dbo.problems.priority " & _
        "INNER JOIN dbo.status ON dbo.problems.status = dbo.status.status_id ON dbo.tblUsers.sid = dbo.problems.rep INNER JOIN " & _
        "dbo.departments ON dbo.problems.department = dbo.departments.department_id " & _
        "INNER JOIN dbo.categories ON dbo.problems.category = dbo.categories.category_id " & _
	    "WHERE (dbo.problems.status IN (1, 2, 3, 4)) " & _
	    "ORDER BY #Orden# ASC"
	    
Select Case Tipo
	Case "Usuarios"
		sSQLu = Replace(sSQLu, "#Tipo#", "tblUsers_1.sid as ID, tblUsers_1.fname AS [user] ")
		sSQLu = Replace(sSQLu, "#Orden#", "tblUsers_1.fname")
	Case "Representantes"
	    sSQLu = Replace(sSQLu, "#Tipo#", "tblUsers.sid as ID, tblUsers.fname AS [user] ")
	    sSQLu = Replace(sSQLu, "#Orden#", "tblUsers.fname")
	Case "Academias"
	    sSQLu = Replace(sSQLu, "#Tipo#", "departments.department_id as ID, departments.dname AS acad ")
	    sSQLu = Replace(sSQLu, "#Orden#", "departments.dname")
	Case "Prioridades"
	    sSQLu = Replace(sSQLu, "#Tipo#", "priority.priority_id as ID, priority.pname AS prior ")
	    sSQLu = Replace(sSQLu, "#Orden#", "priority.priority_id")
	Case "Estados"
	    sSQLu = Replace(sSQLu, "#Tipo#", "status.status_id as ID, status.sname AS status ")
	    sSQLu = Replace(sSQLu, "#Orden#", "status.sname")
	Case "Categorias"
		sSQLu = Replace(sSQLu, "#Tipo#", "categories.category_id as ID, categories.cname AS category ")
	    sSQLu = Replace(sSQLu, "#Orden#", "categories.cname")
End Select

'====================================================================================
If Session("user")<> "" Then
	If Session("user") <> "()" Then
		sSQLu = Replace(sSQLu,"WHERE","WHERE tblUsers_1.sid=" & Session("user") & " AND ")
	End If
End If
'====================================================================================
If Session("rep")<> "" Then
	If Session("rep") <> "()" Then
		sSQLu = Replace(sSQLu,"WHERE","WHERE tblUsers.sid=" & Session("rep") & " AND ")
	End If
End If
'====================================================================================
If Session("acad")<> "" Then
	If Session("acad") <> "()" Then
		sSQLu = Replace(sSQLu,"WHERE","WHERE departments.department_id=" & Session("acad") & " AND ")
	End If
End If
'====================================================================================
If Session("prior") <> "" Then
	If Session("prior") <> "()" Then
		sSQLu = Replace(sSQLu,"WHERE","WHERE priority.priority_id =" & Session("prior") & " AND ")
	End If
End If
'====================================================================================
If Session("status") <> "" Then
	If Session("status") <> "()" Then
		sSQLu = Replace(sSQLu,"WHERE","WHERE status.status_id =" & Session("status") & " AND ")
	End If
End If
'====================================================================================
If Session("cat") <> "" Then
	If Session("cat") <> "()" Then
		sSQLu = Replace(sSQLu,"WHERE","WHERE categories.category_id =" & Session("cat") & " AND ")
	End If
End If
'====================================================================================

Set RSu=Server.CreateObject("ADODB.RecordSet")
RSu.Open sSQLu,oCon_HelpDesk,adOpenStatic

While Not RSu.EOF
	Select Case Tipo
		Case "Usuarios"
			If Session("user") = "" Then
				Session("user") = 0 
			End If
			If Int(Session("user")) = RSu("ID") Then%>
				<option selected value="helpdesk.asp?user=<%=RSu("ID")%>"><%=RSu("user")%></option>
			<%Else%>
				<option value="helpdesk.asp?user=<%=RSu("ID")%>"><%=RSu("user")%></option>
			<%End If
			If Session("user") = 0 Then
				Session("user") = ""
			End If
		Case "Representantes"
			If Session("rep") = "" Then
				Session("rep") = 0
			End If
			If Int(Session("rep")) = RSu("ID") Then%>
				<option selected value="helpdesk.asp?rep=<%=RSu("ID")%>"><%=RSu("user")%></option>
			<%Else%>
				<option value="helpdesk.asp?rep=<%=RSu("ID")%>"><%=RSu("user")%></option>
			<%End If
			If Session("rep") = 0 Then
				Session("rep") = ""
			End If
		Case "Academias"
			If Session("acad") = "" Then
				Session("acad") = 0
			End If
			If Int(Session("acad")) = RSu("ID") Then%>
				<option selected value="helpdesk.asp?acad=<%=RSu("ID")%>"><%=RSu("acad")%></option>
			<%Else%>
				<option value="helpdesk.asp?acad=<%=RSu("ID")%>"><%=RSu("acad")%></option>
			<%End If
			If Session("acad") = 0 Then
				Session("acad") = ""
			End If
		Case "Prioridades"
			If Session("prior") = "" Then
				Session("prior") = 0
			End If
			If Int(Session("prior")) = RSu("ID") Then%>
				<option selected value="helpdesk.asp?prior=<%=RSu("ID")%>"><%=RSu("prior")%></option>
			<%Else%>
				<option value="helpdesk.asp?prior=<%=RSu("ID")%>"><%=RSu("prior")%></option>
			<%End If
			If Session("prior") = 0 Then
				Session("prior") = ""
			End If
		Case "Estados"
			If Session("status") = "" Then
				Session("status") = 0
			End If
			If Int(Session("status")) = RSu("ID") Then%>
				<option selected value="helpdesk.asp?status=<%=RSu("ID")%>"><%=RSu("status")%></option>
			<%Else%>
				<option value="helpdesk.asp?status=<%=RSu("ID")%>"><%=RSu("status")%></option>
			<%End If
			If Session("status") = 0 Then
				Session("status") = ""
			End If
		Case "Categorias"
			If Session("cat") = "" Then
				Session("cat") = 0
			End If
			If Int(Session("cat")) = RSu("ID") Then%>
				<option selected value="helpdesk.asp?cat=<%=RSu("ID")%>"><%=RSu("category")%></option>
			<%Else%>
				<option value="helpdesk.asp?cat=<%=RSu("ID")%>"><%=RSu("category")%></option>
			<%End If
			If Session("cat") = 0 Then
				Session("cat") = ""
			End If
		End Select
	RSu.MoveNext
Wend
RSu.Close

End Function


'********************************************************************************************************
'********************************************************************************************************
Function IncidentesPorUsuario

sSQLiu = "SELECT tblUsers.fname as [user], status.sname as status, count(*) as incidentes " & _
	     "FROM dbo.tblUsers INNER JOIN dbo.priority INNER JOIN dbo.problems INNER JOIN " & _
         "dbo.tblUsers tblUsers_1 ON dbo.problems.uid = tblUsers_1.uid ON dbo.priority.priority_id = dbo.problems.priority " & _
         "INNER JOIN dbo.status ON dbo.problems.status = dbo.status.status_id ON dbo.tblUsers.sid = dbo.problems.rep INNER JOIN " & _
         "dbo.departments ON dbo.problems.department = dbo.departments.department_id " & _
	     "WHERE (dbo.problems.status IN (1, 2, 3, 4)) " & _
	     "GROUP BY tblUsers.fname, status.sname " & _
	     "ORDER BY tblUsers.fname, status.sname"
	     
Set RSiu=Server.CreateObject("ADODB.RecordSet")
RSiu.Open sSQLiu,oCon_HelpDesk,adOpenStatic

TotalRegistros = RSiu.RecordCount

While Not RSiu.EOF
	Registro = Registro + 1
	If UserT <> RSiu("user") Then
		response.Write "<tr bgcolor=#ffffff>"
		UserT = RSiu("user")
		%>
<td valign="top" class="reg"><%=UserT%></td>
<td class="reg"><%=RSiu("status") & " -> " & RSiu("incidentes")%>
  <%
		'Chequeo el registro que sigue para ver si cierro el td
		If Registro < TotalRegistros-1 Then
			RSiu.MoveNext
			If RSiu("user") = UserT Then
				While RSiu("user") = UserT And Not RSiu.EOF
					Response.Write "<br />" & RSiu("status") & " -> " & RSiu("incidentes")
					RSiu.MoveNext
				Wend
			End If
			RSiu.MovePrevious
		End If
	End If
	Response.Write "</td></tr>"
	RSiu.MoveNext
Wend
RSiu.Close
End Function
*/
?>
