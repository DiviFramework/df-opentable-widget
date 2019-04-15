function initOpentable(){
    jQuery('.df-opentable').each(function(){
        var el = jQuery(this);
        if(!el.data('opentable-init')){
            var src = el.data('src');
            var html = '<script type="text/javascript" ' + 'src="' + src + '"></script>';
            el.append(html);
            el.data('opentable-init', true);   
        }
    });
}


jQuery(document).ready(function($) {
    initOpentable();
});