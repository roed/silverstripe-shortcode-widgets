jQuery(document).ready(function () {
    jQuery('.minislideshow').each(function () {
        var holder = jQuery(this);
        holder.css('position', 'relative');
        holder.width(200);
        holder.height(200);
        holder.find('img').each(function () {
            var i = jQuery(this);
            i.css('position', 'absolute');
            i.css('top', '0');
            i.css('left', '0');
            i.hide();
        });
        holder.find('img:first-child').show();
        if (holder.find('img').length > 1) {
            setInterval(function () {
                var current = holder.find('img:visible');
                var next = current.next();
                if (next.length == 0) {
                    next = holder.find('img:first-child');
                }
                current.fadeOut();
                next.fadeIn();
            }, 2000);
        }
    });
});