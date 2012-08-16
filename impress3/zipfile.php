<?php

class zipfile {

  var $datasec       = array();
  var $ctrl_dir      = array();
  var $eof_ctrl_dir  = "\x50\x4b\x05\x06\x00\x00\x00\x00";
  var $old_offset    = 0;
  var $pathToDeepDir = "DeepDir.php";

  function zipfile ($output_filename = 'slides.odp') {

    header('Content-Type: application/vnd.oasis.opendocument.presentation');
    header('Content-Disposition: inline; filename="' . $output_filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    include($this->pathToDeepDir);
  }

  function read_File ($file) {

    if (is_file($file)) {

      $fp = fopen ($file, 'rb');
	  $readLength = filesize($file);
	  $content = "";
	  if ($readLength > 0) $content = fread ($fp, $readLength);
      fclose ($fp);

      return $content;

    }

  }

  function getDirContent ($dirName = './') {

    if (is_dir($dirName)) {

   //   if (include($this->pathToDeepDir)) {

        $dir = new DeepDir();
        $dir->setDir($dirName);
        $dir->load();

        return $dir->files;

    /*  } else {

        if ($handle = opendir($dirName)) {

          while (false !== ($file = readdir($handle))) {

            if (($file != '.') && ($file != '..') && (is_file($file))) {

              $content[] = $file;

            }

          }

          closedir($handle);

          return $content;

        }

      }*/

    }

  }

  function addDirContent ($dir = './') {

    foreach ($this->getDirContent($dir) as $input) {
      $this->addFileAndRead(str_replace('.//', NULL, $input));
    }

  }

  function addFileAndRead ($file) {

    if (is_file($file))
      $this->addFile($this->read_File($file), $file);

  }


  function unix2DosTime($unixtime = 0) {

    $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

    if ($timearray['year'] < 1980) {

      $timearray['year']    = 1980;
      $timearray['mon']     = 1;
      $timearray['mday']    = 1;
      $timearray['hours']   = 0;
      $timearray['minutes'] = 0;
      $timearray['seconds'] = 0;

    }

    return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
      ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);

  }

  function addFile($data, $name, $time = 0) {

    $name     = str_replace('\\', '/', $name);

    $dtime    = dechex($this->unix2DosTime($time));
    $hexdtime = '\x' . $dtime[6] . $dtime[7]
              . '\x' . $dtime[4] . $dtime[5]
              . '\x' . $dtime[2] . $dtime[3]
              . '\x' . $dtime[0] . $dtime[1];

    eval('$hexdtime = "' . $hexdtime . '";');

    $fr   = "\x50\x4b\x03\x04";
    $fr   .= "\x14\x00";            // ver needed to extract
    $fr   .= "\x00\x00";            // gen purpose bit flag
    $fr   .= "\x08\x00";            // compression method
    $fr   .= $hexdtime;             // last mod time and date

    // "local file header" segment
    $unc_len = strlen($data);
    $crc     = crc32($data);
    $zdata   = gzcompress($data);
    $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
    $c_len   = strlen($zdata);
    $fr      .= pack('V', $crc);             // crc32
    $fr      .= pack('V', $c_len);           // compressed filesize
    $fr      .= pack('V', $unc_len);         // uncompressed filesize
    $fr      .= pack('v', strlen($name));    // length of filename
    $fr      .= pack('v', 0);                // extra field length
    $fr      .= $name;

    // "file data" segment
    $fr .= $zdata;

    // "data descriptor" segment (optional but necessary if archive is not
    // served as file)
    $fr .= pack('V', $crc);                 // crc32
    $fr .= pack('V', $c_len);               // compressed filesize
    $fr .= pack('V', $unc_len);             // uncompressed filesize

    // add this entry to array
    $this -> datasec[] = $fr;
    $new_offset        = strlen(implode('', $this->datasec));

    // now add to central directory record
    $cdrec  = "\x50\x4b\x01\x02";
    $cdrec .= "\x00\x00";                // version made by
    $cdrec .= "\x14\x00";                // version needed to extract
    $cdrec .= "\x00\x00";                // gen purpose bit flag
    $cdrec .= "\x08\x00";                // compression method
    $cdrec .= $hexdtime;                 // last mod time & date
    $cdrec .= pack('V', $crc);           // crc32
    $cdrec .= pack('V', $c_len);         // compressed filesize
    $cdrec .= pack('V', $unc_len);       // uncompressed filesize
    $cdrec .= pack('v', strlen($name) ); // length of filename
    $cdrec .= pack('v', 0 );             // extra field length
    $cdrec .= pack('v', 0 );             // file comment length
    $cdrec .= pack('v', 0 );             // disk number start
    $cdrec .= pack('v', 0 );             // internal file attributes
    $cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set

    $cdrec .= pack('V', $this -> old_offset ); // relative offset of local header
    $this -> old_offset = $new_offset;

    $cdrec .= $name;

    // optional extra field, file comment goes here
    // save to central directory
    $this -> ctrl_dir[] = $cdrec;

  }

  function file() {

    $data    = implode(NULL, $this -> datasec);
    $ctrldir = implode(NULL, $this -> ctrl_dir);

    return $data .
           $ctrldir .
           $this -> eof_ctrl_dir .
           pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries "on this disk"
           pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries overall
           pack('V', strlen($ctrldir)) .           // size of central dir
           pack('V', strlen($data)) .              // offset to start of central dir
           "\x00\x00";                             // .zip file comment length
  }

}

?>