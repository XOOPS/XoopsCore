<?php
// $Id$

/**
 * package::i.tools
 *
 * php-downloader    v1.0    -    www.ipunkt.biz
 *
 * (c)    2002 - www.ipunkt.biz (rok)
 *
 * Zip file creation class.
 * Makes zip files.
 *
 * Based on :
 *
 *       http://www.zend.com/codex.php?id=535&single=1
 *       By Eric Mueller <eric@themepark.com>
 *
 *       http://www.zend.com/codex.php?id=470&single=1
 *       by Denis125 <webmaster@atlant.ru>
 *
 *       a patch from Peter Listiak <mlady@users.sourceforge.net> for last modified
 *       date and time of the compressed file
 *
 * Official ZIP file format: http://www.pkware.com/appnote.txt
 *
 * @copyright (c)    2002 - www.ipunkt.biz (rok)
 * @package class
 */
class zipfile
{
    /**
     * Array to store compressed data
     *
     * @var array $datasec
     */
    public $datasec = [];

    /**
     * Central directory
     *
     * @var array $ctrl_dir
     */
    public $ctrl_dir = [];

    /**
     * End of central directory record
     *
     * @var string $eof_ctrl_dir
     */
    public $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";

    /**
     * Last offset position
     *
     * @var int $old_offset
     */
    public $old_offset = 0;

    /**
     * Converts an Unix timestamp to a four byte DOS date and time format (date
     * in high two bytes, time in low two bytes allowing magnitude comparison).
     *
     * @param int $unixtime the current Unix timestamp
     * @return int the current date in a four byte DOS format
     * @access private
     */
    public function unix2DosTime($unixtime = 0)
    {
        $timearray = (0 == $unixtime) ? getdate() : getdate($unixtime);
        if ($timearray['year'] < 1980) {
            $timearray['year'] = 1980;
            $timearray['mon'] = 1;
            $timearray['mday'] = 1;
            $timearray['hours'] = 0;
            $timearray['minutes'] = 0;
            $timearray['seconds'] = 0;
        } // end if
        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    }

    // end of the 'unix2DosTime()' method

    /**
     * Adds "file" to archive
     *
     * @param string $data file contents
     * @param string $name name of the file in the archive (may contains the path)
     * @param int $time the current timestamp
     * @access public
     */
    public function addFile($data, $name, $time = 0)
    {
        $name = str_replace('\\', '/', $name);

        $dtime = dechex($this->unix2DosTime($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7] . '\x' . $dtime[4] . $dtime[5] . '\x' . $dtime[2] . $dtime[3] . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00"; // ver needed to extract
        $fr .= "\x00\x00"; // gen purpose bit flag
        $fr .= "\x08\x00"; // compression method
        $fr .= $hexdtime; // last mod time and date
        // "local file header" segment
        $unc_len = mb_strlen($data);
        $crc = crc32($data);
        $zdata = gzcompress($data);
        $zdata = mb_substr(mb_substr($zdata, 0, mb_strlen($zdata) - 4), 2); // fix crc bug
        $c_len = mb_strlen($zdata);
        $fr .= pack('V', $crc); // crc32
        $fr .= pack('V', $c_len); // compressed filesize
        $fr .= pack('V', $unc_len); // uncompressed filesize
        $fr .= pack('v', mb_strlen($name)); // length of filename
        $fr .= pack('v', 0); // extra field length
        $fr .= $name;
        // "file data" segment
        $fr .= $zdata;
        // "data descriptor" segment (optional but necessary if archive is not
        // served as file)
        $fr .= pack('V', $crc); // crc32
        $fr .= pack('V', $c_len); // compressed filesize
        $fr .= pack('V', $unc_len); // uncompressed filesize
        // add this entry to array
        $this->datasec[] = $fr;
        $new_offset = mb_strlen(implode('', $this->datasec));
        // now add to central directory record
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00"; // version made by
        $cdrec .= "\x14\x00"; // version needed to extract
        $cdrec .= "\x00\x00"; // gen purpose bit flag
        $cdrec .= "\x08\x00"; // compression method
        $cdrec .= $hexdtime; // last mod time & date
        $cdrec .= pack('V', $crc); // crc32
        $cdrec .= pack('V', $c_len); // compressed filesize
        $cdrec .= pack('V', $unc_len); // uncompressed filesize
        $cdrec .= pack('v', mb_strlen($name)); // length of filename
        $cdrec .= pack('v', 0); // extra field length
        $cdrec .= pack('v', 0); // file comment length
        $cdrec .= pack('v', 0); // disk number start
        $cdrec .= pack('v', 0); // internal file attributes
        $cdrec .= pack('V', 32); // external file attributes - 'archive' bit set
        $cdrec .= pack('V', $this->old_offset); // relative offset of local header
        $this->old_offset = $new_offset;
        $cdrec .= $name;
        // optional extra field, file comment goes here
        // save to central directory
        $this->ctrl_dir[] = $cdrec;
    }

    // end of the 'addFile()' method

    /**
     * Dumps out file
     *
     * @return string the zipped file
     * @access public
     */
    public function file()
    {
        $data = implode('', $this->datasec);
        $ctrldir = implode('', $this->ctrl_dir);

        return $data . $ctrldir . $this->eof_ctrl_dir . pack('v', count($this->ctrl_dir)) . // total # of entries "on this disk"
               pack('v', count($this->ctrl_dir)) . // total # of entries overall
               pack('V', mb_strlen($ctrldir)) . // size of central dir
               pack('V', mb_strlen($data)) . // offset to start of central dir
               "\x00\x00"; // .zip file comment length
    }

    // end of the 'file()' method
} // end of the 'zipfile' class
