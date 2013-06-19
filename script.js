jQuery(function(){
    /*
     * SEARCH-BOX DROPDOWN
     */

    if (!jQuery('#fancysearch__input, #fancysearch__ns_custom')) return;

    // Replace HTML dropdown with the icon dropdown, but keep the current
    // value.

    // replace dropdown with hidden field
    var $oldNamespaceSelect = jQuery(".fancysearch_namespace");
    var curNS = $oldNamespaceSelect.val();
    var $newNamespaceSelect = jQuery('<input class="fancysearch_namespace" type="hidden" ' +
                                    'name="namespace" value="' + curNS +
                                    '" />');
    $oldNamespaceSelect.replaceWith($newNamespaceSelect);

    // show the picker
    var $nspicker = jQuery('#fancysearch__ns_custom').show();

    // scroll the picker to the position of the current namespace
    var $curItem = jQuery('.fancysearch_ns_' + curNS);
    $curItem.parent().css('top', ($curItem.prevAll().size()*-31) + 'px');

    // add picker mechanics
    $nspicker.click(function(evt) {
        var $picker = jQuery(this);

        if ($picker.hasClass('closed')) {
            $picker.removeClass('closed');
        } else {
            $picker.addClass('closed');

            var tgt = evt.target;
            jQuery(".fancysearch_namespace").val(tgt.innerHTML);
            jQuery(tgt).parent().animate({'top': (jQuery(tgt).prevAll().size()*-31) + 'px' },"slow");
        }
    });

    // Support qsearch
    jQuery('#fancysearch__input').keyup(function (evt) {
        var ns = jQuery(".fancysearch_namespace").val();
        var $qin = jQuery('#qsearch__in');
        var val = jQuery(this).val();
        if (ns !== '') $val += ' @' + ns;
        $qin.val(val);
        $qin.keyup();
    });
});
