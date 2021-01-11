/**
 * spartan-multi-image-picker.js
 * Repo : https://github.com/adispartadev/spartan-multi-image-picker
 * Created By I Wayan Adi Sparta, 2018
 */
(function ($) {
    "use strict";

    var ADDICON = 'https://via.placeholder.com/230x230?text=No+Image';
    $.fn.spartanMultiImagePicker = function(options) {

        var count       = 0; 
        var last_index  = 0;
        var total_count = 0;

        var defaults = {
            fieldName:        '',
            groupClassName:   'col-md-4 col-sm-4 col-xs-6',
            rowHeight:        '200px',
            dropFileLabel:    'Drop file here',
            placeholderImage: {
                image : ADDICON,
                width : '230px'
            },
            maxCount:          '',
            maxFileSize:       '',
            allowedExt:        'png|jpg|jpeg|gif',
            onAddRow:          function() {},
            onRenderedPreview: function() {},
            onRemoveRow:       function() {},
            onExtensionErr:    function() {},
            onSizeErr:         function() {},
            directUpload :  {
                loaderIcon: '<i class="fas fa-sync fa-spin"></i>',
                status:       false,
                url:          '',
                success:      function() {},
                error:        function() {}
            },
        };

        var settings = $.extend( {}, defaults, options );


        /**
         * CALLED ON IMAGE SUCCESS RENDERED AND ADD NEW IMAGE
         * @param {[type]} settings [description]
         * @param {[type]} el       [description]
         */
        function addRow(settings, el){
            last_index = count;
            var groupClassName = settings.groupClassName, rowHeight = settings.rowHeight, fieldName = settings.fieldName, placeholderImage = settings.placeholderImage, dropFileLabel = settings.dropFileLabel;
            var placeholderImageTarget = placeholderImage.image;
            var placeholderImageWidth  = '64px';

            var uploadLoaderIcon = '<i class="fas fa-sync fa-spin"></i>';
            if(typeof settings.directUpload.loaderIcon != 'undefined'){
                uploadLoaderIcon = settings.directUpload.loaderIcon;
            }

            if(typeof placeholderImage.width != 'undefined'){
                placeholderImageWidth = placeholderImage.width;
            }
            var template = `<div class="${groupClassName} spartan_item_wrapper" data-spartanindexrow="${count}" style="margin-bottom : 20px; ">`+
                                `<div style="position: relative;">`+
                                    `<div class="spartan_item_loader" data-spartanindexloader="${count}" style=" position: absolute; width: 100%; height: ${rowHeight}; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE">` +
                                        `${uploadLoaderIcon}` +
                                    `</div>`+
                                    `<label class="file_upload" style="width: 100%; height: ${rowHeight}; border: 2px dashed #ced4da; border-radius: 4px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;">`+
                                        `<a href="javascript:void(0)" data-spartanindexremove="${count}" style="position: absolute !important; right : 3px; top: 3px; display : none; background : #ED3C20; border-radius: 3px; width: 30px; height: 30px; line-height : 30px; text-align: center; text-decoration : none; color : #FFF;" class="spartan_remove_row"><i class="fas fa-times"></i></a>`+
                                        `<img style="width: ${placeholderImageWidth}; margin: 0 auto; vertical-align: middle;" data-spartanindexi="${count}" src="${placeholderImageTarget}" class="spartan_image_placeholder" /> `+
                                        `<p data-spartanlbldropfile="${count}" style="color : #5FAAE1; display: none; width : auto; ">${dropFileLabel}</p>`+
                                        `<img style="width: 100%; vertical-align: middle; display:none;" class="img_" data-spartanindeximage="${count}">`+
                                        `<input class="form-control spartan_image_input" multiple accept="image/*" data-spartanindexinput="${count}" style="display : none"  name="${fieldName}" type="file">`+
                                   `</label> `+
                                `</div>`+
                           `</div>`;
            
            var html = $.parseHTML(template);

            $(el).append(html);
            count++;
            var param = {
                index : count,
                last_index : last_index
            };
            settings.onAddRow.call(this, count);
        }


        /**
         * CALLED ON IMAGE RENDERED 
         * @param  {[type]} settings [description]
         * @param  {[type]} input    [description]
         * @param  {[type]} parent   [description]
         * @return {[type]}          [description]
         */
        function loadImage(settings, input, parent){
            var index = $(input).data('spartanindexinput');

            if (input.files && input.files[0]) {

                var file_select = input.files[0], allowedExt = settings.allowedExt, maxFileSize = settings.maxFileSize;
                var file_select_type = file_select.type,
                    regex = new RegExp(`(.*?)\.(${allowedExt})$`);

                if(regex.test(file_select_type) || allowedExt == ''){

                    if((maxFileSize == '') ||  (maxFileSize != '' && file_select.size <= maxFileSize)){

                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(parent).find('img[data-spartanindexi="'+index+'"]').hide();
                            $(parent).find('a[data-spartanindexremove="'+index+'"]').show();
                            $(parent).find('img[data-spartanindeximage="'+index+'"]').attr('src', e.target.result);
                            $(parent).find('img[data-spartanindeximage="'+index+'"]').show();
                            settings.onRenderedPreview.call(this, index);

                            // on upload
                            if(settings.directUpload.status == true){
                                actionDirectUpload(settings, input, parent);
                            }
                        };

                        reader.readAsDataURL(input.files[0]);
                        var is_add = false;
                        if( $(parent).find('img[data-spartanindeximage="'+index+'"]').is(":visible")  == false){
                            total_count++;
                            is_add = true;
                        }
                        if(index == (count - 1) && is_add ){
                            if(settings.maxCount == ''){
                                addRow(settings, parent);
                            }
                            else if(settings.maxCount != '' && total_count < settings.maxCount ){
                                addRow(settings, parent);
                            }
                        }
                    }
                    else if(maxFileSize != '' && file_select.size > maxFileSize){
                        
                        if( $(parent).find('img[data-spartanindeximage="'+index+'"]').is(":visible")  == true){
                            $(parent).find('img[data-spartanindexi="'+index+'"]').hide();
                        }
                        settings.onSizeErr.call(this, index, file_select);
                        return false;
                    }

                }
                else{
                    if( $(parent).find('img[data-spartanindeximage="'+index+'"]').is(":visible")  == true){
                        $(parent).find('img[data-spartanindexi="'+index+'"]').hide();
                    }
                    settings.onExtensionErr.call(this, index, file_select);
                    return false;
                }

            }
        }


        /**
         * CALLED ON UPLOAD IS ON
         * @param  {[type]} settings [description]
         * @param  {[type]} input    [description]
         * @param  {[type]} parent   [description]
         * @return {[type]}          [description]
         */
        function actionDirectUpload(settings, input, parent){
            var index = $(input).data('spartanindexinput');
            var formData = new FormData();
            var file = input.files[0];
            var additionalParam = settings.directUpload.additionalParam;
            $(parent).find('[data-spartanindexloader="'+index+'"]').css('display', 'flex');
            formData.append('file', file);

            if(typeof additionalParam != 'undefined'){
                $.each(additionalParam, function(key, value){
                    formData.append(key, value);
                });
            }

            $.ajax({
                url: settings.directUpload.url,
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(data, textStatus, jqXHR){
                    $(parent).find('[data-spartanindexloader="'+index+'"]').css('display', 'none');

                    if(typeof settings.directUpload.success != 'undefined'){
                        settings.directUpload.success(this, data, textStatus, jqXHR);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $(parent).find('[data-spartanindexloader="'+index+'"]').css('display', 'none');
                    if(typeof settings.directUpload.error != 'undefined'){
                        settings.directUpload.error(this, jqXHR, textStatus, errorThrown);
                    }
                }
            });

        }


        /**
         * CALLED ON CLOSE BUTTON CLICK
         * @param  {[type]} settings [description]
         * @param  {[type]} input    [description]
         * @param  {[type]} parent   [description]
         * @return {[type]}          [description]
         */
        function removeRow(settings, input, parent){
            var index = $(input).data('spartanindexremove');
            $(parent).find('[data-spartanindexrow="'+index+'"]').remove();
            if (last_index == index  || $(parent).find('img[data-spartanindeximage="'+last_index+'"]').is(":visible")  == true){
                addRow(settings, parent);
            }
            total_count--;
            settings.onRemoveRow.call(this, index);
        }



        /**
         * CALLED ON HOVER THE BOX
         * @param  {[type]} parent [description]
         * @return {[type]}        [description]
         */
        function onDragEnter(parent){
            var index = $(parent).data('spartanindexrow');
            $(parent).find('.file_upload').css({'border-color': '#5FAAE1', 'background' : '#DBE9F3'});
            if( $(parent).find('img[data-spartanindeximage="'+index+'"]').is(":visible")  == false){
                $(parent).find('p[data-spartanlbldropfile="'+index+'"]').show();
                $(parent).find('img[data-spartanindexi="'+index+'"]').hide();
            }
        }




        /**
         * ON LEAVE BOX
         * @param  {[type]} parent [description]
         * @return {[type]}        [description]
         */
        function onDragLeave(parent){
            var index = $(parent).data('spartanindexrow');
            $(parent).find('.file_upload').css({'border-color': '#ddd', 'background' : 'none'});
            if( $(parent).find('img[data-spartanindeximage="'+index+'"]').is(":visible")  == false){
                $(parent).find('p[data-spartanlbldropfile="'+index+'"]').hide();
                $(parent).find('img[data-spartanindexi="'+index+'"]').show();
            }
        }



        /**
         * DROP IMAGE TO BOX
         * 1. GET THE FILE
         * 2. RESET STYLING
         * 3. RENDER THE IMAGE
         * @param  {[type]} setting [description]
         * @param  {[type]} input   [description]
         * @param  {[type]} parent  [description]
         * @param  {[type]} evt     [description]
         * @return {[type]}         [description]
         */
        function onDropImage(setting, input, parent, evt){
            var index = $(input).data('spartanindexrow');
            var file_p = $(parent).find('.spartan_image_input[data-spartanindexinput="'+index+'"]');
            file_p.files = evt.originalEvent.dataTransfer.files;

            // clear on hover style
            $(input).find('.file_upload').css({'border-color': '#ddd', 'background' : 'none'});
            $(input).find('p[data-spartanlbldropfile="'+index+'"]').hide();
            $(input).find('img[data-spartanindexi="'+index+'"]').show();

            loadImage(settings, file_p, parent);
        }



        return this.each( function() {
            var that = this;
            addRow(settings, that);

            $(this).on("change", ".spartan_image_input", function(){
                loadImage(settings, this, that);
            });

            $(this).on("click", ".spartan_remove_row", function(){
                removeRow(settings, this, that);
            });

           
            $(this).on("dragenter dragover dragstart", '.spartan_item_wrapper', function(event){
                event.stopPropagation();
                event.preventDefault();
                onDragEnter(this);
            });

            $(this).on("dragleave", '.spartan_item_wrapper', function(){
                onDragLeave(this);
            });


            $(this).on("drop", '.spartan_item_wrapper', function(event){
                event.stopPropagation();
                event.preventDefault();
                onDropImage(settings, this, that, event);
            });

        });
    };
})(jQuery);