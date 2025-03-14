/**
|| --------------------------------------------------------------------------------------------
|| Metabox JS
|| --------------------------------------------------------------------------------------------
||
|| @package    Dilaz Metabox
|| @subpackage Metabox
|| @since      Dilaz Metabox 1.0
|| @author     Rodgath, https://github.com/Rodgath
|| @copyright  Copyright (C) 2017, Rodgath LTD
|| @link       https://github.com/Rodgath/Dilaz-Metabox
|| @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
||
*/

var DilazMetaboxScript = new function() {

	"use strict";

	/**
	 * Global Variables
	 */
	var	$t   = this,
		$    = jQuery.noConflict(),
		$doc = $(document);

	/**
	 * DoWhen start
	 */
	$t.doWhen = function() {
		$doc.doWhen();
	}

	/**
	 * Tabs Content min-Height
	 */
	$t.tabMinHeight = function() {
		$(window).load(function() {
			$('.dilaz-metabox').each(function() {

				var	$this      = $(this),
					$navHeight = $this.find('.dilaz-mb-tabs-nav').height(),
					$content   = $this.find('.dilaz-mb-tabs-content');

				$content.css({'min-height':$navHeight+20});
			});
		});
	}

	/**
	 * Tabs
	 */
	$t.tabs = function() {

		var dilazMetabox = $('.dilaz-metabox');

		dilazMetabox.closest('.postbox').addClass('dilaz-mb-wrapper');

		if (dilazMetabox.hasClass('dilaz-mb-wp5')) {
			dilazMetabox.closest('.postbox').addClass('dilaz-mb-wp5-wrapper');
		}

		if (dilazMetabox.hasClass('dilaz-mb-wp6')) {
			dilazMetabox.closest('.postbox').addClass('dilaz-mb-wp6-wrapper');
		}

		dilazMetabox.find('.dilaz-mb-tabs-nav-item:first-of-type, .dilaz-meta-tab:first-of-type').addClass('active');

		$('.dilaz-mb-tabs').on('click', '.dilaz-mb-tabs-nav-item', function() {

			var	tabNav     = $(this),
				tabContent = tabNav.closest('.dilaz-mb-tabs').siblings().children().eq(tabNav.index());

			tabNav.addClass('active').siblings().removeClass('active');
			tabContent.addClass('active').siblings().removeClass('active');
		});
	}

	/**
	 * Hidden field
	 */
	$t.hiddenField = function() {
		$('div[data-dilaz-hidden="yes"]').hide();
	}

	/**
	 * Checkbox field
	 */
	$t.checkboxField = function() {
		$('.dilaz-mb-tabs-content').on('click', '.dilaz-mb-checkbox', function() {
			$(this).toggleClass('focus');
		});
	}

	/**
	 * Radio field
	 */
	$t.radioField = function() {
		$('.dilaz-mb-tabs-content').on('click', '.dilaz-mb-radio', function() {

			var $this = $(this);

			$this.addClass('focus');
			$this.parent().siblings().find('.dilaz-mb-radio').removeClass('focus');
		});
	}

	/**
	 * Switch and buttonset fields
	 */
	$t.switchAndButtonset = function() {
		$('.dilaz-mb-tabs-content').on('click', '.dilaz-mb-switch, .dilaz-mb-button-set', function() {

			var	$this = $(this),
				$thisPar = $this.parent();

			$thisPar.addClass('selected');
			$thisPar.siblings().removeClass('selected');
		});
	}

	/**
	 * UI slider field
	 */
	$t.uiSliderField = function() {
		$('.dilaz-mb-slider').each(function() {

			var	$this = $(this),
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
	}

	/**
	 * UI range field
	 */
	$t.uiRangeField = function() {
		$('.dilaz-mb-range').each(function() {

			var	$this      = $(this),
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
	}

	/**
	 * File upload field
	 */
	$t.fileUploadField = function() {
		$('.dilaz-mb-file-upload-button').each(function() {

			var imageFrame;

			$(this).on('click', function(event) {

				event.preventDefault();

				var options, attachment;

				var	$self              = $(event.target),
					$fileUpload        = $self.closest('.dilaz-mb-file-upload'),
					$fileWrapper       = $fileUpload.find('.dilaz-mb-file-wrapper'),
					$fileWrapperParent = $fileUpload.parent(),
					$fileId            = $fileWrapper.data('file-id') || '',
					$fileLibrary       = $self.data('file-library') || '',
					$fileFormat        = $self.data('file-format') || '',
					$fileMime          = $self.data('file-mime') || '',
					$fileSpecific      = $self.data('file-specific') || false,
					$fileMultiple      = $self.data('file-multiple') || false,
					$fileThumb         = $self.data('file-thumb') || false,
					$fileType          = $self.data('file-type') || '',
					$frameTitle        = $self.data('frame-title') || '',
					$frameButtonText   = $self.data('frame-button-text') || '',
					$mediaPreview      = $fileWrapperParent.find('.dilaz-mb-media-file');

				/* Restricts media uploaded to current postID only */
				var $uploadedTo = ($fileSpecific == true) ? wp.media.view.settings.post.id : '';

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

					var selection = imageFrame.state().get('selection');

					if (!selection)
						return;

					/* loop through the selected files */
					selection.each(function(attachment) {

						var type = attachment.attributes.type;

            var image_src;

						if (type === 'image') {
						  image_src = attachment.attributes.url; // Default to full URL

						  if (attachment.attributes.sizes && attachment.attributes.sizes.thumbnail) {
						    image_src = $fileThumb ? attachment.attributes.sizes.thumbnail.url : attachment.attributes.url;
						  }
						}

						/* attachment data */
						var src     = attachment.attributes.url,
							id      = attachment.id,
							title   = attachment.attributes.title,
							caption = attachment.attributes.caption;

						/* Add file source URL when file is selected */
						if (false == $fileMultiple) {
							$fileUpload.find('.dilaz-mb-file-url').val(src);
							$mediaPreview.remove();
						}

						$fileWrapper.find('.dilaz_metabox_title_bg_image').val(title);
						$fileWrapper.find('.dilaz_metabox_caption_bg_image').val(caption);

						var $fileOutput = '';

						$fileOutput += '<div class="dilaz-mb-media-file '+ $fileType +'  '+ (id != '' ? '' : 'empty') +'" id="file-'+ $fileId +'">';
						$fileOutput += '<input type="hidden" name="'+ $fileId +'[url][]" id="file_url_'+ $fileId +'" class="dilaz-mb-file-url upload" value="'+ src +'">';
						$fileOutput += '<input type="hidden" name="'+ $fileId +'[id][]" id="file_id_'+ $fileId +'" class="dilaz-mb-file-id upload" value="'+ id +'">';

						$fileOutput += '<div class="filename '+ $fileType +'">'+ title +'</div>';
						$fileOutput += '<span class="sort ui-sortable-handle"></span>';
						$fileOutput += '<a href="#" class="dilaz-mb-remove-file" title="Remove"><span class="mdi mdi-window-close"></span></a>';

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

						$fileWrapper.append($fileOutput);
					});
				});

				/* open frame */
				imageFrame.open();
			});
		});
	}

	/**
	 * Remove file
	 */
	$t.removeFile = function() {
		$doc.on('click', '.dilaz-mb-remove-file', function(e) {

			e.preventDefault();

			var $this = $(this);

			$this.siblings('input').attr('value', '');
			$this.parent('.dilaz-mb-media-file').slideUp(500);

			setTimeout(function() {
				$this.parent('.dilaz-mb-media-file').remove();
			}, 1000);

			return false;
		});
	}

	/**
	 * File sorting, drag-and-drop
	 */
	$t.fileSorting = function() {
		$('.dilaz-mb-file-wrapper').each(function() {

			var	$this     = $(this),
				$multiple = $this.data('file-multiple');

			if ($multiple) {
				$this.sortable({
					opacity : 0.6,
					revert : false,
					handle : '.sort',
					cursor : 'move',
					// axis: 'y',
					placeholder: 'ui-sortable-placeholder'
				});
				$('.dilaz-mb-file-wrapper').disableSelection();
			}
		});
	}

	/**
	 * Radio image field
	 */
	$t.radioImageField = function() {
		$('.dilaz-image-selector').click(function() {
			$(this).parent().parent().find('.dilaz-image-selector-img').removeClass('dilaz-image-selector-img-selected');
			$(this).siblings('.dilaz-image-selector-img').addClass('dilaz-image-selector-img-selected');
		});

		$('.dilaz-image-selector-img').show();
	}

	/**
	 * jQuery add-on for checking existence of multiple classes in an element
	 */
	$t.hasClasses = function() {
		for (var i = 0; i < arguments.length; i++) {
			var classes = arguments[i].split(" ");
			for (var j = 0; j < classes.length; j++) {
				if (this.hasClass(classes[j])) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * post status select
	 * Show/Hide fields for specific post types
	 */
	$t.postStatusSelect = function() {
		$('.dilaz-mb-field').each(function() {

			$.fn.hasClasses = function() {
				$t.hasClasses();
			}

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

			var $postFormat    = $(this),
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
	}

	/**
	 * jQuery add-on for checking prefixed class in an element
	 */
	$t.hasClassPrefix = function(classPrefix) {
		for (var i = 0; i < this.length; i++) {
			if (('' + $(this[i]).attr('class')).indexOf(classPrefix) != -1)
				return true;
		}
		return false;
	}

	/**
	 * page template select
	 * Show/Hide fields for specific page templates
	 */
	$t.pageTemplateSelect = function() {

		$('.dilaz-mb-field').each(function() {

			$.fn.hasClassPrefix = function(classPrefix) {
				$t.hasClassPrefix(classPrefix);
			}

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
	}

	/**
	 * Set Cookie
	 * @since Dilaz Metabox 2.5.7
	 */
	$t.setCookie = function(c_name, value, exdays) {
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value = escape(value)+((exdays==null) ? "" : ("; expires="+exdate.toUTCString()));
		document.cookie = c_name + "=" + c_value;
	}

	/**
	 * Get Cookie
	 * @since Dilaz Metabox 2.5.7
	 */
	$t.getCookie = function(c_name) {
		var i, x, y, ARRcookies=document.cookie.split(";");
		for (i = 0; i < ARRcookies.length; i++) {
			x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			y = ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			x = x.replace(/^\s+|\s+$/g, "");
			if (x == c_name) {
				return unescape(y);
			}
		}
	}

	/**
	 * Get JSON
	 * @since Dilaz Metabox 2.5.7
	 */
	$t.getJSON = function(file, callback) {

		$.getJSON(file).done(function(jasonData) {
			callback(jasonData);
		}).error(function() {

			var $alertJsonError = $t.getCookie('DilazMBAlertJsonError') || '';

			/* prevent multiple alert popups */
			if ($alertJsonError != 'yes') {
				$t.setCookie('DilazMBAlertJsonError', 'yes', (1/(24*60*60)) * 10); // cookie expires after 10 seconds
				alert('DilazMetabox Error: Please check your JSON file.');
			}
		});
	}

	/**
	 * Get Google fonts from JSON file
	 * @since Dilaz Metabox 2.5.7
	 */
	$t.updateGoogleFonts = function(sectionId, fontFamily, fontWeight, fontStyle, fontSubset) {

		var	$linkId    = sectionId+'-'+fontFamily.replace(/ /g, '-').toLowerCase(),
			$links     = document.getElementsByTagName('link'),
			fontURLarr = [],
			fontURL    = '';

		/* if Google Font resource link exists, delete it so that it can be updated */
		for (var i = 0; i < $links.length; i++) {
			if ($links[i].id == $linkId) {
				$links[i].remove();
			}
		}

		fontURLarr.push('https://fonts.googleapis.com/css?family=');
		fontURLarr.push(fontFamily.replace(/ /g, '+'));

		/* import Google Fonts */
		$t.getJSON(dilaz_mb_lang.dilaz_mb_url +'inc/google-fonts-min.json', function(jasonData) {
			// console.log(Object.keys(jasonData).length);
			// console.log(jasonData);
			// console.log(jasonData[fontFamily]);
			// console.log(jasonData[fontFamily].variants);
			// console.log(jasonData[fontFamily].subsets);

			/* Check if its a Google font selected */
			if (jasonData[fontFamily] !== undefined) {

				var	fontVariants    = jasonData[fontFamily].variants,
					checkFontStyle  = JSON.stringify(fontVariants).indexOf(fontStyle) > -1,
					checkFontWeight = JSON.stringify(fontVariants).indexOf(fontWeight) > -1,
					fontSubsets     = jasonData[fontFamily].subsets,
					checkFontSubset = JSON.stringify(fontSubsets).indexOf(fontSubset) > -1;

				if (checkFontStyle || checkFontWeight) {
					var theStyle  = (checkFontStyle) ? fontStyle : '',
						theWeight = (checkFontWeight) ? fontWeight : '';
					if (theStyle !== '' || theWeight !== '') {
						fontURLarr.push(':');
						fontURLarr.push(theStyle+theWeight);
					}
				}

				if (checkFontSubset && fontSubset !== '') {
					fontURLarr.push('&subset=');
					fontURLarr.push(fontSubset);
				}

				fontURL += fontURLarr.join('');

				if ($("link[href*='" + fontFamily + "']").length === 0) {

					var $headerLinkOutput = '';

					$headerLinkOutput += '<!-- Code snippet to speed up Google Fonts rendering: googlefonts.3perf.com -->';
					$headerLinkOutput += '<link rel="dns-prefetch" href="https://fonts.gstatic.com">';
					$headerLinkOutput += '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">';
					$headerLinkOutput += '<link rel="preload" id="'+$linkId+'" href="'+fontURL+'" as="fetch" crossorigin="anonymous">';
					$headerLinkOutput += '<script type="text/javascript"> !function(e,n,t){"use strict";var o="'+fontURL+'",r="__3perf_googleFonts_'+$linkId+'";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage); </script>';

					$('head>link:last').after($headerLinkOutput);

				}
			}
		});
	}

	/**
	 * Font preview
	 * @since Dilaz Metabox 2.5.7
	 */
	$t.fontPreview = function() {
		$('.dilaz-mb-field-font').each(function() {

			var	$this         = $(this),
				$fFamily      = $this.find('.family'),
				$fSubset      = $this.find('.subset'),
				$fWeight      = $this.find('.weight'),
				$fStyle       = $this.find('.style'),
				$fCase        = $this.find('.case'),
				$fSize        = $this.find('.f-size'),
				$fHeight      = $this.find('.f-height'),
				$metaboxColor = $this.find('.dilaz-mb-color'),
				$resultColor  = $this.find('.wp-color-result'),
				$fPreview     = $this.find('.font-preview'),
				$fContent     = $fPreview.find('.content'),
				$fColor       = $resultColor.css('background-color'),
				$bgColor      = $t.bgColorBasedOnTextColor($fColor, '#fbfbfb', '#222'),
				$fSectionId   = $this.closest('.dilaz-mb-field').attr('id');

			/* show preview */
			$fPreview.show();

			/* render already set values */
			$fContent.css({
				'font-family'      : $fFamily.val(),
				'font-weight'      : $fWeight.val(),
				'font-style'       : $fStyle.val(),
				'text-transform'   : $fCase.val(),
				'font-size'        : $fSize.val() +'px',
				'line-height'      : $fHeight.val() +'px',
				'color'            : $fColor,
				'background-color' : $bgColor,
			});

			/**
			 * show Google Font in preview if its saved
			 */
			$t.updateGoogleFonts($fSectionId, $fFamily.val(), $fWeight.val(), $fStyle.val(), $fSubset.val());

			$fFamily.on('change', function() {
				var $familyVal = $fFamily.val();
				var $defaultFonts = 'arial verdana trebuchet georgia times tahoma palatino helvetica';
				$fContent.css({'font-family':$familyVal});
				if ($defaultFonts.indexOf($familyVal) == -1) {
					$t.updateGoogleFonts($fSectionId, $fFamily.val(), $fWeight.val(), $fStyle.val(), $fSubset.val());
				}
			});

			$fSubset.on('change', function() {
				$fContent.css({'font-subset':$fSubset.val()});
				$t.updateGoogleFonts($fSectionId, $fFamily.val(), $fWeight.val(), $fStyle.val(), $fSubset.val());
			});

			$fWeight.on('change', function() {
				$fContent.css({'font-weight':$fWeight.val()});
				$t.updateGoogleFonts($fSectionId, $fFamily.val(), $fWeight.val(), $fStyle.val(), $fSubset.val());
			});

			$fStyle.on('change', function() {
				$fContent.css({'font-style':$fStyle.val()});
				$t.updateGoogleFonts($fSectionId, $fFamily.val(), $fWeight.val(), $fStyle.val(), $fSubset.val());
			});

			$fCase.on('change', function() {
				$fContent.css({'text-transform':$fCase.val()});
			});

			$fSize.on('keyup', function() {
				$fContent.css({'font-size':$fSize.val() +'px'});
			});

			$fHeight.on('keyup', function() {
				$fContent.css({'line-height':$fHeight.val() +'px'});
			});

			$metaboxColor.wpColorPicker({
				change: function(event, ui) {
					var textColor = ui.color.toString();
					$fContent.css({
						'color': textColor,
						'background-color': $t.bgColorBasedOnTextColor(textColor, '#fbfbfb', '#222')
					});
				}
			});
		});
	}

	/**
	 * Repeatable field - sortable
	 */
	$t.repeatableField = function() {
		$('.dilaz-mb-repeatable').sortable({
			opacity: 0.6,
			revert: false,
			handle: '.sort-repeatable',
			cursor: 'move',
			axis: 'y',
			update: function() {
				var i = 0;
				$(this).children().each(function(i) {
					$(this).find('input').attr('name', function(index, name) {
						return name.replace(/\[([^\]])\]/g, function(fullMatch, n) {
							return '['+Number(i)+']';
						});
					});
					i++;
				});
			}
		});
	}

	/**
	 * add new repeatable items in the repeatable field
	 */
	$t.addRepeatableField = function() {
		$('.dilaz-mb-add-repeatable-item').on('click', function() {
			var $this     = $(this),
				sorter    = '<span class="sort-repeatable"><i class="dashicons dashicons-move"></i></span>',
				remover   = '<span class="repeatable-remove button"><i class="dashicons dashicons-no-alt"></i></span>',
				rList     = $this.prev('.dilaz-mb-repeatable'),
				sortable  = rList.data('s'),
				nS        = rList.data('ns'),
				removable = rList.data('r'),
				nR        = rList.data('nr'),
				rListItem = rList.find('>li'),
				rClone    = rList.find('>li:last').clone(),
				rItems    = rListItem.length;

			rClone.each(function() {
				var $this = $(this);

				/* hide so that we can slidedown */
				$this.hide();

				/* clear all fields */
				$this.find('input').val('').attr('name', function(index, name) {
					return name.replace(/\[([^\]])\]/g, function(fullMatch, n) {
						return '['+(Number(n) + 1)+']';
					});
				});

				/* if items not-sortable is equal to number of shown items */
				if (nS <= rItems) {
					if (!$this.find('.sort-repeatable').length && sortable == true) {
						$this.prepend(sorter);
					}
				}

				/* if items not-repeatable is equal to number of shown items */
				if (nR == rItems || nR < 1) {
					if (!$this.find('.repeatable-remove').length && removable == true) {
						$this.append(remover);
					}
				}
			});
			$(rList).append(rClone);
			rClone.slideDown(100);
		});
	}

	/**
	 * remove repeatable field
	 */
	$t.removeRepeatableField = function() {
		$doc.on('click', '.repeatable-remove', function(e) {
			e.preventDefault();

			var $this = $(this),
				$parent = $this.parent();

			/* one item should always remain */
			if ($parent.siblings().length > 0) {
				$parent.slideUp(100);
				setTimeout(function() {
					$parent.remove();
				}, 1000);
			}

			return false;
		});
	}

	/**
	 * option-group field
	 */
	$t.optionGroupField = function () {

	  // Accordion functionality
	  $doc.on('click', '.dilaz-mb-opt-group-accordion-header', function () {
	    $(this).siblings('.dilaz-mb-opt-group-accordion-content').slideToggle();
	    // $(".dilaz-mb-opt-group-accordion-content").not($(this).next()).slideUp();
	  });

	  // Make accordion sortable
	  $("[id^=\'dilaz_mb_prefix_\']").sortable({
	    handle: '.drag-handle',
	    axis: 'y',
	    placeholder: 'sortable-placeholder'
	  }).disableSelection();

	}

	/**
	 * check if color is HEX, RGB, RGBA, HSL, HSLA
	 *
	 * @since Dilaz Metabox 2.5.7
	 * @link  https://stackoverflow.com/a/32685393
	 */
	$t.checkColor = function(color) {

		/* check HEX */
		var isHex = /^#(?:[A-Fa-f0-9]{3}){1,2}$/i.test(color);
		if (isHex) { return 'hex'; }

		/* check RGB */
		var isRgb = /^rgb[(](?:\s*0*(?:\d\d?(?:\.\d+)?(?:\s*%)?|\.\d+\s*%|100(?:\.0*)?\s*%|(?:1\d\d|2[0-4]\d|25[0-5])(?:\.\d+)?)\s*(?:,(?![)])|(?=[)]))){3}[)]$/i.test(color);
		if (isRgb) { return 'rgb'; }

		/* check RGBA */
		var isRgba = /^^rgba[(](?:\s*0*(?:\d\d?(?:\.\d+)?(?:\s*%)?|\.\d+\s*%|100(?:\.0*)?\s*%|(?:1\d\d|2[0-4]\d|25[0-5])(?:\.\d+)?)\s*,){3}\s*0*(?:\.\d+|1(?:\.0*)?)\s*[)]$/i.test(color);
		if (isRgba) { return 'rgba'; }

		/* check HSL */
		var isHsl = /^hsl[(]\s*0*(?:[12]?\d{1,2}|3(?:[0-5]\d|60))\s*(?:\s*,\s*0*(?:\d\d?(?:\.\d+)?\s*%|\.\d+\s*%|100(?:\.0*)?\s*%)){2}\s*[)]$/i.test(color);
		if (isHsl) { return 'hsl'; }

		/* check HSLA */
		var isHsla = /^hsla[(]\s*0*(?:[12]?\d{1,2}|3(?:[0-5]\d|60))\s*(?:\s*,\s*0*(?:\d\d?(?:\.\d+)?\s*%|\.\d+\s*%|100(?:\.0*)?\s*%)){2}\s*,\s*0*(?:\.\d+|1(?:\.0*)?)\s*[)]$/i.test(color);
		if (isHsla) { return 'hsla'; }

	}

	/**
	 * RGB to HEX
	 *
	 * @since Dilaz Metabox 2.5.7
	 */
	$t.rgbToHex = function(red, green, blue) {
		var rgb = blue | (green << 8) | (red << 16);
		return '#' + (0x1000000 + rgb).toString(16).slice(1);
	}

	/**
	 * HEX to RGB
	 *
	 * @since Dilaz Metabox 2.5.7
	 * @return Object|String
	 */
	$t.hexToRgb = function(hex) {
		/* Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF") */
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function(m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}

	/**
	 * HEX to RGBA
	 *
	 * @since Dilaz Metabox 2.5.7
	 * @return Object|String
	 */
	$t.hexToRgba = function(hex, opacity) {

		/* Set 'opacity' default parameter */
		if (!opacity) opacity = 1;

		if ($t.checkColor(hex) != 'hex') return null;

		/* convert the hex to RGB */
		var hexToRgb = $t.hexToRgb(hex);

		if (typeof opacity != 'undefined') {
			return $.extend(hexToRgb, {'o':opacity});
		}

		return null;
	}

	/**
	 * Background color based on text color
	 * @since Dilaz Metabox 2.5.7
	 */
	$t.bgColorBasedOnTextColor = function(textColor, lightColor, darkColor) {

		var	checkColor = $t.checkColor(textColor),
			rgb = null;

		if (checkColor == 'hex') {
			var hexToRgb = $t.hexToRgb(textColor);
			if (typeof(hexToRgb) === 'object' && hexToRgb != null) {
				var red   = hexToRgb.hasOwnProperty('r') ? hexToRgb.r : 0,
					green = hexToRgb.hasOwnProperty('g') ? hexToRgb.g : 0,
					blue  = hexToRgb.hasOwnProperty('b') ? hexToRgb.b : 0,
					rgb   = 'rgb('+red+', '+green+', '+blue+')';
			}
		} else if (checkColor == 'rgb') {
			var rgb = textColor;
		}

		if (rgb == null) return null;

		// var matches = rgb.match(/rgb\((\d+),\s?(\d+),\s?(\d+)\)/);
		var matches = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

		if (!matches) return null;

		if (matches != null && matches.length && matches.length > 3) {
			var c = 'rgb(' + matches[1] + ',' + matches[2] + ',' + matches[3] + ')';
			var o = Math.round(((parseInt(matches[1], 10) * 299) + (parseInt(matches[2], 10) * 587) + (parseInt(matches[3], 10) * 114)) / 1000);

			return (o > 125) ? darkColor : lightColor;
		}
	}

	/**
	 * Init
	 *
	 */
	$t.init = function() {

		$t.doWhen();
		$t.tabMinHeight();
		$t.tabs();
		$t.hiddenField();
		$t.checkboxField();
		$t.radioField();
		$t.switchAndButtonset();
		$t.uiSliderField();
		$t.uiRangeField();
		$t.fileUploadField();
		$t.removeFile();
		$t.fileSorting();
		$t.radioImageField();
		$t.postStatusSelect();
		$t.pageTemplateSelect();
		$t.fontPreview();
		$t.repeatableField();
		$t.addRepeatableField();
		$t.removeRepeatableField();
		$t.optionGroupField();

	};
}

jQuery(document).ready(function($) {

	DilazMetaboxScript.init();

});