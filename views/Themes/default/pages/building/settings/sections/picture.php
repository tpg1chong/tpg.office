<div class="upload-gallery d-page-slideshow mal" data-plugins="upload_gallery" data-options="<?=$this->fn->stringify( array(

		'lists' => $this->item['gallery'],
		'data_post' => array(
			'obj_id' => $this->item['id'],
			'obj_type' => 'building',
			'album_name' => 'gallery',
			'media_type' => 'jpg',
			'min_width' => 980,
			'min_height' => 980,
			'minimize' => array(1900,1900)
			// 'sequence' => 1
		),
	) )?>">

	<div class="upload-gallery-lists" role="listsbox"></div>

	<div class="tar mbl"><a class="btn js-add">+เพิ่ม</a></div>
</div>