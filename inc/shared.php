<?php

namespace AcfFbGallery;


class Shared
{
	public $version = '4';
	public $picture_fields = ['id','source','height','width','link','picture'];

	function __construct($version = '4')
	{
		$this->version = $version;
	}


	function update_value($value, $post_id, $field)
	{
		if (!empty($value) && ($field['cache_images'] == 1))
		{
			$images = $this->get_album_images($value, $field['access_token']);
			update_post_meta($post_id, '_' . $field['name'] . '_images', $images);
		}
		return $value;
	}

	function format_output_value($value, $post_id, $field)
	{
		if (empty($value))
		{
			return [];
		}

		if (($field['cache_images'] == 1))
		{
			$images = get_post_meta($post_id, '_' . $field['name'] . '_images', true);

			if (!is_array($images))
			{
				$images = $this->get_album_images($value, $field['access_token']);
			}
		} else
		{
			$images = $this->get_album_images($value, $field['access_token']);
		}


		return $images;
	}

	function get_album_images($album_id, $access_token)
	{

		$url = sprintf('https://graph.facebook.com/%s/photos?limit=1000&access_token=%s&fields=%s', $album_id, $access_token, implode(',', $this->picture_fields) );

		$response = json_decode(file_get_contents($url));

		$images = [];
		foreach ($response->data as $image)
		{
			$images[] = [
				'id' => $image->id,
				'src' => $image->source,
				'height' => $image->height,
				'width' => $image->width,
				'link' => $image->link,
				'picture' => $image->picture,
			];
		}

		return $images;
	}

	function get_album_list($gallery_source_id, $access_token)
	{
		$url = sprintf('https://graph.facebook.com/%s/albums?fields=id,name&access_token=%s', $gallery_source_id, $access_token);
		$page_albums = file_get_contents($url);
		$albums = json_decode($page_albums);
		return $albums;
	}

	function render_field($field, $albums)
	{
		?>
		<select name="<?php echo esc_attr($field['name']) ?>">
			<option value=""></option>
			<?php foreach ($albums->data as $album)
			{ ?>
				<option
					<?php if ($album->id === $field['value'])
					{
						echo 'selected';
					} ?>
					<?php
					$url = sprintf('https://graph.facebook.com/%s/photos?limit=1000&access_token=%s&fields=%s', $album->id, $field['access_token'], implode(',', $this->picture_fields) );
					echo "data-gallery-url='$url'";
					?>
					value="<?php echo $album->id; ?>"><?php echo $album->name; ?></option>
			<?php } ?>
		</select>
		<?php

		if ($this->is_v5())
		{
			$this->render_field_gallery($field);
		}

	}

	function is_v4()
	{
		return $this->version == '4';
	}

	function is_v5()
	{
		return $this->version == '5';
	}

	function render_field_gallery($field)
	{
		?>
		<div class="acf-gallery-main acf-facebook-gallery" style="position: relative;height: 500px;width: 100%;">

			<div class="acf-gallery-attachments">
				<?php
				global $post;
				$current_post_id = intval($post->ID);
				$images = get_post_meta($current_post_id, '_' . $field['_name'] . '_images', true);
				if(is_array($images) && !empty($images)) :
				foreach ($images as $key => $image) :
					?>
					<div class="acf-gallery-attachment acf-soh" data-id="<?php echo $image['id']; ?>">
						<div class="margin" title="">
							<div class="thumbnail">
								<img src="<?php echo $image['src']; ?>" />
							</div>
						</div>
					</div>
				<?php endforeach ;
				endif;
				?>
			</div>

		</div>
		<?php
	}
}