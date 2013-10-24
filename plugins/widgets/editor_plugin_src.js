(function () {

    // TinyMCE will stop loading if it encounters non-existent external script file
    // when included through tiny_mce_gzip.php. Only load the external lang package if it is available.
    var availableLangs = ['en', 'nl'];
    if (jQuery.inArray(tinymce.settings.language, availableLangs) != -1) {
        tinymce.PluginManager.requireLangPack("widgets");
    }

    tinymce.create('tinymce.plugins.widgets', {
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @returns Name/value array containing information about the plugin.
         * @type Array
         */
        getInfo:function () {
            return {
                longname:'Widget shortcodes',
                author:'Michel van der Steege',
                authorurl:'http://www.michelvandersteege.nl',
                infourl:'http://www.michelvandersteege.nl',
                version:"1.0"
            };
        },

        init:function (ed, url) {

            var _this = this;
            var _ed = ed;

            if (ed.settings.content_css !== false){
                ed.contentCSS.push(url + '/css/content.css');
            }

            ed.addButton('widgets', {title:ed.getLang('widgets.desc'), cmd:'mceWidgets', 'image':url + '/img/icon.png'});

            ed.addCommand('mceWidgets', function (ed) {
                var closeButton = '<div class="ui-widget-header"><a href="#" class="ui-dialog-titlebar-close ui-corner-all" role="button"><span class="ui-icon ui-icon-closethick">close</span></a></div>';
                var lightbox = '<div id="widgets-lightbox"><div id="widgets-lightbox-content"><h2>Widgets</h2>' + closeButton + '<div id="widgets-lightbox-formholder">'+_ed.getLang('widgets.loading')+'</div></div></div>';
                jQuery('body').append(lightbox);
            });

            ed.onNodeChange.add(function(ed, cm, n) {
                tinyMCE.activeEditor.controlManager.setDisabled('widgets', jQuery(n).hasClass('widget-wrapper'));
			});

            //replace div to shortcode
            ed.onPostProcess.add(function (ed, o) {
                var content = jQuery('<div/>').append(o.content);
                content.find('.widget-wrapper').each(function() {
                    var el = jQuery(this);
                    var widgetID = el.data('widgetid');
                    var widgetType = el.data('widgettype');
                    var type = '';
                    if(widgetType){
                        type = widgetType.replace(' ', '-');
                    }
                    var widgetdiv = '<div class="widget-wrapper">[widget id='+widgetID+']'+type+'[/widget]</div>';
                    el.replaceWith(widgetdiv);
                });
                var last = content.find('.widget-wrapper').last();
                var next = last.next();
                if(last.length > 0 && next.length > 0 && next.html() == '&nbsp;'){
                    next.remove();
                }
                o.content = content.html();
            });

            //replace shortcode to div
            ed.onBeforeSetContent.add(function (ed, o) {
                var regex = /\[widget(.+?)?\](?:(.+?)?\[\/widget\])?/;
                var content = jQuery('<div/>').append(o.content);
                content.find('.widget-wrapper').each(function(){
                    var widgetEl = jQuery(this);
                    widgetEl.addClass('mceNonEditable');
                    var shortcode = widgetEl.html();
                    var params = {};
                    var regexResult = shortcode.match(regex);
                    var paramsString = regexResult[1];
                    var paramsPairs = paramsString.split(' ');
                    var type = regexResult[2];
                    paramsPairs.shift();
                    for(var i = 0; i < paramsPairs.length; i++){
                        var paramData = paramsPairs[i].split('=');
                        params[paramData[0]] = paramData[1];
                    }
                    widgetEl.attr('data-widgetid', params.id);
                    var shortcodeHTML = 'Widget - id: ' + params.id;
                    if(type){
                        widgetEl.attr('data-widgettype', type);
                        shortcodeHTML += ', type: ' + type.replace('-', ' ');
                    }
                    widgetEl.html(shortcodeHTML);
                });
                var last = content.find('.widget-wrapper:last-child');
                if(last.length > 0 && last.next().length == 0){
                    o.content = content.html() + '<p></p>';
                }else{
                    o.content = content.html();
                }
            });
        }
    });

    // Adds the plugin class to the list of available TinyMCE plugins
    tinymce.PluginManager.add("widgets", tinymce.plugins.widgets);


    jQuery('#widgets-lightbox').entwine({
        onadd:function () {
            //vars
            var _this = this;
            var content = this.find('#widgets-lightbox-content');
            //set size
            content.width(jQuery(window).width() * 0.8);
            content.css('height', 'auto');
            //close button
            content.find('.ui-dialog-titlebar-close').click(function (e) {
                e.preventDefault();
                _this.close();
            });
            //load form
            var url = jQuery('base').attr('href') + 'shortcodewidgets/forlightbox';
            jQuery.ajax({
                url:url,
                success:function (html) {
                    jQuery('#widgets-lightbox-formholder').html(html);
                    var selectedType = jQuery('#widgetType select').val();
                    jQuery('#widgetType select option').each(function(index, element){
                        var currType = jQuery(element).val();
                        if(currType != selectedType){
                            jQuery('#existingWidget_' + currType).hide();
                        }
                    });
                    jQuery('#widgetType select').change(function(){
                        jQuery('div[id^="existingWidget_"]').hide();
                        jQuery('#existingWidget_' + jQuery(this).val()).fadeIn();
                    });
                    _this.ajaxForm();
                }
            });
            //super
            this._super();
        },

        close:function () {
            this.remove();
        },

        ajaxForm:function () {
            var _this = this;
            jQuery('#widgets-lightbox-formholder').find('form').each(function () {
                jQuery(this).submit(function () {
                    jQuery.post(this.action, jQuery(this).serialize(), function (result) {
                        var isJson = result.substr(0, 1) == '{';
                        if(result == ''){
                            _this.close();
                        }else{
                            var json = jQuery.parseJSON(result);
                            _this.insertWidget(json.ID, json.type);
                            _this.close();
                        }
                    });
                    return false;
                });
            });
        },

        insertWidget:function (id, type) {
            var safeType = type.replace(' ', '-');
            var shortcode = '<div class="widget-wrapper" data-widgetid="'+id+'" data-widgettype="'+safeType+'">[widget id='+id+' type='+safeType+']safeType[/widget]</div>';
            tinyMCE.activeEditor.execCommand('mceInsertContent', false, shortcode);
            tinyMCE.activeEditor.setContent(tinyMCE.activeEditor.getContent(), {skip_undo : 1});
        }
    });


})();