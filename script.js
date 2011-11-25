addInitEvent(function(){
    /*
     * SEARCH-BOX DROPDOWN
     */

    if (!$('fancysearch__input') || !$('fancysearch__ns_custom')) return;

    // Replace HTML dropdown with the icon dropdown, but keep the current
    // value.
    jQuery(".fancysearch_namespace")
          .replaceWith(jQuery('<input class="fancysearch_namespace" type="hidden" ' +
                              'name="namespace" value="' +
                              jQuery(".fancysearch_namespace").val() +
                              '" />'));
    var cur = '.' + jQuery(".fancysearch_namespace").val() + '_fancysearch';
    jQuery(cur).parent().css('top', (jQuery(cur).prevAll().size()*-31) + 'px');

    var nspicker = $('fancysearch__ns_custom');
    nspicker.style.display = '';
    addEvent(nspicker, 'click', function(evt) {
        var closed = this.className.match(/(^|\s)closed(\s|$)/);
        if (closed) {
            this.className = this.className.replace(/(^|\s)closed(\s|$)/g, '');
        } else {
            this.className += ' closed';

            var tgt = evt.target;
            jQuery(".fancysearch_namespace").val(tgt.className.match(/^fancysearch_ns_(.*)$/)[1]);
            jQuery(tgt).parent().animate({'top': (jQuery(tgt).prevAll().size()*-31) + 'px' },"slow");

        }
    });

    // Support qsearch
    addEvent($('fancysearch__input'), 'keyup', function (evt) {
        var ns = jQuery(".fancysearch_namespace").val();
        var qin = $('qsearch__in');
        qin.value = this.value;
        if (ns !== '') qin.value += ' @' + ns;
        jQuery(qin).trigger('keyup');
    });
});
