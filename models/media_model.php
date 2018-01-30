<?php

class Media_Model extends Model
{

	private $_albumKeyName = 'album';
	private $_mediaKeyName = 'media';
	private $_shape = array('o', 'n');

    public function __construct()
    {
        parent::__construct();
    }

    public function _getAlbumKey($id)
    {
    	return Hash::create('crc32b', $id, $this->_albumKeyName);
    }

    public function _getMediaKey($id)
    {
    	return Hash::create('md5', $id, $this->_mediaKeyName);
    }


    public function set($userfile, &$data, $options=array())
    {

        $options = array_merge(array(
            'folder' => '',
            'minimize' => array(270, 270)
        ), $options);

    	$folder = !empty($options['folder']) ? $options['folder']: '';
    	$upload = new Upload();
        $extension = $upload->getExtension($userfile['name']);
        $type = $upload->getType($userfile['name']);

    	if( !isset( $data['media_type'] )){
    		$data['media_type'] = $type;
    	}
    	else{
    		$type = $data['media_type'];
    	}

    	if( !isset( $data['media_name'] )){
    		$data['media_name'] = $userfile['name'];
    	}

    	$this->db->insert('media', $data);
    	$data['media_id'] = $this->db->lastInsertId();

        $aid = $this->_getAlbumKey($data['media_album_id']);
    	$mid = $this->_getMediaKey($data['media_id']);

        $filename = "{$folder}{$aid}_{$mid}_";

        $original = WWW_UPLOADS.$filename."o{$extension}";
        if( $upload->copies($userfile['tmp_name'], $original) ){
        	$data['dest']['original'] = $original;

            $path = WWW_UPLOADS.$filename."o.{$type}";
            $upload->imageToJpg($original, $path); // เปลียนประเภทรูป

            // covert to size Normal
            $normal = WWW_UPLOADS.$filename."n.jpg";
            if( $upload->copies($path, $normal) ){

            	/*if( !empty($_POST['cropimage']) ){
            		$upload->cropimage( $_POST['cropimage'], $normal );
            	}*/
                $upload->minimize( $normal, $options['minimize'] );
                $data['dest']['normal'] = $normal;
            }

            // covert to size Quad 4 เหลียมจตุรัส
            /*$quad = WWW_UPLOADS.$filename."n.{$type}";
            if( $upload->copies($path, $quad) ){
                $upload->quad( $quad );
            }*/

            // 
            $upload->minimize( $path );
        }
    }
    public function get($id)
    {
    	$sth = $this->db->prepare("SELECT * FROM media WHERE media_id=:id LIMIT 1");
        $sth->execute( array(
            ':id'=>$id
        ) );

        return  $sth->rowCount()==1
        	? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) )
        	: array();
    }
    public function convert($fdata) {

    	$aid = $this->_getAlbumKey($fdata['media_album_id']);
    	$mid = $this->_getMediaKey($fdata['media_id']);
    	$type = $fdata['media_type'];

        $data['album_id'] = $fdata['media_album_id'];
        $data['id'] = $fdata['media_id'];
        $data['type'] = $type;
        $data['name'] = $fdata['media_name'];

        // original
        $data['original'] = "{$aid}_{$mid}_o.{$type}";
        $data['original_url'] = UPLOADS."{$aid}_{$mid}_o.{$type}";

        // filename
        $data['filename'] = "{$aid}_{$mid}_n.{$type}";
        $data['url'] = UPLOADS.$data['filename'];

        // caption
        $data['caption'] = $fdata['media_caption'];

        if( !empty($fdata['folder']) ){
            $data['folder'] = $fdata['folder'];
        }

        return $data;
    }

    public function resize($id, $fdata, $folder='', $minimize=array(500, 500))
    {
    	$data = $this->get( $id );
    	if( !empty($data) ){

    		$aid = $this->_getAlbumKey($data['album_id']);
    		$mid = $this->_getMediaKey($data['id']);
    		$type = $data['type'];

    		$filename = "{$folder}{$aid}_{$mid}_";
    		$original = WWW_UPLOADS.$filename."o.{$type}";

    		if( file_exists($original) ){
    			$upload = new Upload();

    			$dest = WWW_UPLOADS.$filename."n.{$type}";
                if( $upload->copies($original, $dest) ){
                    $upload->cropimage( $fdata, $dest );

                    $upload->minimize( $dest, $minimize );
                }

    		}
    	}
    }

    public function del($id, $folder='')
    {
    	$data = $this->get( $id );

    	if( !empty($data) ){
    		
    		$aid = $this->_getAlbumKey($data['album_id']);
    		$mid = $this->_getMediaKey($data['id']);
    		$type = $data['type'];

    		$filename = "{$folder}{$aid}_{$mid}_";

	        foreach ($this->_shape as $key => $shape) {
	        	$dest = WWW_UPLOADS.$filename."{$shape}.{$type}";

		        if( file_exists($dest) ){
		            unlink( $dest );
		        }
	        }

	        $this->db->delete('media', "`media_id`={$id}");
    	}
    }
}