var APP = APP || {
    'settings': {},
    'behaviors':{},
    'locale':{},
    'utilities':{},
    'ajax':{}
};

/**
 * Attach all registered behaviors to a page element.
 *
 * Behaviors are event-triggered actions that attach to page elements, enhancing
 * default non-Javascript UIs. Behaviors are registered in the APP.behaviors
 * object using the method 'attach' and optionally also 'detach' as follows:
 * @code
 *    APP.behaviors.behaviorName = {
 *      attach: function (context, settings) {
 *        ...
 *      },
 *      detach: function (context, settings, trigger) {
 *        ...
 *      }
 *    };
 * @endcode
 *
 * APP.attachBehaviors is added below to the jQuery ready event and so
 * runs on initial page load. Developers implementing AHAH/AJAX in their
 * solutions should also call this function after new page content has been
 * loaded, feeding in an element to be processed, in order to attach all
 * behaviors to the new content.
 *
 * Behaviors should use
 * @code
 *   $(selector).once('behavior-name', function () {
 *     ...
 *   });
 * @endcode
 * to ensure the behavior is attached only once to a given element. (Doing so
 * enables the reprocessing of given elements, which may be needed on occasion
 * despite the ability to limit behavior attachment to a particular element.)
 *
 * @param context
 *   An element to attach behaviors to. If none is given, the document element
 *   is used.
 * @param settings
 *   An object containing settings for the current context. If none given, the
 *   global APP.settings object is used.
 */
APP.attachBehaviors = function (context, settings) {
    context = context || document;
    settings = settings || APP.settings;
    // Execute all of them.
    $.each(APP.behaviors, function () {
        if ($.isFunction(this.attach)) {
            this.attach(context, settings);
        }
    });
};

/**
 * Detach registered behaviors from a page element.
 *
 * Developers implementing AHAH/AJAX in their solutions should call this
 * function before page content is about to be removed, feeding in an element
 * to be processed, in order to allow special behaviors to detach from the
 * content.
 *
 * Such implementations should look for the class name that was added in their
 * corresponding APP.behaviors.behaviorName.attach implementation, i.e.
 * behaviorName-processed, to ensure the behavior is detached only from
 * previously processed elements.
 *
 * @param context
 *   An element to detach behaviors from. If none is given, the document element
 *   is used.
 * @param settings
 *   An object containing settings for the current context. If none given, the
 *   global APP.settings object is used.
 * @param trigger
 *   A string containing what's causing the behaviors to be detached. The
 *   possible triggers are:
 *   - unload: (default) The context element is being removed from the DOM.
 *   - move: The element is about to be moved within the DOM (for example,
 *     during a tabledrag row swap). After the move is completed,
 *     APP.attachBehaviors() is called, so that the behavior can undo
 *     whatever it did in response to the move. Many behaviors won't need to
 *     do anything simply in response to the element being moved, but because
 *     IFRAME elements reload their "src" when being moved within the DOM,
 *     behaviors bound to IFRAME elements (like WYSIWYG editors) may need to
 *     take some action.
 *   - serialize: When an AJAX form is submitted, this is called with the
 *     form as the context. This provides every behavior within the form an
 *     opportunity to ensure that the field elements have correct content
 *     in them before the form is serialized. The canonical use-case is so
 *     that WYSIWYG editors can update the hidden textarea to which they are
 *     bound.
 *
 * @see APP.attachBehaviors
 */
APP.detachBehaviors = function (context, settings, trigger) {
    context = context || document;
    settings = settings || APP.settings;
    trigger = trigger || 'unload';
    // Execute all of them.
    $.each(APP.behaviors, function () {
        if ($.isFunction(this.detach)) {
            this.detach(context, settings, trigger);
        }
    });
};

/**
 * Encode special characters in a plain-text string for display as HTML.
 */
APP.checkPlain = function (str) {
    var character, regex,
        replace = {
            '&': '&amp;',
            '"': '&quot;',
            '<': '&lt;',
            '>': '&gt;'
        };
    str = String(str);
    for (character in replace) {
        if (replace.hasOwnProperty(character)) {
            regex = new RegExp(character, 'g');
            str = str.replace(regex, replace[character]);
        }
    }
    return str;
};

/**
 * Replace placeholders with sanitized values in a string.
 *
 * @param str
 *   A string with placeholders.
 * @param args
 *   An object of replacements pairs to make. Incidences of any key in this
 *   array are replaced with the corresponding value. Based on the first
 *   character of the key, the value is escaped and/or themed:
 *    - !variable: inserted as is
 *    - @variable: escape plain text to HTML (APP.checkPlain)
 *    - %variable: escape text and theme as a placeholder for user-submitted
 *      content (checkPlain + APP.theme('placeholder'))
 *
 * @see APP.t()
 * @ingroup sanitization
 */
APP.formatString = function(str, args) {
    // Transform arguments before inserting them.
    for (var key in args) {
        switch (key.charAt(0)) {
            // Escaped only.
            case '@':
                args[key] = APP.checkPlain(args[key]);
                break;
            // Pass-through.
            case '!':
                break;
            // Escaped and placeholder.
            case '%':
            //same as default
            default:
                args[key] = APP.theme('placeholder', args[key]);
                break;
        }
        str = str.replace(key, args[key]);
    }
    return str;
};

/**
 * Translate strings to the page language or a given language.
 *
 * See the documentation of the server-side t() function for further details.
 *
 * @param str
 *   A string containing the English string to translate.
 * @param args
 *   An object of replacements pairs to make after translation. Incidences
 *   of any key in this array are replaced with the corresponding value.
 *   See APP.formatString().
 *
 * @param options
 *   - 'context' (defaults to the empty context): The context the source string
 *     belongs to.
 *
 * @return
 *   The translated string.
 */
APP.t = function (str, args, options) {
    options = options || {};
    options.context = options.context || '';

    // Fetch the localized version of the string.
    if (APP.locale.strings && APP.locale.strings[options.context] && APP.locale.strings[options.context][str]) {
        str = APP.locale.strings[options.context][str];
    }

    if (args) {
        str = APP.formatString(str, args);
    }
    return str;
};

/**
 * Format a string containing a count of items.
 *
 * This function ensures that the string is pluralized correctly. Since APP.t() is
 * called by this function, make sure not to pass already-localized strings to it.
 *
 * See the documentation of the server-side format_plural() function for further details.
 *
 * @param count
 *   The item count to display.
 * @param singular
 *   The string for the singular case. Please make sure it is clear this is
 *   singular, to ease translation (e.g. use "1 new comment" instead of "1 new").
 *   Do not use @count in the singular string.
 * @param plural
 *   The string for the plural case. Please make sure it is clear this is plural,
 *   to ease translation. Use @count in place of the item count, as in "@count
 *   new comments".
 * @param args
 *   An object of replacements pairs to make after translation. Incidences
 *   of any key in this array are replaced with the corresponding value.
 *   See APP.formatString().
 *   Note that you do not need to include @count in this array.
 *   This replacement is done automatically for the plural case.
 * @param options
 *   The options to pass to the APP.t() function.
 * @return
 *   A translated string.
 */
APP.formatPlural = function (count, singular, plural, args, options) {
    args = args || {};
    args['@count'] = count;
    // Determine the index of the plural form.
    var index = APP.locale.pluralFormula ? APP.locale.pluralFormula(args['@count']) : ((args['@count'] == 1) ? 0 : 1);

    if (index == 0) {
        return APP.t(singular, args, options);
    }
    else if (index == 1) {
        return APP.t(plural, args, options);
    }
    else {
        args['@count[' + index + ']'] = args['@count'];
        delete args['@count'];
        return APP.t(plural.replace('@count', '@count[' + index + ']'), args, options);
    }
};

APP.redirect = function(url){
    if(url.indexOf('/') === 0){
        window.location = APP.settings.site_url + PHP.trim(url,'/');
    } else {
        window.location = url;
    }
}

/**
 * Generate the themed representation of a Drupal object.
 *
 * All requests for themed output must go through this function. It examines
 * the request and routes it to the appropriate theme function. If the current
 * theme does not provide an override function, the generic theme function is
 * called.
 *
 * For example, to retrieve the HTML for text that should be emphasized and
 * displayed as a placeholder inside a sentence, call
 * APP.theme('placeholder', text).
 *
 * @param func
 *   The name of the theme function to call.
 * @param ...
 *   Additional arguments to pass along to the theme function.
 * @return
 *   Any data the theme function returns. This could be a plain HTML string,
 *   but also a complex object.
 */
APP.theme = function (func) {
    var args = Array.prototype.slice.apply(arguments, [1]);

    return (APP.theme[func] || APP.theme.prototype[func]).apply(this, args);
};

/**
 * The default themes.
 */
APP.theme.prototype = {

    /**
     * Formats text for emphasized display in a placeholder inside a sentence.
     *
     * @param str
     *   The text to format (plain-text).
     * @return
     *   The formatted text (html).
     */
    placeholder: function (str) {
        return '<em class="placeholder">' + APP.checkPlain(str) + '</em>';
    }
};

APP.ajax = {

    /**
     * Show a notification
     * @param Array
     * - array
     *   - mess (STRING) //message to show
     *   - type (STRING) //type of message
     */
    message: function(data){
        var i;
        for ( i in data ){
            if (data.hasOwnProperty(i)){
                noty(data[i]);
            }
        }
    },

    /**
     * Show a facebox
     *
     * @param Array<String>
     * - content (STRING) //the content of the facebox
     */
    facebox: function(data){
        $.facebox(data.content);
    },

    /**
     * Fill HTML content
     * @param Array
     * - array
     *   - where (STRING) //id or identifier of destination
     *   - content (STRING) //what to put
     */
    html: function(data){
        var i;
        for ( i in data ){
            if (data.hasOwnProperty(i)){
                $(data[i].where).html(data[i].content);
            }
        }
    },
    /**
     * Execute functions
     * @param Array<Function>
     *
     */
    functions: function(data){
        var i;
        for ( i in data ){
            if (data.hasOwnProperty(i)){
                eval(data[i]);
            }
        }
    }
}

APP.jx_manager = function (data){
    var what;
    for ( what in data )
    {
        if (data.hasOwnProperty(what)){
            if(typeof APP.ajax[what] != 'undefined'){
                APP.ajax[what].call(this, data[what]);
            }
        }
    }

    APP.attachBehaviors(document, APP.settings);
};


// 'js enabled' cookie.
document.cookie = 'has_js=1; path=/';
