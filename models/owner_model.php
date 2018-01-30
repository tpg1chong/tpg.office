 <?php

class Owner_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "owner";
    private $_table = "owner";
    private $_field = "*";
    private $_firstFieldName = "";
    private $_filters = array('active');

    private function getFilters(){

    }
    public function lists( $options=array() ) {
        $options = array_merge(array(
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'updated',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);


        if( isset($_REQUEST['active']) ){
            $options['active'] = $_REQUEST['active'];
        }

        $table = $this->_table;
        $condition = '';
        $params = array();


        if( !empty($options['active']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`agency_active`=:active";

            $params[':active'] = $options['active'];
        }


        $arr['total'] = $this->db->count($table, $condition, $params);

        $condition = !empty($condition) ? "WHERE {$condition}":'';

        $orderby = $this->orderby( $this->_firstFieldName.$options['sort'], $options['dir'] );
        $limit = !empty($options['unlimit']) ? '' : $this->limited( $options['limit'], $options['pager'] );

        $arr['lists'] = $this->buildFrag( $this->db->select("SELECT {$this->_field} FROM {$table} {$condition} {$orderby} {$limit}", $params ) );

        if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        $arr['options'] = $options;

        return $arr;
    }
    public function get($id){
        
        $select = $this->_field;
        $sth = $this->db->prepare("SELECT {$select} FROM {$this->_table} WHERE {$this->_firstFieldName}id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id ) );

        return $sth->rowCount()==1 
            ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) )
            : array();
    }


    /* convert */
    public function buildFrag($results) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function convert($data){

        $data = $this->_cutFirstFieldName($this->_firstFieldName, $data);

        $data['time']  = strtotime($data['created']);
        $data['created_str'] = $this->fn->q('time')->normal($data['created']);

        // $data['url'] = URL.'news/'.date('Y/m/', $data['time']).$data['primarylink'];

        $data['permit']['del'] = 1;
        // $data['gallery'] = array();

        return $data;
    }
}