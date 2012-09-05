function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}

var vbCr = "\r";
var vbLf = "\n";
var vbCrLf = "\r\n";
// Most of this is ported from VB, which explains _all_ bugs (kind of )

  function RenderSong(strSong, strTitel, strForfatter, strKommentarerOver, strKommentarerUnder, // Required variables
                      intTaktSpacing, intLineSpacing, intSignLineSpacing, intAfsnitLineSpacing, // This line and below is optional variables
                      strTextFont, intTextFontSize, blnTextFontBold, blnTextFontItalic,
                      blnTextFontUnderline, strSignFont, intSignFontSize,
                      blnSignFontBold, blnSignFontItalic, blnSignFontUnderline,strTitelFont,
                      intTitelFontSize, blnTitelFontBold, blnTitelFontItalic, blnTitelFontUnderline,
                      strForfatterFont, intForfatterFontSize,
                      blnForfatterFontBold, blnForfatterFontItalic, blnForfatterFontUnderline,
                      strOverFont, intOverFontSize, blnOverFontBold, blnOverFontItalic,
                      blnOverFontUnderline, strUnderFont, intUnderFontSize, blnUnderFontBold,
                      blnUnderFontItalic, blnUnderFontUnderline)
  {

  // Set default value for optional variables if not supplied
  
  // Spacing
  intTaktSpacing = (typeof(intTaktSpacing) != 'undefined' ? intTaktSpacing : 20);
  intLineSpacing = typeof(intLineSpacing) != 'undefined' ? intLineSpacing : 5;
  intSignLineSpacing = typeof(intSignLineSpacing) != 'undefined' ? intSignLineSpacing : 5;
  intAfsnitLineSpacing = typeof(intAfsnitLineSpacing) != 'undefined' ? intAfsnitLineSpacing : 5;
  
  // Song text setting
  strTextFont = typeof(strTextFont) != 'undefined' ? strTextFont : "Verdana";
  intTextFontSize = typeof(intTextFontSize) != 'undefined' ? intTextFontSize : 12;
  blnTextFontBold = typeof(blnTextFontBold) != 'undefined' ? blnTextFontBold : false;
  blnTextFontItalic = typeof(blnTextFontItalic) != 'undefined' ? blnTextFontItalic : false;
  blnTextFontUnderline = typeof(blnTextFontUnderline) != 'undefined' ? blnTextFontUnderline : false;
  
  // "nodes" text setting
  strSignFont = typeof(strSignFont) != 'undefined' ? strSignFont : "Verdana";
  intSignFontSize = typeof(intSignFontSize) != 'undefined' ? intSignFontSize : 12;
  blnSignFontBold = typeof(blnSignFontBold) != 'undefined' ? blnSignFontBold : true;
  blnSignFontItalic = typeof(blnSignFontItalic) != 'undefined' ? blnSignFontItalic : false;
  blnSignFontUnderline = typeof(blnSignFontUnderline) != 'undefined' ? blnSignFontUnderline : false;
  
  // Title text setting
  strTitelFont = typeof(strSignFont) != 'undefined' ? strSignFont : "Verdana";
  intTitelFontSize = typeof(intTitelFontSize) != 'undefined' ? intTitelFontSize : 16;
  blnTitelFontBold = typeof(blnTitelFontBold) != 'undefined' ? blnTitelFontBold : true;
  blnTitelFontItalic = typeof(blnTitelFontItalic) != 'undefined' ? blnTitelFontItalic : false;
  blnTitelFontUnderline = typeof(blnTitelFontUnderline) != 'undefined' ? blnTitelFontUnderline : false;
  
  // Author text setting
  strForfatterFont = typeof(strForfatterFont) != 'undefined' ? strForfatterFont : "Verdana";
  intForfatterFontSize = typeof(intForfatterFontSize) != 'undefined' ? intForfatterFontSize : 14;
  blnForfatterFontBold = typeof(blnForfatterFontBold) != 'undefined' ? blnForfatterFontBold : false;
  blnForfatterFontItalic = typeof(blnForfatterFontItalic) != 'undefined' ? blnForfatterFontItalic : false;
  blnForfatterFontUnderline = typeof(blnForfatterFontUnderline) != 'undefined' ? blnForfatterFontUnderline : false;
  
  // Comment-above-song-text settings
  strOverFont = typeof(strOverFont) != 'undefined' ? strOverFont : "Verdana";
  intOverFontSize = typeof(intOverFontSize) != 'undefined' ? intOverFontSize : 10;
  blnOverFontBold = typeof(blnOverFontBold) != 'undefined' ? blnOverFontBold : false;
  blnOverFontItalic = typeof(blnOverFontItalic) != 'undefined' ? blnOverFontItalic : false;
  blnOverFontUnderline = typeof(blnOverFontUnderline) != 'undefined' ? blnOverFontUnderline : false;

  // Comment-below-song-text settings
  strUnderFont = typeof(strUnderFont) != 'undefined' ? strUnderFont : "Verdana";
  intUnderFontSize = typeof(intUnderFontSize) != 'undefined' ? intUnderFontSize : 10;
  blnUnderFontBold = typeof(blnUnderFontBold) != 'undefined' ? blnUnderFontBold : false;
  blnUnderFontItalic = typeof(blnUnderFontItalic) != 'undefined' ? blnUnderFontItalic : false;
  blnUnderFontUnderline = typeof(blnUnderFontUnderline) != 'undefined' ? blnUnderFontUnderline : false;

    strOutput = "";
    strLine = "";
    strRow1 = "";
    strRow2 = "";
    blnDoubleRow = "";
    strOuterTable = "<br>"; // the "<br>" is for some reason needed by IE to render the styles.
    blnNewTakt = false;
    strTakt  = "";
    blnHasContent = false;

    strOuterTable += "<STYLE>";
    strOuterTable += ".outertable {border: 0px; padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px;}";
    strOuterTable += ".outertable td {padding-right: " + intTaktSpacing + "px;}";
    strOuterTable += ".takttable {border: 0px; padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px;}";
    strOuterTable += ".takttable td {padding-right: 0px;}";
    strOuterTable += ".signcell {padding-bottom: " + intSignLineSpacing + "px; font-family: " + strSignFont + "; font-size: " + intSignFontSize + "px; font-weight: " + (blnSignFontBold ? "bold" : "normal") + "; font-style: " + (blnSignFontItalic ? "italic" : "normal") + "; text-decoration: " + (blnSignFontUnderline ? "underline" : "none") + ";}";
    strOuterTable += ".textcell {padding-bottom: " + intLineSpacing + "px; font-family: " + strTextFont + "; font-size: " + intTextFontSize + "px; font-weight: " + (blnTextFontBold ? "bold" : "normal") + "; font-style: " + (blnTextFontItalic ? "italic" : "normal") + "; text-decoration: " + (blnTextFontUnderline ? "underline" : "none") + ";}";
    strOuterTable += ".titelcell {padding-bottom: " + intLineSpacing + "px; font-family: " + strTitelFont + "; font-size: " + intTitelFontSize + "px; font-weight: " + (blnTitelFontBold ? "bold" : "normal") + "; font-style: " + (blnTitelFontItalic ? "italic" : "normal") + "; text-decoration: " + (blnTitelFontUnderline ? "underline" : "none") + ";}";
    strOuterTable += ".forfattercell {padding-bottom: " + intLineSpacing + "px; font-family: " + strForfatterFont + "; font-size: " + intForfatterFontSize + "px; font-weight: " + (blnForfatterFontBold ? "bold" : "normal") + "; font-style: " + (blnForfatterFontItalic ? "italic" : "normal") + "; text-decoration: " + (blnForfatterFontUnderline ? "underline" : "none") + ";}";
    strOuterTable += ".overcell {padding-bottom: " + intLineSpacing + "px; font-family: " + strOverFont + "; font-size: " + intOverFontSize + "px; font-weight: " + (blnOverFontBold ? "bold" : "normal") + "; font-style: " + (blnOverFontItalic ? "italic" : "normal") + "; text-decoration: " + (blnOverFontUnderline ? "underline" : "none") + ";}";
    strOuterTable += ".undercell {padding-bottom: " + intLineSpacing + "px; font-family: " + strUnderFont + "; font-size: " + intUnderFontSize + "px; font-weight: " + (blnUnderFontBold ? "bold" : "normal") + "; font-style: " + (blnUnderFontItalic ? "italic" : "normal") + "; text-decoration: " + (blnUnderFontUnderline ? "underline" : "none") + ";}";
    strOuterTable += "</STYLE>";

    strOuterTable += RenderTextArea(strTitel, "titelcell", intAfsnitLineSpacing);
    strOuterTable += RenderTextArea(strForfatter, "forfattercell", intAfsnitLineSpacing);
    strOuterTable += RenderTextArea(strKommentarerOver, "overcell", intAfsnitLineSpacing);

    strOuterTable += "<TABLE cellspacing=\"0\" cellpadding=\"0\" class=\"outertable\">";

    strSong = strSong.replace(vbCrLf + vbCrLf, vbCrLf + " " + vbCrLf);
    strSong = strSong.replace(vbCr + vbCr, vbCr + " " + vbCr);
    strSong = strSong.replace(vbLf + vbLf, vbLf + " " + vbLf);
    strSong = strSong.replace(vbCr, vbLf);
	strSong = strSong.replace(/ /g, "&nbsp;");

/*    while (strSong.indexOf(vbLf + vbLf + vbLf) >= 0) {
      strSong = strSong.replace(vbLf + vbLf + vbLf, vbLf + vbLf);
    }*/

    strLines = strSong.split(vbLf);
    for (i=0; i < strLines.length; ++i) {
	  strLine = strLines[i];
      if ((trim(strLine) == "") && (blnHasContent)) {
        blnHasContent = false;
        strOuterTable += "</TABLE>";
        strOuterTable += "<TABLE cellspacing=\"0\" cellpadding=\"0\" class=\"outertable\">";
      }
      blnDoubleRow = (strLine.indexOf("[") >= 0);

      strOuterTable += "<TR>";

      if (trim(strLine) != "") {
        while (strLine != "") {
          if (strLine.indexOf("|") >= 0) {
            strTakt = strLine.substring(0, strLine.indexOf("|"));
            strLine = strLine.substr(strLine.indexOf("|") + 1); //strLine.Remove(0, strLine.indexOf("|") + 1);
          } else {
            strTakt = strLine;
            strLine = "";
          }
          if (strTakt != "") {
            blnHasContent = true;
          }
          strOuterTable += "<TD>" + RenderTakt(strTakt, blnDoubleRow) + "</TD>";
        } // while
	  } else {
	    strOuterTable += "<TD>&nbsp;</TD>";
	  }
      strOuterTable += "</TR>";
    } // for
    strOuterTable += "</TABLE>";

    strOuterTable += RenderTextArea(strKommentarerUnder, "undercell", intAfsnitLineSpacing);

    return strOuterTable;

  } // end of RenderSong

  function RenderTextArea(strArea, strCSSClass, intAfsnitLineSpacing) {
    strTemp = "";
    strLine = "";

    strArea = trim(strArea); //strArea.Trim;
    strArea = strArea.replace(vbCr, vbLf);
    while (strArea.indexOf(vbLf + vbLf) >= 0) {
      strArea = strArea.replace(vbLf + vbLf, vbLf);
    }

    strLines = strArea.split(vbLf);
    for (i=0; i < strLines.length; ++i) {
	  strLine = strLines[i];
      strTemp += "<TR><TD class=\"" + strCSSClass + "\">" + strLine + "</TD></TR>";
    }

    if (strTemp != "") {
      strTemp = "<TABLE cellspacing=\"0\" cellpadding=\"0\" class=\"outertable\">" + strTemp + "<TR style=\"height: " + intAfsnitLineSpacing + "px\"><TD></TD></TR></TABLE>";
    }

    return strTemp;
  } // End of RenderTextArea

  function RenderTakt(strTakt, blnDoubleRow) {
    strRow1 = "";
    strRow2 = "";
    strPart = "";
    strText = "";
    strSign = "";
    strOutput = "";

    strOutput = "<TABLE cellspacing=\"0\" cellpadding=\"0\" class=\"takttable fed\">";
    strRow1 = "<TR>";
    strRow2 = "<TR>";
    while (strTakt != "") {
      strPart = "";
      if (strTakt.indexOf("[") == 0) {
        strPart = "[";
        strTakt = strTakt.substr(1); //strTakt.Remove(0, 1);
      }
      if (strTakt.indexOf("[") >= 0) {
        strPart += strTakt.substring(0, strTakt.indexOf("["));
        strTakt = strTakt.substr(strTakt.indexOf("[")); //strTakt.Remove(0, strTakt.indexOf("["));
      } else {
        strPart += strTakt;
        strTakt = "";
      }

      if (strPart.indexOf("[") == 0) {
        strPart = strPart.substr(1); //strPart.Remove(0, 1);
        strSign = strPart.substring(0, strPart.indexOf("]"));
        strPart = strPart.substr(strPart.indexOf("]") + 1); //strPart.Remove(0, strPart.indexOf("]") + 1);
        strText = strPart;
      } else {
        strSign = "";
        strText = strPart;
      }

      if (strSign == "") {
        strSign = "&nbsp;";
      }
      if (strText == "") {
        strText = "&nbsp;";
      }

      strRow1 += "<TD class=\"signcell\">&nbsp;" + strSign.replace(/ /g, "&nbsp;") + "&nbsp;</TD>";
      strRow2 += "<TD class=\"textcell\">" + strText.replace(/ /g, "&nbsp;") + "</TD>";
    } // while
    strRow1 += "</TR>";
    strRow2 += "</TR>";

    if (blnDoubleRow) {
      strOutput += strRow1;
    }
    strOutput += strRow2;

    strOutput += "</TABLE>";

    return strOutput;
  } // end of RenderTakt