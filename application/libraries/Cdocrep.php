<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cDocRep
 *
 * @author dvlpserver
 */
class cdocrep {

    //put your code here

    var $filename,
            $original_path,
            $path_to_save,
            $path_temp_gen_thumbs,
            $path_to_save_thumbs,
            $md5,
            $CI,
            $extension,
            $full_filename,
            $cd_document_repository_category,
            $typeInfo,
            $hasThumbs = false,
            $error,
            $file_full_to_save,
            $log = false,
            $folderSufix,
            $mimeType,
            $gridShowThumb = true;

    function __construct() {
        $this->CI = & get_instance();
        $this->path_to_save = $this->CI->cdbhelper->getSettings('document_repository_path');
        $this->path_temp_gen_thumbs = $this->CI->cdbhelper->getSettings('document_repository_temp_path');
        $this->path_to_save_thumbs = $this->CI->cdbhelper->getSettings('document_repository_thumbs_path');
        $this->CI->load->model('docrep/document_repository_type_model', 'type_doc');
        $this->CI->load->model('docrep/document_repository_file_model', 'file_doc');
        $this->CI->load->model('docrep/document_repository_model', 'rep_doc');
    }

    public function addToRepositoryFile($document, $cd_document_repository_category, $autocommit = true) {
        $this->saveLog('Start Adding File');

        if (!file_exists($document)) {
            $this->saveLog($this->error);

            $this->error = 'Origin File does not exists ' . $document;
            return false;
        }

        // carrego todas as variaveis que vou precisar!!
        $this->explodeInfo($document);
        $this->saveLog('Info:');
        $this->saveLog($this->filename);
        $this->saveLog($this->original_path);
        $this->saveLog($this->extension);
        $this->saveLog($this->md5);
        $this->cd_document_repository_category = $cd_document_repository_category;

        $document = $this->adjustExtension($document);

        $this->full_filename = $document;

        $this->typeInfo = $this->getExtensionSetup();

        // vejo se jah existe um arquivo com o mesmo MD5. Se tiver, eh soh retornar ele.
        $cd_document_repository_file = $this->retDocRepFile();
        if ($cd_document_repository_file !== null) {

            if (!unlink($document)) {
                $this->saveLog('nao consegue deletar');

                return false;
            };
            return $cd_document_repository_file;
        }

        $this->addSubFolder();

        $this->file_full_to_save = $this->path_to_save . $this->md5 . '.' . $this->extension;


        if (!rename($this->full_filename, $this->file_full_to_save)) {
            $this->error = 'Error Renaming file %s, %s';
            $this->error = sprintf($this->error, $this->full_filename, $this->path_to_save . $this->md5);
            $this->saveLog($this->error);

            return false;
        }
        chmod($this->file_full_to_save, 0777);


        // gera o thumbnails
        // print_r($this->typeInfo);
        $this->saveLog('antes do if');

        $folder_thumb = '';

        if ($this->typeInfo['fl_generate_thumbs'] == '1') {
            IF (!$this->makeThumbs()) {
                return false;
            }
            $folder_thumb = $this->path_to_save_thumbs;
        }


        // hora de salvar no banco!

        if ($autocommit) {
            $this->CI->cdbhelper->trans_begin();
        }

        //agora jah tenho todos os dados prontos, eh soh salvar na tabela!!
        $code = $this->CI->file_doc->insertdbDR($this->md5, $this->path_to_save, $folder_thumb, $this->extension);

        if (!$this->CI->cdbhelper->trans_status()) {
            $this->error = $this->CI->cdbhelper->trans_last_error();
            if ($autocommit) {
                $this->CI->cdbhelper->trans_end();
            }
            return false;
        }

        if ($autocommit) {
            $this->CI->cdbhelper->trans_commit();
            $this->CI->cdbhelper->trans_end();
        }

        return $code;
    }

    function addSubFolder() {
        $level1 = substr($this->md5, 0, 1);
        $level2 = substr($this->md5, 1, 1);



        // crio primeiro levelse nao existir;
        if (!is_dir($this->path_to_save . $level1 . '/')) {
            mkdir($this->path_to_save . $level1);
        }

        chmod($this->path_to_save . $level1, 0777);


        // crio primeiro levelse nao existir;
        if (!is_dir($this->path_to_save . $level1 . '/' . $level2)) {
            mkdir($this->path_to_save . $level1 . '/' . $level2);
        }
        chmod($this->path_to_save . $level1 . '/' . $level2, 0777);

        // crio primeiro levelse nao existir - THUMBS;
        if (!is_dir($this->path_to_save_thumbs . $level1 . '/')) {
            mkdir($this->path_to_save_thumbs . $level1);
            //chmod($this->path_to_save_thumbs . $level1, 0777);
        }



        // crio primeiro levelse nao existir;
        if (!is_dir($this->path_to_save_thumbs . $level1 . '/' . $level2)) {
            mkdir($this->path_to_save_thumbs . $level1 . '/' . $level2);
            //chmod($this->path_to_save_thumbs . $level1 . '/' . $level2, 0777);
        }


        $this->path_to_save_thumbs = $this->path_to_save_thumbs . $level1 . '/' . $level2 . '/';

        $this->path_to_save = $this->path_to_save . $level1 . '/' . $level2 . '/';
    }

    function explodeInfo($document) {

        $array_doc = explode('/', $document);

        // nome do arquivo
        $this->filename = $array_doc[count($array_doc) - 1];
        $lastpos = strripos($document, '/');
        $this->original_path = substr($document, 0, $lastpos);
        $lastpos = strripos($document, '.');
        $this->extension = strtolower(substr($document, $lastpos + 1));
        $this->mimeType = mime_content_type($document);

        $this->md5 = $this->retMD5($document);
    }

    public function retDocRepFile() {
        $reta = $this->CI->file_doc->retByHash($this->md5);

        if (count($reta) == 0) {
            return null;
        } else {
            return $reta[0]['cd_document_file'];
        }
    }

    public function retMD5($document) {
        // exemplo com o exec. o MD5_File do PHP se mostrou mais rapido
        //$mdfull = exec('md5sum  "'.$document.'" ');
        //$mdfull = shell_exec('md5sum -b ' . escapeshellarg($document));
        //$exploded = explode(' ', $mdfull);
        //return $exploded[0];
        $mdfull = md5_file($document);
        return $mdfull;
    }

    function getExtensionSetup() {
        /*
          $sql = "SELECT cd_document_repository_type, ds_document_repository_extension,
          cd_document_repository_category, fl_generate_thumbs,
          nr_thumbs_width, nr_thumbs_height, fl_thumbs_two_step
          FROM docrep.\"DOCUMENT_REPOSITORY_TYPE\"
          where ds_document_repository_extension = '". strtolower( $this->extension)."'"
          . "          and cd_document_repository_category =  ".$this->cd_document_repository_category;

         * 
         */

        $reta = $this->CI->type_doc->getDataByExtension($this->extension, $this->cd_document_repository_category);

        // se nao encontrou por extensao, procura por mimeType!
        if (count($reta) == 0) {
            $reta = $this->CI->type_doc->getDataByMime($this->mimeType, $this->cd_document_repository_category);
        }
        // se ainda nao encontrou, dah erro!
        if (count($reta) == 0) {
            return false;
        }

        //$this->extension = $reta[0]['ds_document_repository_extension'];
        return $reta[0];
    }

    public function makeThumbs() {
        $this->saveLog('makeThumbs');

        $file_to = $this->path_to_save_thumbs . $this->md5 . '.png';

        if ($this->typeInfo['fl_thumbs_two_step'] == '1') {
            $file_from = $this->path_temp_gen_thumbs . $this->md5 . '.pdf';

            $line = 'unoconv  -f pdf -e PageRange=1-1 -o %s %s';

            $line = sprintf($line, escapeshellarg($file_from), escapeshellarg($this->file_full_to_save));
            $this->saveLog('Command LiIne Step 1 (when 2):' . $line);

            //echo ($line);
            system($line, $ret);

            if ($ret) {
                $this->error = 'Error Generating Thumbs (First Step) <br>' . $ret . ' <br> ' . $line;
                return false;
            }
        } else {
            $file_from = $this->file_full_to_save;
        }

        $size = $this->typeInfo['nr_thumbs_width'] . 'x' . $this->typeInfo['nr_thumbs_height'] . '>';
        $quality = $this->typeInfo['fl_thumbs_high_quality'] == '1' ? 'PNG' : 'PNG8';


        $line = "convert %s[0] -thumbnail '$size>' -background white -gravity center -quality 90 $quality:%s";
        $line = sprintf($line, escapeshellarg($file_from), escapeshellarg($file_to));
        $this->saveLog('Command LiIne Step 2:' . $line);

        //$ret = shell_exec($line);
        system($line, $ret);

        if ($ret) {
            $this->error = 'Error Generating Thumbs (Second Step)<br>' . $ret . ' <br> ' . $line;
            return false;
        }

        // se eh dois passos, o from eh o arquivo PDF dentro da temp. Entao, removo ele.
        if ($this->typeInfo['fl_thumbs_two_step'] == 'Y') {
            unlink($file_from);
        }

        return true;
    }

    public function delFromDocumentRepository($cd_document_repository, $table_relation, $column_relation, $pk_relation) {
        $xSql = 'SELECT f.cd_document_file '
                . ' FROM ' . $this->CI->db->escape_identifiers("DOCUMENT_FILE") . ' f, ' . $this->CI->db->escape_identifiers("DOCUMENT_REPOSITORY") . ' d,  %s x '
                . 'WHERE d.cd_document_repository = x.cd_document_repository '
                . '  AND f.cd_document_file       = d.cd_document_file '
                . '  AND d.cd_document_repository = %s and %s = %s';

        $xSql = sprintf($xSql, $this->CI->db->escape_identifiers($table_relation), $cd_document_repository, $column_relation, $pk_relation);

        $ar = $this->CI->cdbhelper->basicSQLArray($xSql);
        $arCodes = array();
        foreach ($ar as $key => $value) {
            array_push($arCodes, $value['cd_document_file']);
        }



        $this->CI->cdbhelper->trans_begin();



        $sql = 'DELETE FROM %s where cd_document_repository = %s and %s = %s';
        $sql = sprintf($sql, $this->CI->db->escape_identifiers($table_relation), $cd_document_repository, $column_relation, $pk_relation);

        $this->CI->cdbhelper->CIBasicQuery($sql);

        if (!$this->CI->cdbhelper->trans_status()) {
            $lasterror = $this->CI->cdbhelper->trans_last_error();
            $this->CI->cdbhelper->trans_end();
            return $lasterror;
        } else {
            $this->CI->cdbhelper->trans_commit();
            $this->CI->cdbhelper->trans_end();

            $this->makeJsonInfo($arCodes);

            return 'OK';
        }
    }

    public function addToDocumentRepository($array, $table_relation, $column_relation, $pk_relation, $autoStartTrans = true) {
        $retcodes = array();
        $retFiles = array();
        if ($autoStartTrans) {
            $this->CI->cdbhelper->trans_begin();
        }

        foreach ($array as $value) {

            // primeiro checo necessidade de deletar
            if (isset($value['originalDocRep'])) {

                $xSql = 'SELECT f.cd_document_file '
                        . ' FROM ' . $this->CI->db->escape_identifiers("DOCUMENT_FILE") . ' f, ' . $this->CI->db->escape_identifiers("DOCUMENT_REPOSITORY") . ' d,  %s x '
                        . 'WHERE d.cd_document_repository = x.cd_document_repository '
                        . '  AND f.cd_document_file       = d.cd_document_file '
                        . '  AND d.cd_document_repository = %s and %s = %s';

                $xSql = sprintf($xSql, $this->CI->db->escape_identifiers($table_relation), $value['originalDocRep'], $column_relation, $pk_relation);

                $ar = $this->CI->cdbhelper->basicSQLArray($xSql);
                $arCodes = array();
                foreach ($ar as $key => $value2) {
                    array_push($arCodes, $value2['cd_document_file']);
                }


                $sql = 'delete from "' . $this->CI->db->escape_identifiers($table_relation) . '" WHERE cd_document_repository = ' . $value['originalDocRep'] . ' and ' . $column_relation . ' =  ' . $pk_relation;




                $q = $this->CI->cdbhelper->CIBasicQuery($sql);
                if (!$this->CI->cdbhelper->trans_status()) {
                    $this->error = $this->CI->cdbhelper->trans_last_error();
                    if ($autoStartTrans) {
                        $this->CI->cdbhelper->trans_end();
                    }
                    return false;
                }

                $this->makeJsonInfo($arCodes);


                if (isset($value['onlyDelete'])) {

                    if ($value['onlyDelete'] == 'Y') {
                        array_push($retcodes, array('cd_document_repository' => $value['originalDocRep'], $column_relation => $pk_relation, 'deleted' => 'Y'));
                        continue;
                    }
                }
            }



            $document = $this->path_temp_gen_thumbs . $value['filename'];
            if (!rename($value['tmp_filename'], $document)) {
                $this->error = 'Error Renaming File : from ' . $value['tmp_filename'] . ' TO ' . $document;
                $this->CI->cdbhelper->trans_rollback();
                if ($autoStartTrans) {
                    $this->CI->cdbhelper->trans_end();
                }
                return false;
            };

            chmod($document, 0777);

            $cd_document_repository_file = $this->addToRepositoryFile($document, $value['cd_document_repository_category'], FALSE);
            array_push($retFiles, $cd_document_repository_file);

            if (!$cd_document_repository_file) {
                $this->CI->cdbhelper->trans_rollback();
                if ($autoStartTrans) {
                    $this->CI->cdbhelper->trans_end();
                }
                return false;
            }

            $code = $this->CI->rep_doc->insertdbDR($value['ds_document_repository'], $value['filename'], $value['cd_document_repository_type'], $cd_document_repository_file);

            if (!$this->CI->cdbhelper->trans_status()) {
                $this->error = $this->CI->cdbhelper->trans_last_error();
                if ($autoStartTrans) {
                    $this->CI->cdbhelper->trans_end();
                }
                return false;
            }
            $col = '';
            $data = '';
            if (isSet($value['addColumns'])) {
                $col = ',' . $value['addColumns'];
                $data = ',' . $value['addColumnsData'];
            }
            
            

            $sql = 'INSERT INTO ' . $this->CI->db->escape_identifiers($table_relation) . ' (' . $column_relation . ',  cd_document_repository ' . $col . ' ) '
                    . ' VALUES  ( ' . $pk_relation . ', ' . $code . $data . ')';

            $q = $this->CI->cdbhelper->CIBasicQuery($sql);

            // catch the one inserted.

            if (!$this->CI->cdbhelper->trans_status()) {
                $this->error = $this->CI->cdbhelper->trans_last_error();
                if ($autoStartTrans) {
                    $this->CI->cdbhelper->trans_end();
                }
                return false;
            }
            $sql = "select * from " . $this->CI->db->escape_identifiers($table_relation) . " where $column_relation = $pk_relation AND cd_document_repository = $code";
            $q = $this->CI->cdbhelper->CIBasicQuery($sql);
            $row = $q->result_array();
            $inscode = array_values($row[0])[0];

            array_push($retcodes, $inscode);
            // $this->CI->cdbhelper->lastId()
        }

        $this->CI->cdbhelper->trans_commit();
        if ($autoStartTrans) {
            $this->CI->cdbhelper->trans_end();
        }

        $this->makeJsonInfo($retFiles);

        return $retcodes;
    }

    public function updateRepositoryData($array, $tableinfo = array()) {
        $this->CI->cdbhelper->trans_begin();

        foreach ($array as $key => $value) {
            
            $data = array('ds_document_repository' => $value['ds_document_repository'], 'cd_document_repository_type' => $value['cd_document_repository_type']);
            $where = "cd_document_repository = " . $value['cd_document_repository'];;

            $upd = $this->CI->db->update_string('DOCUMENT_REPOSITORY', $data, $where);
            
            /*
            $upd = 'UPDATE ' . $this->CI->db->escape_identifiers("DOCUMENT_REPOSITORY") . ' SET ds_document_repository = \'%s\', '
                    . '                                   cd_document_repository_type = %s '
                    . '  WHERE cd_document_repository = %s';

            
            $upd = sprintf($upd, $this->CI->security->xss_clean($value['ds_document_repository']), $value['cd_document_repository_type'], $value['cd_document_repository']);
*/
            
            
            $this->CI->cdbhelper->CIBasicQuery($upd);
            if (!$this->CI->cdbhelper->trans_status()) {
                $this->error = $this->CI->cdbhelper->trans_last_error();
                $this->CI->cdbhelper->trans_end();
                return false;
            }

            if ($tableinfo == array() || !isset($tableinfo['tp_field'])) {
                continue;
            }
            //die (print_r($tableinfo));
            $upd = 'UPDATE ' . $this->CI->db->escape_identifiers($tableinfo['table']) . ' SET ' . $tableinfo['tp_field'] . ' = \'%s\''
                    . '  WHERE cd_document_repository = %s';


            $upd = sprintf($upd, $value['cd_type'], $value['cd_document_repository']);
            $this->CI->cdbhelper->CIBasicQuery($upd);
            if (!$this->CI->cdbhelper->trans_status()) {
                $this->error = $this->CI->cdbhelper->trans_last_error();
                $this->CI->cdbhelper->trans_end();
                return false;
            }
        }

        $this->CI->cdbhelper->trans_commit();
        $this->CI->cdbhelper->trans_end();

        return true;
    }

    function saveLog($text) {
        if ($this->log) {

            file_put_contents('/tmp/docrep.txt', time() . microtime() . ": " . $text . "\n", FILE_APPEND);
        }
    }

    public function getPictureBase64($cd_document_repository) {
        $docs = $this->CI->rep_doc->retSqlArray(' AND r.cd_document_repository = ' . $cd_document_repository . '', '');
        if (count($docs) == 0) {
            return '';
        }
        $line = $docs[0];
        $filename = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

        return 'data: ' . $line['ds_mime_type'] . ';base64,' . base64_encode(file_get_contents($filename));
    }

    public function getPicture($cd_document_repository) {
        $docs = $this->CI->rep_doc->retSqlArray(' AND r.cd_document_repository = ' . $cd_document_repository . '', '');
        if (count($docs) == 0) {
            return '';
        }
        $line = $docs[0];
        $filename = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

        return file_get_contents($filename);
    }

    public function getPictureThumbs($cd_document_repository) {
        $docs = $this->CI->rep_doc->retSqlArray(' AND r.cd_document_repository = ' . $cd_document_repository . '', '');
        if (count($docs) == 0) {
            return '';
        }
        $line = $docs[0];
        $filename = $line['ds_document_file_thumbs_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

        return file_get_contents($filename);
    }

    public function adjustExtension($document) {

        $reta = $this->CI->type_doc->getDataByExtension($this->extension, $this->cd_document_repository_category);

        // se nao encontrou por extensao, procura por mimeType!
        if (count($reta) == 0) {
            $reta = $this->CI->type_doc->getDataByMime($this->mimeType, $this->cd_document_repository_category);

            $document_new = $this->original_path . substr($this->filename, 0, strlen($this->filename) - strlen($this->extension) - 1) . '.' . $reta[0]['ds_document_repository_extension'];
            rename($document, $document_new);
            chmod($document_new, 0777);
            $this->explodeInfo($document_new);

            return $document_new;
        }
        // se ain

        return $document;
    }

    public function makeJsonInfo($codes) {
        //disabled until have something

        return;
        ;

        if (count($codes) == 0) {
            return;
        }


        $x = $this->CI->file_doc->retRetrieveGridArray(' WHERE cd_document_file IN (' . implode(',', $codes) . ')');


        $sqlOrig = 'select \'ARTICLE\' as usedOn, 
	       (select ds_product_document_repository_type from "PRODUCT_DOCUMENT_REPOSITORY_TYPE" where cd_product_document_repository_type = x.cd_product_document_repository_type) as Type,
	       x.dt_record as Record,
	       d.ds_original_file as OriginalFileName,
               x.cd_product as pkTableRel
	FROM "PRODUCT_DOCUMENT_REPOSITORY" x, "DOCUMENT_REPOSITORY" d
	WHERE d.cd_document_repository = x.cd_document_repository
	  AND d.cd_document_file       = f.cd_document_file
';


        foreach ($x as $key => $value) {

            $var = array('HashName' => $value['ds_document_file_hash'],
                'pathFile' => $value['ds_document_file_path'],
                'PathFileThumbs' => $value['ds_document_file_thumbs_path'],
                'mainRecord' => $value['dt_record'],
                'fileOriginalExtension' => $value['ds_file_extension']
            );


            $sqldone = str_replace('f.cd_document_file', $value['cd_document_file'], $sqlOrig);

            $data = $this->CI->cdbhelper->basicSQLArray($sqldone);

            $var['data'] = $data;
            //$filesaved = $value['ds_document_file_path'].$value['ds_document_file_hash'].'.'.$value['ds_file_extension'].'.json';
            $filesaved = $value['ds_document_file_path'] . $value['ds_document_file_hash'] . '.' . $value['ds_file_extension'] . '.xml';

            $xml = $this->CI->cdbhelper->array_to_xml($var, 'imgInfo');

            file_put_contents($filesaved, $xml);
        }
    }
    
    
    
    public function retBasicGridObject($level, $gridname = 'docrepGrid', $divname = 'docrepGridDiv') {
        $grid = $this->CI->w2gridgen;
        $f = $this->CI->cfields;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
        }
 
        //$grid->addColumn('', 'Description', '20px', $f->);
        $grid->addColumn('ds_document_repository', 'Description', '100%', $f->retTypeStringAny());
        $grid->addColumn('ds_original_file', 'File Name', '100%', $f->retTypeStringAny());
        
        
        
        
        
    }
    
    
}