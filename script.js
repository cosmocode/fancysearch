addInitEvent(function(){
    /*
     * SEARCH-BOX DROPDOWN
     */

    // Replace HTML dropdown with the icon dropdown, but keep the current
    // value.
    jQuery(".fancysearch_namespace")
          .replaceWith(jQuery('<input class="fancysearch_namespace" type="hidden" ' +
                              'name="namespace" value="' +
                              jQuery(".fancysearch_namespace").val() +
                              '" />'));
    var cur = '.' + jQuery(".fancysearch_namespace").val() + '_fancysearch';
    jQuery(cur).parent().css('top', (jQuery(cur).prevAll().size()*-31) + 'px');

    jQuery("#fancysearch__ns_custom").show();
    jQuery("#fancysearch__ns_custom").live("click", function(){
        jQuery(this).toggleClass("closed");
    });
    jQuery("#fancysearch__ns_custom:not(.closed) li").live("click", function(){
        jQuery(".fancysearch_namespace").val(jQuery(this).attr("class").match(/(?:(\w*)_fancysearch|^()$)/)[1]);
        jQuery(this).parent().animate({'top': (jQuery(this).prevAll().size()*-31) + 'px' },"slow");
    });

    // Support qsearch
    addEvent($('fancysearch__input'), 'keyup', function (evt) {
        var ns = jQuery(".fancysearch_namespace").val();
        var qin = $('qsearch__in');
        qin.value = this.value;
        if (ns !== '') qin.value += ' @' + ns;
        qin.events.keyup[1].call(qin, evt);
    });
});
