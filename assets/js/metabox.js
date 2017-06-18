/**
|| --------------------------------------------------------------------------------------------
|| Metabox JS
|| --------------------------------------------------------------------------------------------
||
|| @package		Dilaz Metaboxes
|| @subpackage	Metabox
|| @since		Dilaz Metaboxes 1.0
|| @author		WebDilaz Team, http://webdilaz.com, http://themedilaz.com
|| @copyright	Copyright (C) 2017, WebDilaz LTD
|| @link		http://webdilaz.com/metaboxes
|| @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
|| 
*/

jQuery(document).ready(function($) {
	
	/* DoWhen start	 */
	$(document).doWhen();

	/* Tabs Content min-Height */
	$('.dilaz-metabox').each(function() {
		
		var $this      = $(this),
			$navHeight = $this.find('.dilaz-mb-tabs-nav').height(),
			$content   = $this.find('.dilaz-mb-tabs-content');
			
		$content.css({'min-height':$navHeight+20});
	});

	/* Tabs */
	$(function() {
		
		$('.dilaz-metabox').closest('.postbox').addClass('dilaz-mb-wrapper');
		
		$('.dilaz-metabox').find('.dilaz-mb-tabs-nav-item:first-of-type, .dilaz-meta-tab:first-of-type').addClass('active');

		$('.dilaz-mb-tabs').on('click', '.dilaz-mb-tabs-nav-item', function() {
			
			var tabNav     = $(this),
				tabContent = tabNav.closest('.dilaz-mb-tabs').siblings().children().eq(tabNav.index());
				
			tabNav.addClass('active').siblings().removeClass('active');
			tabContent.addClass('active').siblings().removeClass('active');		
		});
	});
	
	/* Hidden field */
	$('div[data-dilaz-hidden="yes"]').hide();
	
	/* Checkbox field */
	$('.dilaz-mb-tabs-content').on('click', '.dilaz-mb-checkbox', function() {
		$(this).toggleClass('focus');
	});
	
	/* Radio field */
	$('.dilaz-mb-tabs-content').on('click', '.dilaz-mb-radio', function() {
		
		var $this = $(this);
		
		$this.addClass('focus');
		$this.parent().siblings().find('.dilaz-mb-radio').removeClass('focus');
	});
	
	/* Switch and buttonset field */
	$('.dilaz-mb-tabs-content').on('click', '.dilaz-mb-switch, .dilaz-mb-button-set', function() {
		
		var $this = $(this);
		
		$this.parent().addClass('selected');
		$this.parent().siblings().removeClass('selected');
	});
	
	/* UI slider field */
	$('.dilaz-mb-slider').each(function() {
		
		var $this = $(this),
			$min  = parseInt($this.data('min')),
			$max  = parseInt($this.data('max')),
			$step = parseInt($this.data('step')),
			$val  = parseInt($this.data('val'));
			
		$this.slider({
			animate : true,
			range   : 'min',
			value   : $val,
			min     : $min,
			max     : $max,
			step    : $step,
			slide   : function(event, ui) {
				$this.next($val).find('span').text(ui.value);
				$this.siblings('input').val(ui.value);
			},
			change  : function(event, ui) {
				$this.next($val).find('span').text(ui.value);
				$this.siblings('input').val( ui.value);
			}
		});
	});
	
	/* UI range field */
	$('.dilaz-mb-range').each(function() {
		
		var $this      = $(this),
			$minVal    = parseInt($this.data('min-val')),
			$maxVal    = parseInt($this.data('max-val')),
			$min       = parseInt($this.data('min')),
			$max       = parseInt($this.data('max')),
			$step      = parseInt($this.data('step')),
			$range     = $this.find('.dilaz-mb-slider-range'),
			$optMin    = $this.find('#option-min'),
			$optMinVal = $optMin.val(),
			$optMax    = $this.find('#option-max'),
			$optMaxVal = $optMax.val();
			
		$range.slider({
			range  : true,
			min    : $min,
			max    : $max,
			step   : $step,
			values : [$minVal, $maxVal],
			slide  : function(event, ui) {
				$optMin.val(ui.values[0]);
				$optMin.next('.dilaz-mb-min-val').find('.val').text(ui.values[0]);
				$optMax.val(ui.values[1]);
				$optMax.next('.dilaz-mb-max-val').find('.val').text(ui.values[1]);
			}
		});
	});
	
	/* File upload field */
	$('.dilaz-mb-file-upload-button').each(function() {
		
		var imageFrame;
		
		$(this).on('click', function(event) {
			
			event.preventDefault();
			
			var options, attachment;
			
			$self              = $(event.target);
			$fileUpload        = $self.closest('.dilaz-mb-file-upload');
			$fileWrapper       = $fileUpload.find('.dilaz-mb-file-wrapper');
			$fileWrapperParent = $fileUpload.parent();
			$fileId            = $fileWrapper.data('file-id') || '';
			$fileLibrary       = $self.data('file-library') || '';
			$fileFormat        = $self.data('file-format') || '';
			$fileMime          = $self.data('file-mime') || '';
			$fileSpecific      = $self.data('file-specific') || false;
			$fileMultiple      = $self.data('file-multiple') || false;
			$fileType          = $self.data('file-type') || '';
			$frameTitle        = $self.data('frame-title') || '';
			$frameButtonText   = $self.data('frame-button-text') || '';
			$mediaPreview      = $fileWrapperParent.find('.dilaz-mb-media-file');
			
			/* Restricts media uploaded to current postID only */
			$uploadedTo = ($fileSpecific == true) ? wp.media.view.settings.post.id : '';
			
			/* open frame if it exists */
			if ( imageFrame ) {
				imageFrame.open();
				return;
			}
			
			/* frame settings */
			imageFrame = wp.media({
				title    : $frameTitle,
				multiple : $fileMultiple,
				library  : {	
					type       : $fileType,
					uploadedTo : $uploadedTo 
				},
				button : {
					text : $frameButtonText
				}
			});
			
			/* frame select handler */
			imageFrame.on( 'select', function() {
				
				selection = imageFrame.state().get('selection');
				
				if (!selection)
					return;
				
				/* loop through the selected files */
				selection.each( function(attachment) {
					
					var type = attachment.attributes.type;
					
					if (type == 'image') {
						
						/* if uploaded image is smaller than default thumbnail(250 by 250)
						then get the full image url */
						if (attachment.attributes.sizes.thumbnail !== undefined) {
							var image_src = attachment.attributes.sizes.thumbnail.url;
						} else {
							var image_src = attachment.attributes.url;
						}
					}
					
					/* attachment data */
					var src     = attachment.attributes.url,
						id      = attachment.id,
						title   = attachment.attributes.title,
						caption = attachment.attributes.caption,
						type    = attachment.attributes.type;
						
					$fileWrapper.find('.dilaz_metabox_title_bg_image').val(title);
					$fileWrapper.find('.dilaz_metabox_caption_bg_image').val(caption);
					
					var $fileOutput = '';
					
					$fileOutput += '<div class="dilaz-mb-media-file '+ $fileType +'  '+ (id != '' ? '' : 'empty') +'" id="file-'+ $fileId +'">';
					$fileOutput += '<input type="hidden" name="'+ $fileId +'[]" id="file_'+ $fileId +'" class="dilaz-mb-file-id upload" value="'+ id +'">';
					$fileOutput += '<div class="filename '+ $fileType +'">'+ title +'</div>';
					$fileOutput += '<span class="sort ui-sortable-handle"></span>';
					$fileOutput += '<a href="#" class="remove" title="Remove"><i class="fa fa-close"></i></a>';
					
					switch ( type ) {
						case 'image':
							$fileOutput += '<img src="'+ image_src +'" class="dilaz-mb-file-preview file-image" alt="">';
							break;
							
						case 'audio':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/audio.png" class="dilaz-mb-file-preview file-audio" alt="">';
							break;
							
						case 'video':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/video.png" class="dilaz-mb-file-preview file-video" alt="">';
							break;
							
						case 'document':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/document.png" class="dilaz-mb-file-preview file-document" alt="">';
							break;
							
						case 'spreadsheet':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/spreadsheet.png" class="dilaz-mb-file-preview file-spreadsheet" alt="">';
							break;
							
						case 'interactive':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/interactive.png" class="dilaz-mb-file-preview file-interactive" alt="">';
							break;
							
						case 'text':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/text.png" class="dilaz-mb-file-preview file-text" alt="">';
							break;
							
						case 'archive':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/archive.png" class="dilaz-mb-file-preview file-archive" alt="">';
							break;
							
						case 'code':
							$fileOutput += '<img src="'+ dilaz_mb_lang.dilaz_mb_images +'media/code.png" class="dilaz-mb-file-preview file-code" alt="">';
							break;
							
					}
					
					$fileOutput += '</div>';
					
					if ($fileMultiple == true) {
						$fileWrapper.append($fileOutput);
					} else {
						$fileWrapper.html($fileOutput);
					}
				});
			});
			
			/* open frame */
			imageFrame.open();
		});
	});
	
	/* Remove file */
	$(document).find('.dilaz-mb-media-file').on('click', '.remove', function(e) {
		
		e.preventDefault();
		
		var $this = $(this);
		
		$this.siblings('input').attr('value', '');
		$this.parent('.dilaz-mb-media-file').slideUp(200);
		
		setTimeout(function() {
			$this.parent('.dilaz-mb-media-file').remove();
		}, 1000);
		
		return false;
	});
	
	/* File sorting, drag-and-drop */
	$('.dilaz-mb-file-wrapper').each(function() {
		
		var $this = $(this);
			$media = $this.find('.dilaz-mb-media-file');
			
		if ($media.length > 1) {
			$this.sortable({
				opacity : 0.6,
				revert : true,
				handle : '.sort',
				cursor : 'move',
				// axis: 'y',
				placeholder: 'ui-sortable-placeholder'
			});
			$('.dilaz-mb-file-wrapper').disableSelection();
		}
	});
	
	/* Radio image */
	$(function() {
		$('.dilaz-image-selector').click(function(){
			$(this).parent().parent().find('.dilaz-image-selector-img').removeClass('dilaz-image-selector-img-selected');
			$(this).siblings('.dilaz-image-selector-img').addClass('dilaz-image-selector-img-selected');
		});
		
		$('.dilaz-image-selector-img').show();
	});
	
	/* jQuery add-on for checking multiple classes in an element */
	$.fn.hasClasses = function() {
		for (var i =0; i < arguments.length; i++) {
			var classes = arguments[i].split(" ");
			for (var j = 0; j < classes.length; j++) {
				if (this.hasClass(classes[j])) {
					return true;
				}
			}
		}
		return false;
	}
	
	/* post status select */
	/* Show/Hide fields for specific post types */
	$(function() {
		
		$('.dilaz-mb-field').each(function() {
			
			var $optField = $(this);
			
			if ($optField.hasClasses('standard aside image gallery link quote status video audio chat')) {
				$optField.css('display', 'none');
			} else {
				$optField.css('display', 'block');
			}
		});
		
		var $postFormatInput = $('#post-formats-select input');
		
		$postFormatInput.change(function() {
			
			var $postFormat        = $(this),
				$postFormatVal     = $postFormat.val(),
				$postFormatOpt     = ($postFormatVal == 0) ? 'standard' : $postFormatVal,
				$mbTabContentField = $('.dilaz-mb-field');
			
			$mbTabContentField.each(function() {
				
				var $optField = $(this);
				
				if ($optField.hasClasses('standard aside image gallery link quote status video audio chat')) {
					if ($optField.hasClass($postFormatOpt)) {
						$optField.css('display', 'block');
					} else {
						$optField.css('display', 'none');
					}
				}
			});
		});
		
		$postFormatInput.each(function() {
			
			var $postFormat    = $(this);
				$postFormatVal = $postFormat.val(),
				$postFormatOpt = ($postFormatVal == 0) ? 'standard' : $postFormatVal;
				
			if ($postFormat.is(':checked')) {
				
				var $mbTabContentField = $('.dilaz-mb-field');
				
				$mbTabContentField.each(function() {
					
					var $optField = $(this);
					
					if ($optField.hasClass($postFormatOpt)) {
						$optField.css('display', 'block');
					} 
				});
			}
		});
    });
	
	/* jQuery add-on for checking prefixed class in an element */
	$.fn.hasClassPrefix = function(classPrefix) {
		for (var i = 0; i < this.length; i++) {
			if (('' + $(this[i]).attr('class')).indexOf(classPrefix) != -1)
				return true;
		}
		return false;
	}
		
	/* page template select */
	/* Show/Hide fields for specific page templates */
	$(function() {
		
		$('.dilaz-mb-field').each(function() {
			
			var $optField = $(this);
			
			if ($optField.hasClassPrefix('page-')) {
				$optField.css('display', 'none');
			} else {
				$optField.css('display', 'block');
			}
		});
		
		var $pageTemplateSelect = $('select#page_template');
		
		$pageTemplateSelect.on('change', function() {
			
			var $pageTemplate      = $(this),
				$pageTemplateVal   = $pageTemplate.val(),
				$pageTemplateOpt   = $pageTemplateVal.slice(0,-4), // remove .php file extension
				$mbTabContentField = $('.dilaz-mb-field');
				
			$mbTabContentField.each(function() {
				
				var $optField = $(this);
				
				if ($optField.hasClassPrefix('page-')) {
					if ($optField.hasClass($pageTemplateOpt)) {
						$optField.css('display', 'block');
					} else {
						$optField.css('display', 'none');
					}
				}
			});
		});
		
		/* automatically show fields for selected page template */
		$pageTemplateSelect.trigger('change');
    });
	
});