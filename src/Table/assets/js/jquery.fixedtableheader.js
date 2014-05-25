/* Copyright (c) 2009 Mustafa OZCAN (http://www.mustafaozcan.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * Version: 1.0.2
 * Requires: jquery.1.3+
 */
jQuery.fn.fixedtableheader = function (options) {
    var settings = jQuery.extend({
        headerrowsize: 1,
        highlightrow: false,
        highlightclass: "highlight"
    }, options);
    this.each(function (i) {
        var $tbl = $(this);
        var $tblhfixed = $tbl.find("tr:lt(" + settings.headerrowsize + ")");
        var headerelement = "th";
        if ($tblhfixed.find(headerelement).length === 0) headerelement = "td";
        if ($tblhfixed.find(headerelement).length > 0) {
            $tblhfixed.find(headerelement).each(function () {
                $(this).css("width", $(this).width());
            });
            var $clonedTable = $tbl.clone().empty();
            var tblwidth = GetTblWidth($tbl);
            $clonedTable.attr("id", "fixedtableheader" + i).addClass('table-cloned').css({
                "position": "fixed",
                "top": "0",
                "left": $tbl.offset().left
            }).append($tblhfixed.clone()).width(tblwidth).hide().appendTo($("body"));
            if (settings.highlightrow) $("tr:gt(" + (settings.headerrowsize - 1) + ")", $tbl).hover(function () {
                $(this).addClass(settings.highlightclass);
            }, function () {
                $(this).removeClass(settings.highlightclass);
            });
            $(window).scroll(function () {
                $clonedTable.css({
                    "position": "fixed",
                    "top": "0",
                    "left": $tbl.offset().left - $(window).scrollLeft()
                });
                var sctop = $(window).scrollTop();
                var elmtop = $tblhfixed.offset().top;
                if (sctop > elmtop && sctop <= (elmtop + $tbl.height() - $tblhfixed.height())) $clonedTable.show(); else $clonedTable.hide();
            });
            $(window).resize(function () {
                if ($clonedTable.outerWidth() != $tbl.outerWidth()) {
                    $tblhfixed.find(headerelement).each(function (index) {
                        var w = $(this).width();
                        $(this).css("width", w);
                        $clonedTable.find(headerelement).eq(index).css("width", w);
                    });
                    $clonedTable.width($tbl.outerWidth());
                }
                $clonedTable.css("left", $tbl.offset().left);
            });
        }
    });
    function GetTblWidth($tbl) {
        return $tbl.outerWidth();
    }
};

APP.behaviors.fixedtableheader = {
    attach: function (context, settings) {
        $('.sticky-enabled', context).once('fixed_table_header', function () {
            if (window.Modernizr !== undefined && Modernizr.touch && $(this).hasClass('no-mobile-sticky')) {
                return
            }

            $(this).fixedtableheader();
        });
    }
};

/** experimental syntax
APP.addBehavior(
    //name
    'fixed_table_header',

    //selector
    '.sticky-enabled',

    //callback for ".each()"
    function() {
        if (window.Modernizr !== undefined && Modernizr.touch && $(this).hasClass('no-mobile-sticky')) {
            return
        }

        $(this).fixedtableheader();
    },

    //required libraries, loaded only if the selector matches (optional)
    ['jquery.fixedtableheader']
)

APP.addBehavior = function(name, selector, callback, required) {
    APP.behaviors[name] = {
        attach: function(context, settings) {
            var matched = $(selector, context);

            if (!matched.length) {
                return;
            }

            if (!required) {
                matched.once(name, callback);
                return;
            }

            require(required, function() {
                matched.once(name, callback);
            });
        }
    }
}
*/
