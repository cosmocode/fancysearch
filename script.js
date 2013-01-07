jQuery(function(){
    /*
     * SEARCH-BOX DROPDOWN
     */

    if (!jQuery('#fancysearch__input, #fancysearch__ns_custom')) return;

    // Replace HTML dropdown with the icon dropdown, but keep the current
    // value.

    var oldNamespaceSelect = jQuery(".fancysearch_namespace");
    var newNamespaceSelect = jQuery('<input class="fancysearch_namespace" type="hidden" ' +
        'name="namespace" value="' +
        oldNamespaceSelect.val() +
        '" />');

    oldNamespaceSelect.replaceWith(newNamespaceSelect);

    var cur = '.fancysearch_ns_' + oldNamespaceSelect.val();
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
            jQuery(".fancysearch_namespace").val(tgt.innerHTML);
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
