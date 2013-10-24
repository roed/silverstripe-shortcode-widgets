<% require javascript(shortcodewidgets/thirdparty/swfobject/swfobject.js) %>
<script type="text/javascript">
        swfobject.embedSWF("$FlashFile.URL", "flashwidget_{$ID}", "$Width", "$Height", "9.0.0", "{$baseHref}shortcodewidgets/thirdparty/swfobject/expressInstall.swf");
    </script>
<div id="flashwidget_{$ID}">
    $AlternativeContent
</div>