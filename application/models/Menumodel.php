
<?php
class Menumodel extends CI_Model{
    
     function __construct()
 {
   parent::__construct();

 }    
    
       public function retSubMenus($cd_human_resource, $cd_menu) {

        
           
        $sql = 'SELECT m.cd_menu, retDescTranslated(m.ds_menu, null) as ds_menu, m.ds_controller, m.cd_menu_parent, nr_order
                FROM ' . $this->db->escape_identifiers("MENU") . ' m,
                     ' . $this->db->escape_identifiers("HUMAN_RESOURCE_MENU") . ' h
                WHERE h.cd_human_resource = ?
                  AND m.cd_menu = h.cd_menu
                  and m.dt_deactivated IS NULL
                  and m.cd_menu_parent = ?

                UNION

                SELECT m.cd_menu,retDescTranslated(m.ds_menu, null) as ds_menu, m.ds_controller, m.cd_menu_parent, nr_order
                  FROM ' . $this->db->escape_identifiers("MENU") . ' m,
                       ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' jh,
                       ' . $this->db->escape_identifiers("JOBS_MENU") . '           jm
                  WHERE jh.cd_human_resource = ?
                    AND jm.cd_jobs = jh.cd_jobs
                    AND m.cd_menu = jm.cd_menu
                    and m.dt_deactivated IS NULL
                    and m.cd_menu_parent = ?
                    order by nr_order';

        $q = $this->getCdbhelper()->CIBasicQuery($sql, array($cd_human_resource, $cd_menu, $cd_human_resource, $cd_menu));
    
        $menu = '';
        
        
        
        foreach($q->result() as $row) {
           if ($row->ds_controller=="#") {
               $target="";
           } else {
               $target='target="content"';
           }
            
            //$menu = $menu.'<li><a href="'. $row->ds_controller. '" '.$target.'>'.$row->ds_menu.'</a>';
           $link = "'".$row->ds_controller."','".$row->ds_menu."'";
           
           $menu = $menu.'<li><a href="javascript:openpage('.$link.');" > '.$row->ds_menu.' </a>';
            
            $submenu= $this->retSubMenus($cd_human_resource, $row->cd_menu);
            
            if ($submenu!=''){
                
                $menu=$menu."<ul>";
                $menu=$menu.$submenu;                
                $menu=$menu."</ul>";

            }
            
        }

        return $menu;
    }
    
    
    public function returnMenu() {
        
        $cd_human_resource = $this->session->userdata('cd_human_resource');
    
        
        $sql = 'SELECT m.cd_menu, retDescTranslated(m.ds_menu, null) as ds_menu, m.ds_controller, m.cd_menu_parent, nr_order
                FROM ' . $this->db->escape_identifiers("MENU") . ' m,
                     ' . $this->db->escape_identifiers("HUMAN_RESOURCE_MENU") . ' h
                WHERE h.cd_human_resource = ?
                  AND m.cd_menu = h.cd_menu
                  and m.dt_deactivated IS NULL
                  and m.cd_menu_parent IS NULL
                  and h.dt_deactivated IS NULL

                UNION

                SELECT m.cd_menu, retDescTranslated(m.ds_menu, null) as ds_menu, m.ds_controller, m.cd_menu_parent, nr_order
                  FROM ' . $this->db->escape_identifiers("MENU") . ' m,
                       ' . $this->db->escape_identifiers("JOBS_HUMAN_RESOURCE") . ' jh,
                       ' . $this->db->escape_identifiers("JOBS_MENU") . '           jm
                  WHERE jh.cd_human_resource = ?
                    AND jm.cd_jobs = jh.cd_jobs
                    AND m.cd_menu = jm.cd_menu
                    and m.dt_deactivated IS NULL
                    and m.cd_menu_parent IS NULL
                    and jh.dt_deactivated IS NULL
                    order by nr_order';

        $q = $this->getCdbhelper()->CIBasicQuery($sql, array($cd_human_resource, $cd_human_resource) );
        
        $menu = '';
        
        foreach($q->result() as $row) {

           if ($row->ds_controller=="#") {
               $target="";
           } else {
               $target='target="content"';
           }
            
            //$menu = $menu.'<li><a href="'. $row->ds_controller. '" '.$target.'>'.$row->ds_menu.'</a>';
           $link = "'".$row->ds_controller."','".$row->ds_menu."'";

           $menu = $menu.'<li><a href="javascript:openpage('.$link.');" > '.$row->ds_menu.' </a>';
            
            $submenu= $this->retSubMenus($cd_human_resource, $row->cd_menu);
            if ($submenu!=''){
                $menu=$menu."<ul>";
                $menu=$menu.$submenu;                
                $menu=$menu."</ul>";

            }
            
            
            $menu = $menu.'</li>';
        }

        //$menu =$menu. '</ul>';
        
        return $menu;
    }
    
        
    public function retMenusbToMaint($type, $cd_jobs) {

        $this->cdbhelper->trans_begin();
        
        if ($this->db->dbdriver == 'postgre') {
            $sql = "SELECT * FROM getMenuOptions('%s', %s)  order by 2";
        } else {
            $sql = "CALL getMenuOptions('%s', %s)";
            
        }
        
        $this->cdbhelper->trans_commit();
        $this->cdbhelper->trans_end();


        
        $sql = sprintf($sql,$type, $cd_jobs);
                
        
        $q = $this->getCdbhelper()->CIBasicQuery($sql);
        return $q->result_array();

    }
    
    
        public function retRetrieveJson($type, $code) {
        
        $ret = $this->retMenusbToMaint($type, $code);
           
        
        $array = array();
        
        foreach ($ret as $row) {
            
            $insArray = $this->retW2Array (intval($row['cd_menu_key']),
                                           $row['ds_menu_key'], 
                                           $row['fl_checked']);
            
            array_push($array, $insArray ); 
        }
        
    return json_encode($array);
        
    }
        
    // monta o array. Funcao importante que vai garantir o formato dos campos na tela.
    function retW2Array ($cd_menu_key, 
                         $ds_menu_key, 
                         $fl_checked ) {
        
        if ($fl_checked == "Y") {
            $fl_checked = true; 
        } else {
            $fl_checked = false;
        }
        
        
        $array = array ('recid'      => $cd_menu_key,
                        'ds_menu_key'=> rtrim($ds_menu_key),
                        'fl_checked' => $fl_checked
                       );
        
        return $array;
        
    }    
        
 
    public function copyMergeMenu($type, $codefrom, $codeto, $copymerge) {
        
        
        $this->cdbhelper->trans_begin();
        
        if ($copymerge == 'C') {
            if ($type == 'H') {
                $sql = 'DELETE FROM ' . $this->db->escape_identifiers("HUMAN_RESOURCE_MENU") . ' where cd_human_resource = ' . $codeto;
            } else {
                $sql = 'DELETE FROM ' . $this->db->escape_identifiers("JOBS_MENU") . ' where cd_jobs = ' . $codeto;                
            }
            
            $this->getCdbhelper()->CIBasicQuery($sql); 
            if (!$this->cdbhelper->trans_status()) {
                $error = $this->cdbhelper->trans_last_error();
                $this->cdbhelper->trans_end();
                $msg = array("message"=>$error);

                return json_encode($msg);
            }  
        }
        
        if ($type == 'H') {
            $sql = 'INSERT INTO ' . $this->db->escape_identifiers("HUMAN_RESOURCE_MENU") . ' (cd_human_resource, cd_menu) ';
            $sql = $sql . ' SELECT '. $codeto . ', cd_menu FROM ' . $this->db->escape_identifiers("HUMAN_RESOURCE_MENU") . ' h ';
            $sql = $sql . ' WHERE cd_human_resource = ' . $codefrom ;
            $sql = $sql . '   AND NOT EXISTS ( SELECT 1 FROM ' . $this->db->escape_identifiers("HUMAN_RESOURCE_MENU") . ' h1 WHERE h1.cd_menu = h.cd_menu AND h1.cd_human_resource = '.$codeto.') ';
            
        } else {
            $sql = 'INSERT INTO ' . $this->db->escape_identifiers("JOBS_MENU") . ' (cd_jobs, cd_menu) ';
            $sql = $sql . ' SELECT '. $codeto . ', cd_menu FROM ' . $this->db->escape_identifiers("JOBS_MENU") . ' h ';
            $sql = $sql . ' WHERE cd_jobs = ' . $codefrom ;
            $sql = $sql . '   AND NOT EXISTS ( SELECT 1 FROM ' . $this->db->escape_identifiers("JOBS_MENU") . ' h1 WHERE h1.cd_menu = h.cd_menu AND h1.cd_jobs = '.$codeto.') ';
        }
                
        $this->getCdbhelper()->CIBasicQuery($sql);
        
        if (!$this->cdbhelper->trans_status()) {
            $error = $this->cdbhelper->trans_last_error();
            $this->cdbhelper->trans_end();
            $msg = array("message"=>$error);

            return json_encode($msg);
        }  

        $this->cdbhelper->trans_commit();
        
        $this->cdbhelper->trans_end();
        
        $msg = array("message"=>"OK");
        
        return json_encode($msg);
        
    }
    
    
    public function updateGridData($type, $cd_code, $array) {

        if ($type == "H" ) {
            $table = $this->db->escape_identifiers('HUMAN_RESOURCE_MENU');
            $cd_code_table = "cd_human_resource";
        } else {
            $table = $this->db->escape_identifiers('JOBS_MENU');
            $cd_code_table = "cd_jobs";            
        }
        
            
        $this->cdbhelper->trans_begin();
        $bError = false;
        foreach ($array as $row) {
            $array_row = (array) $row;
            $cd_menu = $array_row['recid'];
            $fl_checked =  $array_row['fl_checked'] == 1 ? "Y" : "N";
            $exists = $this->checkExists($table,$cd_code_table, $cd_code, $cd_menu );
            $sql = "nada";
            
            if ($fl_checked == 'Y' && !$exists ) {
                $sql = "insert into ".$table. "( $cd_code_table, cd_menu ) values ( ".$cd_code.",".$cd_menu.")";
            } 
            
            if ($fl_checked == 'N' && $exists) {
                $sql = "DELETE FROM ".$table. " where " .$cd_code_table. " = " . $cd_code ." and cd_menu = ".$cd_menu;
            }
           
            if ($sql != "nada") {
                $this->getCdbhelper()->CIBasicQuery($sql); 

                if ( !$this->cdbhelper->trans_status() ) {
                    $bError = true;
                    break;
                }                
            }
           
          
        }
        
        if ($bError) {
           $error = $this->cdbhelper->trans_last_error();
        } else {
            $error = "OK";
            $this->cdbhelper->trans_commit();
        }
        
        $this->cdbhelper->trans_end();
        $ret = array("message"=>$error);
        return json_encode($ret);
        
    }

    function checkExists ( $table, $pk, $code, $menu) {
        $sql = 'SELECT 1 FROM '.$table.' where '.$pk.' = '.$code. ' and cd_menu = '.$menu;
        $query = $this->getCdbhelper()->CIBasicQuery($sql);

        return $query->num_rows() > 0;

    }
    
    
    function getMenuName($controller) {
        $sql = 'SELECT  retDescTranslated(m.ds_menu, null) as ds_menu
                FROM ' . $this->db->escape_identifiers("MENU") . ' m
               WHERE m.ds_controller = ' . "'".$controller."'";
      $q = $this->getCdbhelper()->CIBasicQuery($sql);
      
      $a = $q->result_array();
      
      return $a[0]['ds_menu'];
    }
    
    function getMenuId($controller) {
        $sql = 'SELECT  m.cd_menu
                FROM ' . $this->db->escape_identifiers("MENU") . ' m
               WHERE m.ds_controller = ' . "'".$controller."'";
      $q = $this->getCdbhelper()->CIBasicQuery($sql);
      
      $a = $q->result_array();
      
      return $a[0]['cd_menu'];
    }
    
        
    
    
     public function returnMenuSelect() {
      $cd_human_resource = $this->session->userdata('cd_human_resource');
      $q = $this->getCdbhelper()->CIBasicQuery('select * from public.returnmenumb('.$cd_human_resource.')');
      $menu = $q->result_array();

      return $menu;
      
    }
    
    public function getCdbhelper() {
       return $this->cdbhelper;
    }
    
    }

?>