$(document).ready(function(){

    if(window.location.hash) {
        var hash = window.location.hash;
        var segs = hash.split('/');
        $(window).scrollTo(segs[0]);
        if(segs[1].substr(0,1) == 'h') {
            $(segs[1].substr(1).split(',')).each(function(i,id){
                $('#'+id).toggleClass('selected');
            })
        }
    }
    
    $('ul.lines li').hover(function(){ $(this).toggleClass('hover'); });
    $('ul.lines li').click(function(e){
        if($(e.target).is("a")){ return; }
        $(this).toggleClass('selected'); 
        var all_selected = $(this).parent().find('.selected');
        if(all_selected.length) {
            var sel_ids = [];
            all_selected.each(function(i,el){
                sel_ids.push($(el).attr('id'));
            });
            var hash = sel_ids[0]+'/h'+sel_ids.join(',');
            window.location.hash = hash;
        } else {
            window.location.hash = '';
        }
    });

});