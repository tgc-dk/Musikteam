<?php
// TODO: Load the layout settings from the database
?>
<table width="500" border="0">
  <tr>
    <td>
	<div id="layout_edit">
		<div class="sub_menu">

<div onclick="Afstande()" id="Afstande_menu">Afstande</div> 
<div  onclick="Tekst_2()" id="Tekst_2_menu">Tekst</div> 
<div   onclick="Overskrifter()" id="Overskrifter_menu">Overskrifter</div> 
<div  onclick="Kommentarer()" id="Kommentarer_menu">Kommentarer</div> 
<div  onclick="Gem_layout()" id="Gem_layout_menu">Gem layout</div>		</div>

	  <div id="Afstande">
			<table width="500" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="133" height="20" bgcolor="#f4f4f4"><strong>Afstande mellem:</strong> </td>
				<td width="16" bgcolor="#f4f4f4"><a href="#"></a></td>
				<td width="12" bgcolor="#f4f4f4">
					<form id="form3" name="form1" method="post" action="">
						<label for="textfield"></label>
					</form>
				</td>
				<td width="300" bgcolor="#f4f4f4"><a href="#"></a></td>
				<td width="39" bgcolor="#f4f4f4">&nbsp;</td>
			</tr>
			<tr>
				<td>Takter:</td>
				<td><a href="#"><img src="img/minus.gif" name="skalam" width="16" height="23" border="0" align="top" id="skalam" /></a></td>
				<td>
					<label for="label"></label>
					<input name="Takter" type="text" id="label" size="2" maxlength="2" value="10">
				</td>
				<td><a href="#"><img src="img/plus.gif" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td bgcolor="#f4f4f4">Linier:</td>
				<td bgcolor="#f4f4f4"><a href="#"><img src="img/minus.gif" name="skalam" width="16" height="23" border="0" align="top" id="skalam" /></a></td>
				<td bgcolor="#f4f4f4"><input name="textfield3" type="text" value="5" size="2" maxlength="2" ></td>
				<td bgcolor="#f4f4f4"><a href="#"><img src="img/plus.gif" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a></td>
				<td bgcolor="#f4f4f4">&nbsp;</td>
			</tr>
			<tr>
				<td>Akkorder og tekst: </td>
				<td><a href="#"><img src="img/minus.gif" name="skalam" width="16" height="23" border="0" align="top" id="skalam" /></a></td>
				<td><input name="textfield4" type="text" id="label3" size="2" maxlength="2" value="3"></td>
				<td><a href="#"><img src="img/plus.gif" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td bgcolor="#f4f4f4">Afsnit:</td>
				<td bgcolor="#f4f4f4"><a href="#"><img src="img/minus.gif" alt="-" name="skalam" width="16" height="23" border="0" align="top" id="skalam" /></a></td>
				<td bgcolor="#f4f4f4"><input name="textfield" type="text" id="textfield" size="2" maxlength="2" value="3"></td>
				<td bgcolor="#f4f4f4"><a href="#"><img src="img/plus.gif" alt="+" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a></td>
				<td bgcolor="#f4f4f4">&nbsp;</td>
			</tr>
			</table>
			

		</div>
	 
	  <div id="Tekst_2">
	   
			<table width="500" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td height="30" colspan="2" bgcolor="#f2f2f2"><strong>Sangtekst: </strong></td>
				<td height="30" colspan="2" bgcolor="#f2f2f2"><strong>Akkorder: </strong></td>
			</tr>
			<tr>
				<td>
					<form id="form4" name="form4" method="post" action="">
						<label for="select"></label>
						Font:
					</form>
				</td>
				<td>
					<select name="select" id="select">
						<option>Verdana</option>
						<option>Arial</option>
						<option>Courier</option>
						<option>Times New Roman</option>
					</select>
				</td>
				<td width="68">
					<form id="form4" name="form4" method="post" action="">
						<label for="label4">Font</label> :
					</form>
				</td>
				<td width="195">
					<select name="select2" id="label4">
						<option>Verdana</option>
						<option>Arial</option>
						<option>Courier</option>
						<option>Times New Roman</option>
					</select>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f2f2">St&oslash;rrelse:</td>
				<td bgcolor="#f2f2f2">
					<a href="#"><img src="img/minus.gif" alt="-" name="skalam" width="16" height="23" border="0" align="top" id="skalam" />
					<input name="textfield5" type="text" id="textfield2" size="2" maxlength="2" value:="value:">
					<img src="img/plus.gif" alt="+" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a>
				</td>
				<td bgcolor="#f2f2f2">St&oslash;rrelse:</td>
				<td bgcolor="#f2f2f2"><a href="#"><img src="img/minus.gif" alt="-" name="skalam" width="16" height="23" border="0" align="top" id="skalam" />
					<input name="textfield52" type="text" id="textfield5" size="2" maxlength="2" value:="value:" />
					<img src="img/plus.gif" alt="+" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a>
				</td>
			</tr>
			<tr>
				<td width="67"><strong>Fed:</strong></td>
				<td width="170">
					<form id="form8" name="form8" method="post" action="">
						<input type="checkbox" name="checkbox4" value="checkbox" id="checkbox4" />
						<label for="checkbox4"></label>
					</form>
				</td>
				<td><strong>Fed:</strong></td>
				<td>
					<form id="form5" name="form5" method="post" action="">
						<input type="checkbox" name="checkbox" value="checkbox" id="checkbox" />
						<label for="checkbox"></label>
					</form>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f2f2"><em>Kursiv:</em></td>
				<td bgcolor="#f2f2f2">
					<form id="form9" name="form9" method="post" action="">
						<input type="checkbox" name="checkbox5" value="checkbox" id="checkbox5" />
						<label for="checkbox5"></label>
					</form>
				</td>
				<td bgcolor="#f2f2f2"><em>Kursiv:</em></td>
				<td bgcolor="#f2f2f2">
					<form id="form6" name="form6" method="post" action="">
						<input type="checkbox" name="checkbox2" value="checkbox" id="checkbox2" />
						<label for="checkbox2"></label>
					</form>
				</td>
			</tr>
			<tr>
				<td><u>Understreg:</u></td>
				<td>
					<form id="form10" name="form10" method="post" action="">
						<input type="checkbox" name="checkbox6" value="checkbox" id="checkbox6" />
						<label for="checkbox6"></label>
					</form>
				</td>
				<td><u>Understreg:</u></td>
				<td>
					<form id="form7" name="form7" method="post" action="">
						<input type="checkbox" name="checkbox3" value="checkbox" id="checkbox3" />
						<label for="checkbox3"></label>
					</form>
				</td>
			</tr>
			</table>

		    
		</div>

		<div id="Overskrifter">
			<table width="500" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td height="30" colspan="2" bgcolor="#f2f2f2"><strong>Ttitel: </strong></td>
				<td height="30" colspan="2" bgcolor="#f2f2f2"><strong>Forfatter: </strong></td>
			</tr>
			<tr>
				<td>
					<form id="form4" name="form4" method="post" action="">
						<label for="select"></label>Font:
					</form>
				</td>
				<td>
					<select name="select" id="select">
						<option>Verdana</option>
						<option>Arial</option>
						<option>Courier</option>
						<option>Times New Roman</option>
					</select>
				</td>
				<td width="68">
					<form id="form4" name="form4" method="post" action="">
						<label for="label4">Font</label>:
					</form>
				</td>
				<td width="195">
					<select name="select2" id="label4">
						<option>Verdana</option>
						<option>Arial</option>
						<option>Courier</option>
						<option>Times New Roman</option>
					</select>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f2f2">St&oslash;rrelse:</td>
				<td bgcolor="#f2f2f2">
					<a href="#"><img src="img/minus.gif" alt="-" name="skalam" width="16" height="23" border="0" align="top" id="skalam" />
					<input name="textfield5" type="text" id="textfield2" size="2" maxlength="2" value:="value:">
					<img src="img/plus.gif" alt="+" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a>
				</td>
				<td bgcolor="#f2f2f2">St&oslash;rrelse:</td>
				<td bgcolor="#f2f2f2">
					<a href="#"><img src="img/minus.gif" alt="-" name="skalam" width="16" height="23" border="0" align="top" id="skalam" />
					<input name="textfield52" type="text" id="textfield5" size="2" maxlength="2" value:="value:" />
					<img src="img/plus.gif" alt="+" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a>
				</td>
			</tr>
			<tr>
				<td width="67"><strong>Fed:</strong></td>
				<td width="170">
					<form id="form8" name="form8" method="post" action="">
						<input type="checkbox" name="checkbox4" value="checkbox" id="checkbox4" />
						<label for="checkbox4"></label>
					</form>
				</td>
				<td><strong>Fed:</strong></td>
				<td>
					<form id="form5" name="form5" method="post" action="">
						<input type="checkbox" name="checkbox" value="checkbox" id="checkbox" />
						<label for="checkbox"></label>
					</form>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f2f2"><em>Kursiv:</em></td>
				<td bgcolor="#f2f2f2">
					<form id="form9" name="form9" method="post" action="">
						<input type="checkbox" name="checkbox5" value="checkbox" id="checkbox5" />
						<label for="checkbox5"></label>
					</form>
				</td>
				<td bgcolor="#f2f2f2"><em>Kursiv:</em></td>
				<td bgcolor="#f2f2f2">
					<form id="form6" name="form6" method="post" action="">
						<input type="checkbox" name="checkbox2" value="checkbox" id="checkbox2" />
						<label for="checkbox2"></label>
					</form>
				</td>
			</tr>
			<tr>
				<td><u>Understreg:</u></td>
				<td>
					<form id="form10" name="form10" method="post" action="">
						<input type="checkbox" name="checkbox6" value="checkbox" id="checkbox6" />
						<label for="checkbox6"></label>
					</form>
				</td>
				<td><u>Understreg:</u></td>
				<td>
					<form id="form7" name="form7" method="post" action="">
						<input type="checkbox" name="checkbox3" value="checkbox" id="checkbox3" />
						<label for="checkbox3"></label>
					</form>
				</td>
			</tr>
			</table>
		</div>
		 
		<div id="Kommentarer">
			<table width="500" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td height="30" colspan="2" bgcolor="#f2f2f2"><strong>Kommentarer over sang: </strong></td>
				<td height="30" colspan="2" bgcolor="#f2f2f2"><strong>Kommentarer under sang: </strong></td>
			</tr>
			<tr>
				<td>
					<form id="form4" name="form4" method="post" action="">
						<label for="select"></label>Font:
					</form>
				</td>
				<td>
					<select name="select" id="select">
						<option>Verdana</option>
						<option>Arial</option>
						<option>Courier</option>
						<option>Times New Roman</option>
					</select>
				</td>
				<td width="68">
					<form id="form4" name="form4" method="post" action="">
						<label for="label4">Font</label>:
					</form>
				</td>
				<td width="195">
					<select name="select2" id="label4">
						<option>Verdana</option>
						<option>Arial</option>
						<option>Courier</option>
						<option>Times New Roman</option>
					</select>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f2f2">St&oslash;rrelse:</td>
				<td bgcolor="#f2f2f2">
					<a href="#"><img src="img/minus.gif" alt="-" name="skalam" width="16" height="23" border="0" align="top" id="skalam" />
					<input name="textfield5" type="text" id="textfield2" size="2" maxlength="2" value:="value:">
					<img src="img/plus.gif" alt="+" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a>
				</td>
				<td bgcolor="#f2f2f2">St&oslash;rrelse:</td>
				<td bgcolor="#f2f2f2">
					<a href="#"><img src="img/minus.gif" alt="-" name="skalam" width="16" height="23" border="0" align="top" id="skalam" />
					<input name="textfield52" type="text" id="textfield5" size="2" maxlength="2" value:="value:" />
					<img src="img/plus.gif" alt="+" name="skalap" width="16" height="23" border="0" align="top" id="skalap" /></a>
				</td>
			</tr>
			<tr>
				<td width="67"><strong>Fed:</strong></td>
				<td width="170">
					<form id="form8" name="form8" method="post" action="">
						<input type="checkbox" name="checkbox4" value="checkbox" id="checkbox4" />
						<label for="checkbox4"></label>
					</form>
				</td>
				<td><strong>Fed:</strong></td>
				<td>
					<form id="form5" name="form5" method="post" action="">
						<input type="checkbox" name="checkbox" value="checkbox" id="checkbox" />
						<label for="checkbox"></label>
					</form>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f2f2"><em>Kursiv:</em></td>
				<td bgcolor="#f2f2f2">
					<form id="form9" name="form9" method="post" action="">
						<input type="checkbox" name="checkbox5" value="checkbox" id="checkbox5" />
						<label for="checkbox5"></label>
					</form>
				</td>
				<td bgcolor="#f2f2f2"><em>Kursiv:</em></td>
				<td bgcolor="#f2f2f2">
					<form id="form6" name="form6" method="post" action="">
						<input type="checkbox" name="checkbox2" value="checkbox" id="checkbox2" />
						<label for="checkbox2"></label>
					</form>
				</td>
			</tr>
			<tr>
				<td><u>Understreg:</u></td>
				<td>
					<form id="form10" name="form10" method="post" action="">
						<input type="checkbox" name="checkbox6" value="checkbox" id="checkbox6" />
						<label for="checkbox6"></label>
					</form>
				</td>
				<td><u>Understreg:</u></td>
				<td>
					<form id="form7" name="form7" method="post" action="">
						<input type="checkbox" name="checkbox3" value="checkbox" id="checkbox3" />
						<label for="checkbox3"></label>
					</form>
				</td>
			</tr>
			</table>
		</div>
		 
		<div id="Gem_layout">
			<table width="500" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td colspan="2" bgcolor="#f2f2f2"><strong>Sang layout: </strong></td>
			</tr>
			<tr>
				<td colspan="2">Gemmer det nuv&aelig;rende layout for denne sang. Fremover anvendes dette layout<br />
i stedet for standard layoutet. </td>
			</tr>
			<tr>
				<td width="309">&nbsp;</td>
				<td width="179">
					<a href="#"><input type="button" name="sange.html22" value="Gem sang layout" class="submit_btn_2" /></a>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" bgcolor="#f2f2f2"><strong>Standard layout:</strong></td>
			</tr>
			<tr>
				<td colspan="2">Gemmer det nuv&aelig;rende layout som standard for alle sange, der ikke har sit eget layout. </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<a href="#"><input type="button" name="sange.html23" value="Overskriv standard layout" class="submit_btn_2" /></a>
				</td>
			</tr>
			</table>
		</div>
	</div> <!-- layout_edit -->
</td>
  </tr>
</table>