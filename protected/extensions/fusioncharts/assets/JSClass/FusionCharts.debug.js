/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global window: false */

/*members FusionCharts, args, attributes, class, constructor, core,
    extend, getObjectReference, height, id, insertMode, items, lang, lastId,
    length, options, overlayButton, parsePolicies, policies, product,
    prototype, raiseError, raiseEvent, raiseWarning, resizeTo, safeMode,
    src, swfUrl, toString, uniqueId, version, width, ref, __state
*/

/*!
 * FusionCharts JavaScript Library
 * Copyright FusionCharts Technologies LLP
 * License Information at <http://www.fusioncharts.com/license>
 *
 * @author FusionCharts
 * @version 3.2.1-release
 *
 * Third-party attributions:
 * SWFObject v2.2 <http://code.google.com/p/swfobject/>
 * JSON v2 <http://www.JSON.org/js.html>
 * Firebug Lite 1.3.0 <http://getfirebug.com/firebuglite>
 * jQuery 1.4.2 <http://jquery.com/>
 */

/**
 * -----------------------------------------------------------------------------
 * FusionCharts Core Framework
 * FusionCharts core framework module. This module contains the basic routines
 * required by subsequent modules to extend/scale or add functionality to the
 * FusionCharts object.
 * -----------------------------------------------------------------------------
 */
(function () {

    // In case FusionCharts o
    // bject already exists, we skip this function.
    if (typeof window.FusionCharts !== 'undefined') {
        return;
    }

    /**
     * @var {object} global The global variable would store all private methods
     * and properties available to each module.
     *
     * @var {object} modules For maintaining module information.
     *
     * @var {Array} argsT Specifies the order in which the parameters of the new
     * FusionCharts objects are interpreted and converted to options object.
     */
    var global = {}, modules = {}, argsT = ['swfUrl', 'id', 'width', 'height',
        'debugMode', 'registerWithJS', 'bgColor', 'scaleMode', 'lang',
        'detectFlashVersion', 'autoInstallRedirect'];

    /**
     * This method, when added to the prototype of an object,
     * allows shallow or deep extension of the object with another
     * object.
     */
    global.extend = function (obj1, obj2) {

        // Get shallow or deep copy information
        var deep = typeof arguments[arguments.length - 1] === 'boolean' ?
            arguments[arguments.length - 1] : false;

        // Decide which parameter is going to be source and which one will be
        // sink.
        var src = obj2, snk = obj1;
        if (typeof obj2 === 'boolean' || arguments.length === 1) {
            snk = global.core;
            src = obj1;
        }
        // When 'shallow' is marked as true, the methods and properties
        // of source is not added to the prototype of the sink.
        if (deep === true) {
            snk = snk.prototype;
        }

        // Copy all methods and properties of the object passed in parameter
        // to the object to which this function is attached.
        for (var item in src) {
            snk[item] = src[item];
        }

        return snk;
    };

    // Function that auto-generates a unique id.
    global.uniqueId = function () {
        return 'chartobject-' + (global.uniqueId.lastId += 1);
    };
    global.uniqueId.lastId = 0;

    // Define the policy to create default parameters for the swfObject.
    // Values are in format [sourceOption, defaultValue]
    // This helps in building the initial FusionCharts object when new instances
    // are created from user parameters.
    global.policies = {
        /**
         * @var options {object} Contains all the customizable options that are
         * used by the library internally and has nothing to do with renderer
         * attributes, vars or parameters
         */
        options: {
            product: ['product', 'v3'],
            insertMode: ['insertMode', 'replace'],
            safeMode: ['safeMode', true],
            overlayButton: ['overlayButton', undefined]
        },
        /**
         * @var attributes {object} Contains configurations pertaining to the
         * host (browser) environment.
         */
        attributes: {
            lang: ['lang', 'EN'],
            'class': ['className', 'FusionCharts']
        },

        /**
         * @var {Array} width configuration for width of the chart.
         * @var {Array} height configuration for height of the chart.
         * @var {Array} src specifies chart swf url
         */
        width: ['width', '400'],
        height: ['height', '300'],
        src: ['swfUrl', ''],
        
        __state: {}
    };

    /**
     * Allows the core to process an arguments object based on a set of policies
     * and construct an object out of it that is mapped exactly as respective
     * parameter policy defines. In other words, it uses an object and
     * creates another object or updates another object with values from the
     * original arguments object in a particular hierarchy and name that a set
     * of rules (policies) define.
     */
    global.parsePolicies = function (obj, policies, options) {
        var prop, policy, value;

        // Iterate through the data policy and correspondingly create the
        // three stacks of parameters, attributes and flashVars
        for (policy in policies) {

            // Set just the policy object in case of single-level policy.
            if (global.policies[policy] instanceof Array) {
                value = options[policies[policy][0]];
                obj[policy] = value === undefined ? policies[policy][1] : value;
                continue;
            }

            // Define objects that would hold parameters for swfobject. Also
            // populate with variables from the parameters
            if (typeof obj[policy] !== 'object') {
                obj[policy] = {};
            }

            // Set every sub-object for two-level policy
            for (prop in policies[policy]) {
                value = options[policies[policy][prop][0]];
                obj[policy][prop] = value === undefined ? 
                    policies[policy][prop][1] : value;
            }
        }
    };
    
    global.core = function (command) {
        // This point onwards, we must check whether this is being used as a
        // constructor or not
        if (!(this instanceof global.core)) {
            // Allow private communication with modules. In case FusionCharts is
            // not called as constructor and it is passed an array that is marked
            // to do private communication, then share the global variable.
            if (arguments.length === 1 &&
                command instanceof Array && command[0] === 'private') {
                // Prevent overwriting and duplicate execution of modules.
                if (modules[command[1]] === true) {
                    return undefined;
                }
                modules[command[1]] = true;
                return global;
            }

            // Allow using FusionCharts object to directly access its new items
            if (arguments.length === 1 && typeof command === 'string') {
                return global.core.items[command];
            }
            // In case all above combination of arguments fail, we can unsafely
            // assume that user forgot the 'new' keyword.
            global.raiseError(this, '25081840', 'run', '', new SyntaxError(
                "Use the \"new\" keyword while creating a new FusionCharts object"));
        }

        // Define a variable for iterative key in various loops and the
        // object variable that stores the options.
        var options = {}, prop;

        // Check whether linear arguments are sent and convert it to object.
        if (arguments.length === 1 && typeof arguments[0] === 'object') {
            // If the above condition matches, then we can safely assume that
            // the first parameter is the options object.
            options = arguments[0];
        }
        else {
            // Iterate through the arguments template and add the keys to the
            // options object while fetching corresponding values from arguments
            // array.
            for (prop in argsT) {
                options[argsT[prop]] = arguments[prop];
            }
        }

        // Incorporate the trailing object parameter as object-style
        // parameter input overrides.
        if (typeof arguments[arguments.length - 1] === 'object') {
            delete options[arguments.length - 1];
            global.extend(options, arguments[arguments.length - 1]);
        }

        // Set autogenerated chart-id in case one is not specified
        this.id = typeof options.id === 'undefined' ?
            this.id = global.uniqueId() : options.id;

        // Set dimension passed by user and subsequently validate the options.
        // - Remove trailing 'px'
        this.args = options;

        // If an item is created with same id, the previous item is disposed.
        if (global.core.items[this.id] instanceof global.core) {
            this.id = global.uniqueId();
            global.raiseWarning(this, '06091847', 'param', '',
                'A FusionChart oject with the specified id \"' + this.id +
                '\" already exists. Renaming it to ' + this.id);
        }
        
        // Parse global policies.
        global.parsePolicies(this, global.policies, options);

        // Set initial dimension of charts
        this.resizeTo(options.width, options.height, true);
        
        // Execute all constructors one after the other using events.
        global.raiseEvent('BeforeInitialize', options, this);

        // Add this object to the repository of objects within core object.
        global.core.items[this.id] = this;

        // Raise initialization event.
        global.raiseEvent('Initialized', options, this);

        return this;
    };

    // Make the core extensible and reset the constructor of the object
    // for maintaining correct prototype chain.
    global.core.prototype = {};
    // Reset constructor.
    global.core.prototype.constructor = global.core;

    global.extend({
        id: 'FusionCharts',
        /**
         * The version of FusionCharts.js
         */
        version: [3, 2, 1, 'release', 1750],

        // Add a container of all chart objects. This will allow easy returning
        // of FusionCharts objects through getChartFromId.
        items: {},

        // Add an object to store options
        options: {},
        
        // Add function to access the object created by renderers.
        getObjectReference: function (id) {
            return global.core.items[id].ref;
        }
    }, false);

    // Expose the core to the global scope.
    window.FusionCharts = global.core;

}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global Array: false, FusionCharts, window: false */

/*members "*", BeforeDispose, BeforeInitialize, DataLoadCancelled, BeforeDataUpdate,
    DataLoadError, DataLoadRequestCancelled, DataLoadRequestCompleted, DataUpdateCancelled,
    DataLoadRequested, DataLoaded, DataXMLInvalid, Disposed, DrawComplete,
    FusionChartsEvents, Initialized, InvalidDataError, Loaded, DataUpdated
    NoDataToDisplay, Rendered, Resized, addEventListener, addListener, call,
    cancel, core, eventId, eventType, extend, items, lastEventId, length,
    listeners, push, raiseError, raiseEvent, removeEventListener,
    removeListener, sender, splice, stopPropagation, toLowerCase,
    triggerEvent
*/

/**
 * -----------------------------------------------------------------------------
 * Event Handler Framework
 * This module allows FusionCharts to work with W3C Level 2 style events for
 * allowing multiple handlers per event and also to do event driven development
 * on a global or per-chart basis.
 * -----------------------------------------------------------------------------
 */
(function () {

    // Try register the module with FusionCharts.
    var global = FusionCharts(['private', 'EventManager']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    // Collection of FusionCharts events
    window.FusionChartsEvents = {
        BeforeInitialize: 'beforeinitialize',
        Initialized: 'initialized',
        Loaded: 'loaded',
        Rendered: 'rendered',
        DataLoadRequested: 'dataloadrequested',
        DataLoadRequestCancelled: 'dataloadrequestcancelled',
        DataLoadRequestCompleted: 'dataloadrequestcompleted',
        BeforeDataUpdate: 'beforedataupdate',
        DataUpdateCancelled: 'dataupdatecancelled',
        DataUpdated: 'dataupdated',
        DataLoadCancelled: 'dataloadcancelled',
        DataLoaded: 'dataloaded',
        DataLoadError: 'dataloaderror',
        NoDataToDisplay: 'nodatatodisplay',
        DataXMLInvalid: 'dataxmlinvalid',
        InvalidDataError: 'invaliddataerror',
        DrawComplete: 'drawcomplete',
        Resized: 'resized',
        BeforeDispose: 'beforedispose',
        Disposed: 'disposed'
    };

    // A function to create an abstraction layer so that the try-catch /
    // error suppression of flash can be avoided while raising events.
    var managedFnCall = function (item, scope, event, args) {
        // We change the scope of the function with respect to the
        // object that raised the event.
        try {
            item[0].call(scope, event, args || {});
        }
        catch (e) {
            // Call error in a separate thread to avoid stopping
            // of chart load.
            setTimeout(function () { 
                throw e;
            }, 0);
        }
    };

    // Function that executes all functions that are to be invoked upon trigger
    // of an event.
    var slotLoader = function (slot, event, args) {
        // If slot does not have a queue, we assume that the listener
        // was never added and halt method.
        if (!(slot instanceof Array)) {
            // Statutory W3C NOT preventDefault flag
            return;
        }
        
        // Initialize variables.
        var i = 0, scope;

        // Iterate through the slot and look for match with respect to
        // type and binding.
        for (; i < slot.length; i += 1) {

            // If there is a match found w.r.t. type and bind, we fire it.
            if (slot[i][1] === event.sender || slot[i][1] === undefined) {

                // Determine the sender of the event for global events.
                // The choice of scope differes depending on whether a
                // global or a local event is being raised.
                scope = slot[i][1] === event.sender ?
                    event.sender : global.core;

                managedFnCall(slot[i], scope, event, args);
            }

            // Check whether propagation flag is set to false and discontnue
            // iteration if needed.
            if (event.cancel === true) {
                break;
            }
        }
    };

    var EventTarget = {

        // Entire collection of listeners.
        listeners: {},

        // The last raised event id. Allows to calculate the next event id.
        lastEventId: 0,

        addListener: function (type, listener, bind) {

            // In case type is sent as array, we recurse this function.
            if (type instanceof Array) {
                // We look into each item of the 'type' parameter and send it,
                // along with other parameters to a recursed addListener
                // method.
                for (var i = 0; i < type.length; i += 1) {
                    EventTarget.addListener(type[i], listener, bind);
                }
                return;
            }

            // Validate the type parameter. Listener cannot be added without
            // valid type.
            if (typeof type !== 'string') {
                global.raiseError(this, '03091549', 'param', '::EventTarget.addListener',
                    new Error('Unspecified Event Type'));
                return;
            }

            // Listener must be a function. It will not eval a string.
            if (typeof listener !== 'function') {
                global.raiseError(this, '03091550', 'param', '::EventTarget.addListener',
                    new Error('Invalid Event Listener'));
                return;
            }

            // Desensitize the type case for user accessability.
            type = type.toLowerCase();

            // If the insertion position does not have a queue, then create one.
            if (!(EventTarget.listeners[type] instanceof Array)) {
                EventTarget.listeners[type] = [];
            }

            // Add the listener to the queue.
            EventTarget.listeners[type].push([listener, bind]);

        },

        removeListener: function (type, listener, bind) {
		
			var i;
		
            // In case type is sent as array, we recurse this function.
            if (type instanceof Array) {
                // We look into each item of the 'type' parameter and send it,
                // along with other parameters to a recursed addListener
                // method.
                for (i = 0; i < type.length; i += 1) {
                    EventTarget.removeListener(type[i], listener, bind);
                }
                return;
            }

            // Validate the type parameter. Listener cannot be removed without
            // valid type.
            if (typeof type !== 'string') {
                global.raiseError(this, '03091559', 'param', '::EventTarget.removeListener',
                    new Error('Unspecified Event Type'));
                return;
            }

            // Listener must be a function. Else we have nothing to remove!
            if (typeof listener !== 'function') {
                global.raiseError(this, '03091560', 'param', '::EventTarget.removeListener',
                    new Error('Invalid Event Listener'));
                return;
            }

            // Desensitize the type case for user accessability.
            type = type.toLowerCase();

            // Create a reference to the slot for easy lookup in this method.
            var slot = EventTarget.listeners[type];

            // If slot does not have a queue, we assume that the listener
            // was never added and halt method.
            if (!(slot instanceof Array)) {
                return;
            }

            // Iterate through the slot and remove every instance of the
            // event handler.
            for (i = 0; i < slot.length; i += 1) {
                // Remove all instances of the listener found in the queue.
                if (slot[i][0] === listener && slot[i][1] === bind) {
                    slot.splice(i, 1);
                    i -= 1;
                }
            }
        },

        // opts can have { async:true, omni:true }
        triggerEvent: function (type, sender, args) {

            // In case, event type is missing, dispatch cannot proceed.
            if (typeof type !== 'string') {
                global.raiseError(this, '03091602', 'param', '::EventTarget.dispatchEvent',
                    new Error('Invalid Event Type'));
                return undefined;
            }

            // Desensitize the type case for user accessability.
            type = type.toLowerCase();
            
            // Model the event as per W3C standards. Add the function to cancel
            // event propagation by user handlers. Also append an incremental
            // event id.
            var event = {
                eventType: type,
                eventId: (EventTarget.lastEventId += 1),
                sender: (typeof sender === 'string' ?
                    global.core.items[sender] : sender),
                stopPropagation: function () {
                    return (this.cancel = true) === false;
                }
            };

            // Execute the functions present within the event slot (collection
            // of functions for a particular event).
            slotLoader(EventTarget.listeners[type], event, args);
            
            // Facilitate the call of a global event listener.
            slotLoader(EventTarget.listeners['*'], event, args);

            // Statutory W3C NOT preventDefault flag
            return true;
        }
    };

    // Facilitate for raising events internally.
    global.raiseEvent = function (type, args, obj) {
        return EventTarget.triggerEvent(type,
            (obj === undefined ? global.core : obj), args);
    };

    // Extend the eventlisteners to internal global.
    global.addEventListener = function (type, listener) {
        return EventTarget.addListener(type, listener);
    };
    global.removeEventListener = function (type, listener) {
        return EventTarget.removeListener(type, listener);
    };

    // Add eventListener extensibility to FusionCharts object
    global.extend({
        addEventListener: global.addEventListener,
        removeEventListener: global.removeEventListener
    }, false);

    // Add eventListener extensibility to FusionCharts prototype so that
    // individual FusionCharts objects can use per-chart events
    global.extend({
        addEventListener: function (type, listener) {
            return EventTarget.addListener(type, listener, this);
        },
        removeEventListener: function (type, listener) {
            return EventTarget.removeListener(type, listener, this);
        }
    }, true);

    // Cleanup on dispose
    global.addEventListener('BeforeDispose', function (e) {
        var type, i;
        // Iterate through all events in the collection of listeners
        for (type in EventTarget.listeners) {
            for (i = 0; i < EventTarget.listeners[type].length; i += 1) {
                // When a match is found, delete the listener from the
                // collection.
                if (EventTarget.listeners[type][i][1] === e.sender) {
                    EventTarget.listeners[type].splice(i, 1);
                }
            }
        }
    });

}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global Array: false, FusionCharts, window: false, console: false */

/*members _enableFirebugLite, appendChild, comp, console, core,
    createElement, currentOutputHelper, debugMode, enabled, event, eventId,
    eventType, extend, firebug, getElementsByTagName, id, impl, length,
    level, log, message, module, name, nature, onload, onreadystatechange,
    options, outputFailed, outputFormat, outputHandler, outputHelpers,
    outputTo, param, raiseError, raiseEvent, raiseWarning, range,
    readyState, run, scriptBaseUri, sender, setTimeout, source, src, text,
    toLowerCase, toString, type, undefined, verbose
*/

/**
 * -----------------------------------------------------------------------------
 * FusionCharts JavaScript Library
 * Error Handler Framework
 *
 * This module allows other FusionCharts JavaScript Library modules to raise
 * error and warning messages.
 * -----------------------------------------------------------------------------
 */
(function () {

    // Try register the module with FusionCharts.
    var global = FusionCharts(['private', 'ErrorHandler']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    // Set the default options for the default output helper.
    var DEFAULT_OUTPUT_HELPER = 'text';

    /**
     * @var {object} errorNature is an enumeration containing possible error
     * types. This is used so that shorthand reference to .raiseError and
     * .raiseWarning can be expanded for easier user reference.
     */
    var errorNatures = {
        type: 'TypeException',
        range: 'ValueRangeException',
        impl: 'NotImplementedException',
        param: 'ParameterException',
        run: 'RuntimeException',
        comp: 'DesignTimeError',
        'undefined': 'UnspecifiedException'
    };


    /**
     * This function raises the error event after appropriately formatting the
     * parameters.
     * @param {FusionCharts} sender
     * @param {string} id Is the error reference id.
     * @param {string} nature Is a cue as to what category of error is this.
     *         The value of this param must be same as one of the "keys" within
     *         the "errorNatures" collection.
     * @param {string} source Is a cue as to which object/module caused this
     *         error.
     * @param {Error} err
     * @param {string} level Indicates whether this error event is an error
     *         or warning event. Its values can be "Error" or "Warning".
     *
     * @type void
     */
    var raiseEWEvent = function (sender, id, nature, source, err, level) {

        // We create a human-readable message for this error.
        var message = '#' + id + ' ' + sender.id + source + ' ' + level + ' >> ';

        // If err is sent as error object, we input more details to the error
        // object
        if (err instanceof Error) {
            err.name = errorNatures[nature];
            err.module = 'FusionCharts' + source;
            err.level = level;

            // Update the error message.
            err.message = message + err.message;
            message = err.message;
            
            // Throw error in a separate scope so that the execution of this script
            // is not blocked. Do this only when debugMode is enabled
            window.setTimeout(function () {
                throw err;
            }, 0);

        }
        else {
            // Append the message string to the error message and sync with err.
            message = message + err;
        }

        // Prepare the event argument object.
        var args = {
            id: id,
            nature: errorNatures[nature],
            source: 'FusionCharts' + source,
            message: message
        };
        // Raise the appropriate event.
        global.raiseEvent(level, args, sender);

        // Raise legacy events
        if (typeof window['FC_' + level] === 'function') {
            window['FC_' + level](args);
        }

    };

    /**
     * This function raises an "Error" event based upon the parameters passed
     * to it.
     * @param {FusionCharts} sender
     * @param {string} id Is the error reference id.
     * @param {string} nature Is a cue as to what category of error is this.
     *         The value of this param must be same as one of the "keys" within
     *         the "errorNatures" collection.
     * @param {string} source Is a cue as to which object/module caused this
     *         error.
     * @param {string} message
     *
     * @type void
     */
    global.raiseError = function (sender, id, nature, source, message) {
        raiseEWEvent(sender, id, nature, source, message, 'Error');
    };

    /**
     * This function raises an "Warning" event based upon the parameters passed
     * to it.
     * @param {FusionCharts} sender
     * @param {string} id Is the error reference id.
     * @param {string} nature Is a cue as to what category of error is this.
     *         The value of this param must be same as one of the "keys" within
     *         the "errorNatures" collection.
     * @param {string} source Is a cue as to which object/module caused this
     *         error.
     * @param {string} message
     *
     * @type void
     */
    global.raiseWarning = function (sender, id, nature, source, message) {
        raiseEWEvent(sender, id, nature, source, message, 'Warning');
    };

    /**
     * @var {object} logger Conatins all routines pertaining to logging a debug
     * outout.
     */
    var logger = {
        /**
         * @var {object} outputHelpers Is the collection of functions that calls
         * the outputTo function with arguments formatted in a specific manner.
         */
        outputHelpers: {
            // Simple text output function.
            'text': function (e, a) {
                var sender = (e.sender.id || e.sender).toString();
                logger.outputTo('#' + e.eventId + ' [' + sender + '] fired "' +
                    e.eventType + '" event. ' + (e.eventType === 'error' ||
                    e.eventType === 'warning' ? a.message : ''));
            },
            // Function that calls the debugger method in typical FusionCharts
            // events argument format.
            'event': function (e, a) {
                this.outputTo(e, a);
            },
            // This function formats outputs with all details, and still,
            // maintaining human readable format. It is best used in conjunction
            // with an advanced JS console.
            'verbose': function (e, a) {
                logger.outputTo(e.eventId, e.sender.id, e.eventType, a);
            }
        },

        /**
         * @var {object} outputHandler Is the eventHandler that indirectly calls
         * the output function via output helpers whenever any event is raised.
         */
        outputHandler: function (e, a) {
            // Verify whether the output function exists or not.
            if (typeof logger.outputTo !== 'function') {
                global.core.debugMode.outputFailed = true;
                return;
            }
            // Clear flag of data load fail upon reaching this line.
            global.core.debugMode.outputFailed = false;
            // Call the current outputHelper in order to invoke the
            // required function.
            logger.currentOutputHelper(e, a);
        },

        /**
         * @var {function} currentOutputHelper Is the function that formats the
         * debug output if event format to different formats as arguments.
         * @var {function} outputTo Is the reference to the function that is
         * called when a debug event is raised.
         * @var {boolean} keeps a track whether the logger is enabled or not.
         */
        currentOutputHelper: undefined,

        outputTo: undefined,

        enabled: false
    };

    // Set the initial default output helper to the one specified as default.
    logger.currentOutputHelper = logger.outputHelpers[DEFAULT_OUTPUT_HELPER];

    // Add debugMode API to FusionCharts core object, so that it can be accessed
    // by users globally.
    global.extend({
        debugMode: {
            /**
             * Specifies how to format the output to the function that will
             * accept output from the debugger.
             * @id FusionCharts.debugMode.outputFormat
             *
             * @param {string} format Can be one of the accepted format names
             * such as "text", "verbose", "event".
             *
             * @type boolean
             */
            outputFormat: function (format) { 
                // Validate the parameter.
                if (format && typeof format.toLowerCase === 'function' &&
                    typeof logger.outputHelpers[format = format.toLowerCase()] === 'function') {
                    // set the current output helper function to the one specified
                    // in parameter
                    logger.currentOutputHelper = logger.outputHelpers[format];
                    // Return "true" to users, indicating, output format
                    // successfully updated.
                    return true;
                }
                // In case validation fails, notify user that it failed by
                // returning false;
                return false;
            },

            /**
             * Allows you to specify the function to which the debugger output
             * will be redirected.
             * @id FusionCharts.debugMode.outputTo
             *
             * @param {function} fn is the function to which the debugMode output
             * will be passed on.
             *
             * @type void
             */
            outputTo: function (fn) {
                // Check whether the logger is a function or not. If it is a
                // function, we set a reference to it to be used later as the
                // logger function.
                if (typeof fn === 'function') {
                    logger.outputTo = fn;
                }
                
                // In case user sends 'null' as the value of the logger function,
                // we can assume that user wants not to log any output.
                else if (fn === null) {
                    global.core.debugMode.enabled(false);
                    delete logger.outputTo;
                }
            },

            /**
             * Enables, disables and configures the debugMode.
             * @FusionCHarts.edbugMode.enabled
             *
             * @param {boolean} state specifies whether to enable logging of
             * debug information.
             * @param {function} outputTo is the function to which the debugMode
             * output will be passed on.
             * @param {string} format Can be one of the accepted format names
             * such as "text", "verbose", "event".
             *
             * @return The current 'enable' state of the debugMode.
             * @type boolean
             */
            enabled: function (state, outputTo, format) {

                // In case user send in only one parameter and that too a
                // function, we can assume that he wants to use it as a logger
                // function and also enable logging.
                if (typeof state === 'function') {
                    if (typeof outputTo === 'string' && arguments.length === 2) {
                        format = outputTo;
                    }
                    outputTo = state;
                    state = true;
                }
                
                // In case user sends in a valid parameter to change the current
                // state of the debugger, we update the debugger state.
                if (typeof state === 'boolean' && state !== logger.enabled) {
                    global.core[(logger.enabled = state) ? 'addEventListener'
                        : 'removeEventListener']('*', logger.outputHandler);
                }

                // If user sends in a parameter for the logger parameter, we
                // set it to the logger function reference.
                if (typeof outputTo === 'function') {
                    logger.outputTo = outputTo;
                }

                // Set output format if needed.
                global.core.debugMode.outputFormat(format);

                // Finally send the current debugger state to the user.
                return logger.enabled;
            },

            /**
             * This method fetches FirebugLite component's code and adds it to
             * current page. Subsequently, on load of the script it enables
             * advanced console logging to it.
             * @id FusionCharts.debugMode._enableFirebugLite
             *
             * @param {string} url is the path to the location where the firebug
             * javascript exists.
             *
             * @type void
             * @private true
             */
            _enableFirebugLite: function (url) {
                // Check whether firebug already exists.
                if (window.console && window.console.firebug) {
                    // If firebug already exists, we do not need to include any
                    // script for firebu-lite and we simply enable logging to
                    //console.
                    global.core.debugMode.enabled(console.log, 'verbose');
                    return;
                }

                // Check for "XSS attack characters"

                // Install firebug-lite within page by creating new 'script'
                // element and appending to page head.

                // Create the element with its attributes.
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = typeof url === 'string' ? url :
                    global.core.options.scriptBaseUri + 'firebug-lite.js';

                // Set default configuration of firebug-lite
                script['\v' === 'v' ? 'text' : 'innerHTML'] =
                    '{ startOpened: true }';


                // Attach events to check when the script has loaded or not.
                // on load of the script, enable logging to console.
                script.onload = function () {
                    global.core.debugMode.enabled(console.log, 'verbose');
                };
                script.onreadystatechange = function () {
                    if (this.readyState === 'complete' || this.readyState ===
                        'loaded') {
                        global.core.debugMode.enabled(console.log, 'verbose');
                    }
                };
                
                // Append the script to the head of this page.
                document.getElementsByTagName('head')[0].appendChild(script);
            }
        }
    }, false);
    
}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global Array: false, FusionCharts, RegExp: false, window: false
    FusionChartsDataFormats: true */

/*members FusionCharts, XMLURL, addEventListener, appendChild, apply,
    args, call, config, configure, containerElement, containerElementId,
    core, createElement, currentRendererName, dataFormat, extend,
    firstChild, getAttribute, getElementById, getElementsByTagName,
    getExternalInterfaceMethods, getRenderer, getRendererPolicy,
    hasChildNodes, id, init, initialized, insertMode, items, length,
    options, parsePolicies, policies, prototype, raiseError, raiseEvent,
    ref, register, removeChild, render, renderer, resize, sender,
    setAttribute, setCurrentRenderer, setDefault, slice, split, success,
    toLowerCase, toString, undefined, update, __state, rendering
*/

/**
 * -----------------------------------------------------------------------------
 * Renderer Abstraction Framework
 * This module allows developers to abstract the entire rendering engine. This
 * helps in multiple implementations of FusionCharts in various technologies
 * such as flash, HTML5, etc.
 * -----------------------------------------------------------------------------
 */
(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'RendererManager']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    // Allow FusionCharts to accept parameter to specify where to render the
    // chart.
    global.policies.options.containerElementId = ['renderAt', undefined];
    global.policies.options.renderer = ['renderer', undefined];

    // Collection of renderers.
    var notDefined = function () {
        global.raiseError(this, '25081845', 'run', '::RendererManager',
            new Error('No active renderer'));
        return;
    };
    var renderers = {
        'undefined': {
            render: notDefined, 
            update: notDefined,
            resize: notDefined,
            config: notDefined,
            policies: {}
        }
    }, store = {}; // store which chart has what renderer

    // API to add renderer and also to set/get the current renderer.
    global.renderer = {
        register: function (name, obj) {
            // Validate parameters
            if (!name || typeof name.toString !== 'function') {
                throw "#03091436 ~renderer.register() Invalid value for renderer name.";
            }

            // Desensitize character case for renderer name
            name = name.toString().toLowerCase();

            // Prevent addition of duplicate renderer
            if (renderers[name] !== undefined) {
                global.raiseError(global.core, '03091438', 'param', '::RendererManager>register',
                    'Duplicate renderer name specified in "name"');
                return false;
            }

            // Add renderer to the collection of renderers.
            renderers[name] = obj;
            // Return true when a new renderer is successfully added.
            return true;
        },

        // Set the current renderer
        setDefault: function (name) {
            // Validate parameters
            if (!name || typeof name.toString !== 'function') {
                global.raiseError(global.core, '25081731', 'param', '::RendererManager>setDefault',
                    'Invalid renderer name specified in "name"');
                return false;
            }
            
            // Validate the renderer name and see whether the parameter refers to a
            // valid renderer.
            // ALSO: Desensitize character case for renderer name.
            if (renderers[name = name.toString().toLowerCase()] === undefined) {
                global.raiseError(global.core, '25081733', 'range', '::RendererManager>setDefault',
                    'The specified renderer does not exist.');
                return false;
            }

            // Set reference to the current renderer.
            global.policies.options.renderer = ['renderer', name];
            return true;
        },

        getRenderer: function (name) {
            return renderers[name];
        },

        getRendererPolicy: function (name) {
            var policies = renderers[name].policies;
            return typeof policies === 'object' ? policies : {};
        },

        currentRendererName: function () {
            return global.policies.options.renderer[1];
        },

        update: function (obj) {
            store[obj.id].update.apply(obj,
                Array.prototype.slice.call(arguments, 1));
        },

        render: function (obj) {
            store[obj.id].render.apply(obj,
                Array.prototype.slice.call(arguments, 1));
        },

        resize: function (obj) {
            store[obj.id].resize.apply(obj,
                Array.prototype.slice.call(arguments, 1));
        },

        config: function (obj) {
            store[obj.id].config.apply(obj,
                Array.prototype.slice.call(arguments, 1));
        }
    };


    // This function allows users to make a generic call to external interface
    // of the chart via the FusionCharts object
    var eiCall = function (method) {
        return function () {
            // Verify whether the chart is valid object and then proceed.
            if (this.ref === undefined  || this.ref === null  ||
                typeof this.ref[method] !== 'function') {
                // Raise error event to notify that a method on the renderer was
                // invoked while the renderer does not have such a method.
                global.raiseError(this, '25081617', 'run', '~' + method + '()',
                    'ExternalInterface call failed. Check whether chart has been rendered.');
                return undefined;
            }
            
            return this.ref[method].apply(this.ref, arguments);
        };
    };

    // Constructor to add renderer functions
    global.addEventListener('BeforeInitialize', function (event) {
        // Reference to event sender.
        var obj = event.sender;
        
        // Check if construction has the default renderer name saved.
        if (typeof obj.options.renderer === 'string' &&
            renderers[obj.options.renderer.toLowerCase()] === undefined) {
            obj.options.renderer = global.policies.options.renderer[1];
        }
        // Desensitize the case of the parameter.
        obj.options.renderer = obj.options.renderer.toLowerCase();

        // Keep a reference
        store[obj.id] = renderers[obj.options.renderer];

        // Check whether this particular renderer has been rendered.
        if (store[obj.id].initialized !== true &&
            typeof store[obj.id].init === 'function') {
            // Call the 'init' function on the renderer and set a flag.
            store[obj.id].init();
            store[obj.id].initialized = true;
        }

        // Parse construction policies specific to this renderer.
        global.parsePolicies(obj, store[obj.id].policies || {}, obj.args);

        // Copy the prototype of the renderer specified in constructor to the
        // main object.
        for (var prop in store[obj.id].prototype) {
            obj[prop] = store[obj.id].prototype[prop];
        }

    });

    global.addEventListener('Loaded', function (e) {

        // Store a reference to the chart swf HTML Node.
        var obj = e.sender, chartObj = e.sender.ref;

        // Clear the flag that keeps a track whether the chart is presently
        // in a 'rendering' state
        if (obj instanceof global.core) {
            delete obj.__state.rendering;
        }

        // Validate whether the chart swf node exists and that it has
        // the prerequisite externalInterface functions.
        if (chartObj === undefined || chartObj === null || typeof
            chartObj.getExternalInterfaceMethods !== 'function') {
            return;
        }

        // The externalInterfaceMethods names are parsed from CSV to
        // Array.
        var eiItems = chartObj.getExternalInterfaceMethods().split(','),
            i;

        // We iterate through all the externalInterface method names and
        // create an extensible API object that is added to main
        // FusionCharts object as reference.
        for (i = 0; i < eiItems.length; i += 1) {
            // Copy method from renderer only when a local method does not exist
            if (obj[eiItems[i]] === undefined) {
                obj[eiItems[i]] = eiCall(eiItems[i]);
            }
        }
    });


// ============================================================================

    // Function that checks duplicate.
    var isDuplicateId = function (lookupId, container) {
        // Get the lookup element from the ID sent via parameter.
        var lookupElement = document.getElementById(lookupId),
            // Get the ID of the container element.
            containerId = container.getAttribute('id');

        // Check whether the element exists or not. If it does not exist, it
        // implies that there cannot be any duplicate.
        if (lookupElement === null) {
            return false;
        }

        // In case chart's Id and container's Id is same then is duplicate.
        if (lookupId === containerId) {
            return true;
        }

        // Check whether the lookup element returned before is actually
        // inside container or not.
        var children = container.getElementsByTagName('*');
        for (var i = 0; i < children.length; i += 1) {
            if (children[i] === lookupElement) {
                return false;
            }
        }
        // If the lookupElement is outside/before the container, it implies
        // that it is a duplicate.
        return true;
    };

    // Define a function that saves the reference to the embedded object
    // after it has been rendered.
    var updatePostRender = function (status) {
        // Check whether the render was successful.
        if (status.success === false) {
            global.raiseError(global.core.items[status.id], '25081850', 'run', '::RendererManager',
                new Error('There was an error rendering the chart. ' +
                    'Enable FusionCharts JS debugMode for more information.'));
        }
        
        // Lookup the FusionCharts object within its "items" repository.
        global.core.items[status.id].ref = status.ref;
        
        // If the ref has been created, create a reverse reference.
        if (status.ref) {
            status.ref.FusionCharts = global.core.items[status.id];
        }

        // Raise event that this chart has a DOM element
        global.raiseEvent('internal.DOMElementCreated', status,
            global.core.items[status.id]);

    };

    global.extend({
        render: function (containerElement) {

            // Check IE-Safe variable name collision within Global Scope
            if (window[this.id] !== undefined) {
                global.raiseError(this, '25081843', 'comp', '.render',
                    new Error('#25081843:IECompatibility() Chart ' +
                    'Id is same as a JavaScript variable name. Variable naming ' +
                    'error. Please use unique name for chart JS variable, ' +
                    'chart-id and container id.'));
            }

            // Create a blank element inside to mimic alternativecontent
            var alt = document.createElement('span');

            // Procure containerElement from internal object options that has
            // been passed via parameters.
            if (containerElement === undefined) {
                containerElement = this.options.containerElementId;
            }

            // In case user sends the element id, we get the object from it
            if (typeof containerElement === 'string') {
                containerElement = document.getElementById(containerElement);
            }
            if (containerElement === undefined || containerElement === null) {
                global.raiseError(this, '03091456', 'run', '.render()',
                    new Error("Unable to find the container DOM element."));
                return this;
            }

            // Check duplicate rendering with same id
            if (isDuplicateId(this.id, containerElement)) {
                global.raiseError(this, '05102109', 'run', '.render()',
                    new Error("A duplicate object already exists with the specific Id: " + this.id));
                return this;
            }

            // Set the attribute of this element that will be replaced by
            // swfobject
            alt.setAttribute('id', this.id);

            // Clear the contents of the containerElement and subsequently
            // append the new alt content.
            if (this.options.insertMode === 'replace') {
                while (containerElement.hasChildNodes()) {
                    containerElement.removeChild(containerElement.firstChild);
                }
            }
            containerElement.appendChild(alt);

            // Update the present container details in object.
            this.options.containerElement = containerElement;
            this.options.containerElementId = containerElement.id;

            // Set state that the chart is rendering
            this.__state.rendering = true;

            // Call the current renderer.
            global.renderer.render(this, containerElement, updatePostRender);

            // Return the fusioncharts object for chainability
            return this;
        },
        
        configure: function (key, value) {
            global.renderer.config(this, (typeof key === 'object') ? key : (function () {
                var items = {};
                items[key] = value;
                return items;
            }()));
        }
    }, true);

    global.extend({
        setCurrentRenderer: global.renderer.setDefault,

        /**
         * Render FusionCharts directly using the simplest one-line argument
         * parameter.
         * This function directly renders FusionCharts into the container
         * specified in arguments.
         */
        render: function () {

            // The order in which to parse the linear parameters.
            var argsT = ['swfUrl', 'id', 'width', 'height', 'renderAt',
                'dataSource', 'dataFormat'], params = {}, i;

            // If a FusionCharts object is sent to it, it calls render method of
            // it.
            if (arguments[0] instanceof global.core) {
                arguments[0].render();
                return arguments[0];
            }

            // Iterate through the linear parameters using the argument template
            // array defined above and create a parameter object out of it.
            for (i = 0; (i < arguments.length && i < argsT.length); i += 1) {
                params[argsT[i]] = arguments[i];
            }

            // Incorporate the trailing object parameter as object-style
            // parameter input overrides.
            if (typeof arguments[arguments.length - 1] === 'object') {
                delete params[argsT[i - 1]];
                global.extend(params, arguments[arguments.length - 1]);
            }

            // Pre-specify the 'xmlurl' format
            if (params.dataFormat === undefined) {
                params.dataFormat = FusionChartsDataFormats.XMLURL;
            }

            // Render a new FusionCharts out of the parameters and return the
            // object.
            return new global.core(params).render();
            
        }
    }, false);
}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global window: false, Array, FusionCharts, Error: false */

/*members ActiveXObject, FC_DataLoadError, FusionChartsDataFormats,
    XMLHttpRequest, __state, abort, addDataHandler, addEventListener, ajax,
    apply, call, cancelDataLoad, cancelDataLoadRequest, cancelDataUpdate,
    core, data, dataError, dataFormat, dataSource, decode, encode, error,
    extend, format, get, getChartData, httpStatus, id, isActive, location,
    onreadystatechange, open, options, overrideMimeType, policies, protocol,
    prototype, push, raiseError, raiseEvent, raiseWarning, readyState,
    renderer, rendering, responseText, send, sender, setChartData,
    setChartDataUrl, setRequestHeader, slice, source, status, test,
    toLowerCase, toString, transcodeData, update, updatePending, url, x,
    xmlHttpRequestObject
*/

/**
 * -----------------------------------------------------------------------------
 * Data-Handler Abstraction Framework
 * This allows developers to dynamically integrate a data transcoder so that
 * FusionCharts core can seamlessly work with multiple formats for data
 * provisioning.
 * -----------------------------------------------------------------------------
 */

(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'DataHandlerManager']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    // Collection of Data Formats
    window.FusionChartsDataFormats = {};

    /**
     * Global AJAX framework to manage AJAX data requests for data transactions.
     * @id global.ajax
     * @type object
     */
    global.ajax = (function () {

        /**
         * Function that internally manages AJAX error events. When called, it
         * passes on error details to the user-assigned ajax.error function.
         *
         * @param x {XMLHttpRequest}
         * @param e {Error}
         * @param args {arguments}
         *
         * @type void
         */
        var error = function (ajaxApi, x, e, args) {
            if (typeof ajaxApi.error === 'function') {
                // Replace the 'callBack' function reference with xmlHttpRequest
                // object as the error event need not know about the callback.
                args[1] = x;
                // As the arguments (args) may not be an array, we pass it to
                // array object prototype before pushing in the arguments.
                Array.prototype.push.call(args, e);
                // Call the public error method with arguments in the same order
                // as what was passed to the 'get' method.
                ajaxApi.error.apply(ajaxApi, args);
            }
        };

        var api = {
            x: window.XMLHttpRequest && (window.location.protocol !== "file:" || !window.ActiveXObject) ? function () {
                return new window.XMLHttpRequest();
            } : function () {
                try {
                    return new window.ActiveXObject("Microsoft.XMLHTTP");
                }
                catch (e) {
                    throw "Charts cannot render due to lack of AJAX support. Use setDataURL for fallback";
                }
            },

            get: function (u, f) {

                var x = api.x(), args = arguments;
                x.onreadystatechange = function () {
                    try {
                        if (x.readyState === 4) {
                            if (x.status === 200 || x.status === 0) {
                                f(x.responseText, x);
                            }
                            else {
                                error(api, x, new Error('XMLHttpRequest Error'), args);
                            }
                        }
                    }
                    catch (e) {
                        error(api, x, e, args);
                    }
                };

                try {
                    if (x.overrideMimeType) {
                        x.overrideMimeType('text/plain');
                    }
                    x.open('GET', u, true);
                    x.setRequestHeader('If-Modified-Since', 'Sat, 29 Oct 1994 19:43:31 GMT');
                    x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    x.setRequestHeader('Accept', 'text/plain, */*');
                    x.send(null);
                }
                catch (e) {
                    error(api, x, e, args);
                }

                // Return the xmlhttp object
                return x;
            }
        };

        return api;

    }());

    // Function to manage ajax error events
    global.ajax.error = function (url, x, method, obj, e) {

        // Compile argument for event.
        var eventArgs = {
            source: method,
            url: url,
            xmlHttpRequestObject: x,
            error: e,
            httpStatus: (x && x.status) ? x.status : -1
        };
	
        // Raise data load error message.
        global.raiseEvent('DataLoadError', eventArgs, obj);

        // Call legacy event handler.
        if (typeof window.FC_DataLoadError === 'function') {
            window.FC_DataLoadError(obj.id, eventArgs);
        }
    };

    // Store all data handlers here in a collection and also store all 'data' of
    // every chart in an object.
    /**
     * @var {object} cache stores the parsed JSON data as a cache location, so
     * that multiple calls to decode JSON does not involve redundant conversion.
     */
    var handlers = {}, dataStore = {}, xStore = {}, cache = {}, isUrl =  /url$/i;


    // Function to abort ajax request from httpRequest cache
    var abortAjaxOf = function (id) {
        // Clear any previous xmlHttpRequest.
        if (xStore[id] && typeof xStore[id].abort === 'function' &&
            xStore[id].readyState && xStore[id].readyState !== 0) {
            xStore[id].abort();
            return true;
        }
        return false;
    };
    
    // Allow data-related parameters to be passed in constructor
    global.policies.options.dataSource = ['dataSource', undefined];
    global.policies.options.dataFormat = ['dataFormat', undefined];

    // Expose Data handler and related management API.
    global.addDataHandler = function (name, obj) {
        if (typeof name !== 'string' || handlers[name.toLowerCase()] !== undefined) {
            global.raiseError(global.core, '03091606', 'param',
                '::DataManager.addDataHandler', new Error('Invalid Data Handler Name'));
            return;
        }
        var api = {}, lcaseName = name.toLowerCase();

        // Add handler to collection
        handlers[lcaseName] = obj;

        // Create Handler Direct Access API. This adds common fuctions for the
        // handler.
        api['set' + name + 'Url'] = function (url) {
            return this.setChartDataUrl(url, name);
        };

        api['set' + name + 'Data'] = function (data) {
            return this.setChartData(data, name);
        };

        api['get' + name + 'Data'] = function () {
            return this.getChartData(name);
        };

        // Add data formats to global formats collection
        window.FusionChartsDataFormats[name] = lcaseName;
        window.FusionChartsDataFormats[name + 'URL'] = lcaseName + 'URL';

        // Extend FusionCharts objects
        global.extend(api, true);
    };

    // Add constructor to initialize datastore or to clear any garbage.
    global.addEventListener('BeforeInitialize', function (event) {

        // Get short reference to the event sender.
        var obj = event.sender;

        // Clear the dataStore, cache and xStore when new chart is created.
        dataStore[obj.id] = '';
        cache[obj.id] = {};


        // Set intitial data if present
        if (obj.options.dataSource !== undefined &&
            typeof obj.options.dataFormat === 'string') {
            obj.setChartData(obj.options.dataSource, obj.options.dataFormat);
        }
    });

    // Add method to make sure to delete all fusioncharts objects when
    // dispose method is invoked
    global.addEventListener('BeforeDispose', function (e) {
        delete dataStore[e.sender.id];
        delete cache[e.sender.id];
        abortAjaxOf(e.sender.id);
    });

    // Add getter and setter to FusionCharts Objects
    global.extend({
        setChartDataUrl: function (url, format, silent) {
            if (format === undefined || format === null || typeof format.toString !== 'function') {
                global.raiseError(global.core, '03091609', 'param',
                    '.setChartDataUrl', new Error('Invalid Data Format'));
                return;
            }

            // Desensitize case of parameter.
            format = format.toString().toLowerCase();

            /**
             * @var {Boolean} cancelDLRFlag is to allow users to flag that 
             * data-load request has to be cancelled.
             */
            var baseFormat, obj = this, cancelDLRFlag = false;

            // Check whether the data-format has "url" at the end of it.
            // We compute the base format and update the format accordingly so
            // that format always ends with 'url' and the baseFormat contains real
            // format name.
            if (isUrl.test(format)) {
                baseFormat = format.slice(0, -3);
            }
            else {
                baseFormat = format;
                format = format + 'url';
            }
            
            // Raise event to notify xmlhttprequest transaction start.
            global.raiseEvent('DataLoadRequested', {
                source: 'XmlHttpRequest',
                url: url,
                dataFormat: baseFormat,
                cancelDataLoadRequest: function () {
                    cancelDLRFlag = true;
                    this.cancelDataLoadRequest = function () {
                        return false;
                    };
                    return true;
                }
            }, obj);

            // Check if user cancelled dataload request.
            if (cancelDLRFlag === true) {
                global.raiseEvent('DataLoadRequestCancelled', {
                    source: 'XmlHttpRequest',
                    url: url,
                    dataFormat: baseFormat
                }, obj);

                // Exit from this function
                return;
            }

            // Update reference to data-source.
            this.options.dataSource = url;

            // Initiate XmlHttpRequest.
            xStore[this.id] = global.ajax.get(decodeURIComponent(url), function (responseText, x) {
                // Allow cancellation of data loaing
                var cancelDLFlag = false;

                // Raise pre data-load event
                global.raiseEvent('DataLoadRequestCompleted', {
                    source: 'XmlHttpRequest',
                    url: url,
                    data: responseText,
                    dataFormat: baseFormat,
                    cancelDataLoad: function () {
                        cancelDLFlag = true;
                        this.cancelDataLoad = function () {
                            return false;
                        };
                        return true;
                    },
                    xmlHttpRequestObject: x
                }, obj);

                // Update the chart's dataStore with data received
                // from url.
                if (cancelDLFlag !== true) {
                    // Set the chart's data received from url.
                    obj.setChartData(responseText, baseFormat, silent);
                }
                else {
                    // Raise post data load event .
                    global.raiseEvent('DataLoadCancelled', {
                        source: 'XmlHttpRequest',
                        url: url,
                        dataFormat: baseFormat,
                        xmlHttpRequestObject: x
                    }, obj);
                }

                // Delete the xmlhttp object.
                delete xStore[this.id];

            }, 'XmlHttpRequest', this);

        },

        setChartData: function (data, format, silent) {
            // In case format is not a string, we raise an error
            if (format === undefined || format === null || typeof format.toString !== 'function') {
                global.raiseError(global.core, '03091610', 'param',
                    '.setChartData', new Error('Invalid Data Format'));
            }
            // Desensitize case of parameter.
            format = format.toString().toLowerCase();

            // Thebase format is set by truncating fetching method from the
            // formatg-string and then stored in 'baseFormat' variable.
            var baseFormat;

            // Check whether the data-format has "url" at the end of it. If true,
            // then we call the setChartDataUrl method and that in turn calls this
            // method as callback (marked as _recursed.)
            if (isUrl.test(format)) {
                this.setChartDataUrl(data, format, silent);
                return;
            }
            // When there is no trailing "url" in data-format, we assume that
            // the format provided is baseFormat itself.
            else {
                // Update the dataSource here only when we know that this call
                // was not recursed for dataurl purpose
                this.options.dataSource = data;
                baseFormat = format;
            }
            // Update chart's internal data-format options
            this.options.dataFormat = format;

            // Fetch the data-handler function from the dataHandler collection.
            var handler = handlers[baseFormat], parseResult, eventArgs,
                cancelDUFlag = false;

            // If the handler has been successfully fetched, execute it.
            if (typeof handler === 'undefined') {
                global.raiseError(global.core, '03091611', 'param',
                    '.setChartData', new Error('Data Format not recognized'));
                return;
            }

            // Execute the parser and fetch the parsing result.
            parseResult = handler.encode(data, this);
            // Updated the result so that it can be passed as event argument.
            parseResult.format = baseFormat;

            // Create argument for events
            eventArgs = {
                dataFormat: baseFormat,
                dataSource: data,
                dataError: parseResult.error,
                data: parseResult.data,
                cancelDataUpdate: function () {
                    cancelDUFlag = true;
                    this.cancelDataUpdate = function () {
                        return false;
                    };
                    return true;
                }
            };

            global.raiseEvent('BeforeDataUpdate', eventArgs, this);
            delete eventArgs.cancelDataUpdate; // Remove cancellation fn.

            // Update event only if it is not marked to be cancelled by user.
            if (cancelDUFlag === true) {
                global.raiseEvent('DataUpdateCancelled', eventArgs, this);
                return;
            }

            // Save data within dataStore
            dataStore[this.id] = (parseResult.data = eventArgs.data) || '';
            // Clear cache
            cache[this.id] = {};
            
            // Raise data updation event if it is not marked as silent data
            // updating.
            if (silent !== true) {
                // stall this update if this is stuck in between a render and loading
                if (this.options.safeMode === true &&
                    this.__state.rendering === true && !this.isActive()) {
                    this.__state.updatePending = parseResult;
                    global.raiseWarning(this, '23091255', 'run', '::DataHandler~update',
                    'Renderer update was postponed due to async loading.');
                }
                else {
                    delete this.__state.updatePending;
                    global.renderer.update(this, parseResult);
                }
            }
            
            // DevNote: This event must not be raised asynchronously in order
            // to maintain integrity and timing of related codes.
            global.raiseEvent('DataUpdated', eventArgs, this);
        },

        getChartData: function (format, advanced) {

            // Variable to store a reference to the data-handler.
            var handler, parseResult;
            // Desensitize case of parameter and fetch the data-handler
            // function from the dataHandler collection within the validation
            // check itself.

            if (format === undefined || typeof format.toString !== 'function' ||
                (handler = handlers[format = format.toString().toLowerCase()]) === undefined) {
                global.raiseError(this, '25081543', 'param', '~getChartData()',
                    new Error('Unrecognized data-format specified in "format"'));
                return undefined;
            }

            // Check presence of cached data or call the decoder routine of
            // the data-handler and return the decoded data.
            parseResult = (typeof cache[this.id][format] === 'object') ?
                cache[this.id][format] :
                cache[this.id][format] = handler.decode(dataStore[this.id], this);

            // Return the eminent data (in advanced mode if needed.
            return Boolean(advanced) === true ? parseResult : parseResult.data;
        }
    }, true);

    global.extend({
        transcodeData: function (data, from, to, advanced) {
            
            // Validate parameters.
            if (!from || typeof from.toString !== 'function' || !to ||
                typeof to.toString !== 'function' || 
                handlers[(to = to.toString().toLowerCase())] === undefined ||
                handlers[(from = from.toString().toLowerCase())] === undefined) {
                global.raiseError(this, '14090217', 'param', 'transcodeData()',
                    new Error('Unrecognized data-format specified during transcoding.'));
                return undefined;
            }

            // Chain the decoder and encoder.
            var l1 = handlers[from].encode(data, this), 
                l2 = handlers[to].decode(l1.data, this);

            // Carry the error.
            if (!(l2.error instanceof Error)) {
                l2.error = l1.error;
            }
            return advanced ? l2 : l2.data;
        }
    }, false);

    // Attach an event handler to clear the data cache when chart is disposed.
    global.core.addEventListener('Disposed', function (e) {
        delete cache[e.sender.id];
    });

    // Check for pending data updates
    global.core.addEventListener('Loaded', function (e) {
        var obj = e.sender;
        if (obj instanceof global.core && obj.__state.updatePending !== undefined) {
            global.renderer.update(obj, obj.__state.updatePending);
            delete obj.__state.updatePending;
        }
    });


}());


/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
    eqeqeq: true, plusplus: true, bitwise: true, immed: true */

/*global window: false, Array: false, FusionCharts: false,
    FusionChartsEvents: false, FusionChartsDataFormats: false*/

/*members XML, XMLURL, addEventListener, animate, apply, args,
    attributes, chartType, clone, constructor, core, dispose, exportChart,
    extend, getAttribute, getChartFromId, getDataAsCSV, getElementById,
    getElementsByTagName, getObjectById, getRendererPolicy, getXML,
    hasRendered, height, id, indexOf, isActive, items, lastIndexOf, length,
    link, options, params, policies, print, raiseError, raiseEvent, ref,
    renderer, replace, resize, resizeTo, scriptBaseUri, sender,
    setChartData, setDataURL, setDataXML, signature, split, src, stallLoad,
    substring, success, swfobject, test, toLowerCase, toString, vars, vled,
    width
*/

/**
 * -----------------------------------------------------------------------------
 * Generic Runtime Module
 * -----------------------------------------------------------------------------
 */
(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'GenericRuntime']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    // Set the FusionCharts filename possibilities as regular expression.
    var SCRIPT_NAME_REGEX = /(fusioncharts\.js|fusioncharts\.debug\.js|fusioncharts\.core\.js|fusioncharts\.min\.js|fusioncharts\.packed\.js)(\?.*)?$/i;

    // Collection of chart alias that are not same as swf filename
    var alias = {
        vled: 'realtimeverticalled'
    };

    // Get the script base uri.
    global.core.options.scriptBaseUri = (function () {
        // Get a collection of all script nodes.
        var scripts = document.getElementsByTagName('script'),
            l = scripts.length, scriptBaseUri = '', i;

        // Iterate through the script node collection and match whether its
        // 'src' attribute contains fusioncharts file name.
        for (i = 0; i < l; i += 1) {
            // In case the script name regex matches with an src, we use that
            // src to retrieve the base uri.
            if (SCRIPT_NAME_REGEX.test(scripts[i].getAttribute('src'))) {
                // To retrieve the base path of the src, we simply do a string
                // split withg the fusioncharts filename and get the first item
                // of script base uri.
                scriptBaseUri = scripts[i].getAttribute('src').split(SCRIPT_NAME_REGEX)[0];
                break;
            }
        }

        return scriptBaseUri;

    }());

    // Deconstruct policies.
    // Update the arguments with latest copy of all variables by
    // reverse engineering the policies.
    var deconstructPolicySet = function (policies, options, obj) {
        for (var policy in policies) {
            // Set just the policy object in case of single-level policy.
            if (policies[policy] instanceof Array) {
                options[policies[policy][0]] = obj[policy];
                continue;
            }
            // Copy the source of multi-level policies
            for (var prop in policies[policy]) {
                options[policies[policy][prop][0]] = obj[policy][prop];
            }
        }
    }, lengthCleanupRegex = /[^\%\d]*$/g, signatureMatchRegex = /^FusionCharts/;

    global.extend({
        // Add default object management prototype method to raise deletion
        // event.
        dispose: function () {
            // The event must be async in order to prevent integrity.
            global.raiseEvent('BeforeDispose', {}, this);
            // Delete the reference of the item
            delete global.core.items[this.id];
            // Raise a post-disposal event
            global.raiseEvent('Disposed', {}, this);
        },

        clone: function (params, noCreate) {

            // Create a copy of arguments of this object.
            var options = global.extend({}, this.args);

            // Recreate construction parameters by reverse calculating the global
            // policies.
            deconstructPolicySet(global.policies, options, this);
            // Also deconstruct the rendere specific policies.
            deconstructPolicySet(global.renderer.getRendererPolicy(this.options.renderer),
                options, this);

            // Remove any specific parameters that if cloned will create issues.
            delete options.id;
            delete options.animate;
            delete options.stallLoad;

            // Override any of the options by parameters sent by user
            if (typeof params === 'object') {
                global.extend(options, params);
            }

            // Create new FusionCharts object from the computed options
            return noCreate ? options : new global.core(options);

        },
        
        /**
         * Legacy function that simply calls setDataXML on JS object.
         * @id FusionCharts.setDataXML
         *
         * @param {string} xml The data as string in FusionCharts compatible XML
         * format.
         *
         * @type void
         * @deprecated
         */
        setDataXML: function (xml) {
            // Check whether the XML as parameter can be converted to
            // string or not and then send data to chart.
            if (xml === undefined || xml === null ||
                typeof xml.toString !== 'function') {
                // Notify using error event that invalid data was provided as xml.
                global.raiseError(this, '25081627', 'param', '~setDataXML',
                    'Invalid data type for parameter "xml"');
                return;
            }

            // We check whether the renderer has setDataXML function, we set it.
            if (this.ref === undefined || this.ref === null ||
                typeof this.ref.setDataXML !== 'function') {
                this.setChartData(xml.toString(), FusionChartsDataFormats.XML);
            }
            else {
                // When direct XML updated API is available we use it to directly
                // do remote update of XML.
                this.ref.setDataXML(xml.toString());
            }

        },

        /**
         * @id FusionCharts.setDataURL
         *
         * @param {string} url 
         *
         * @type void
         * @deprecated
         */
        setDataURL: function (url) {
            // Check whether the URL as parameter can be converted to
            // string or not.
            if (url === undefined || url === null ||
                typeof url.toString !== 'function') {
                // Notify using error event that invalid data was provided as xml.
                global.raiseError(this, '25081724', 'param', '~setDataURL',
                    'Invalid data type for parameter "url"');
                return;
            }

            // We check whether the renderer has setDataURL function, we set it.
            if (this.ref === undefined || this.ref === null ||
                typeof this.ref.setDataURL !== 'function') {
                // For flash renderer, we set the flashVars, so that it is picked
                // up when chart renders.
                this.setChartData(url.toString(), FusionChartsDataFormats.XMLURL);
            }
            else {
                // When direct XML updated API is available we use it to directly
                // do remote update of XML.
                this.ref.setDataURL(url.toString());
            }
        },

        isActive: function () {
            if (!this.ref || document.getElementById(this.id) !==
                this.ref || typeof this.ref.signature !== 'function') {
                return false;
            }
            
            try {
                return signatureMatchRegex.test(this.ref.signature());
            }
            catch (e) {
                return false;
            }
        },

        resizeTo: function (w, h, noUpdate) {
            var dimension = {
                width: w,
                height: h
            };

            if (typeof arguments[0] === 'object') {
                dimension.width = arguments[0].width;
                dimension.height = arguments[0].height;
                noUpdate = h;
            }

            if (dimension.width && typeof dimension.width.toString === 'function') {
                this.width = dimension.width.toString().replace(lengthCleanupRegex, '');
            }
            if (dimension.height && typeof dimension.height.toString === 'function') {
                this.height = dimension.height.toString().replace(lengthCleanupRegex, '');
            }

            if (noUpdate !== true) {
                global.renderer.resize(this, dimension);
            }
        },
        
        // Add function to get chart type name from SWF
        chartType: function () {
            var url = this.src.substring(this.src.indexOf('.swf'), 0),
                file = url.substring(url.lastIndexOf('/') + 1).toLowerCase();
            return alias[file] === undefined ? file : alias[file];
        }

    }, true);

    // Globally expose getChartFromId method.
    window.getChartFromId = function (id) {
        return global.core.items[id] instanceof global.core ?
            global.core.items[id].ref : global.swfobject.getObjectById(id);
    };


    // This function allows users to make a generic call from renderer
    // of the chart to main FusionCharts object.
    var jsCall = function (obj, prop) {
        // Check whether me
        if (typeof obj[prop] === 'function') {
            return function () {
                return obj[prop].apply(obj, arguments);
            };
        }
        return obj[prop];
    };

    /**
     * Extend FusionCharts capabilities to swf HTMLNode object
     */
    global.addEventListener('internal.DOMElementCreated', function (event, args) {
        // Check whether the object was added to DOM or not.
        if (args.ref === undefined || args.success !== true) {
            return;
        }

        // List the properties that are not to be extended.
        var ignore = {
            options: true,
            vars: true,
            attributes: true,
            params: true,
            src: true,
            ref: true,
            constructor: true,
            setDataXML: true,
            setDataURL: true,
            hasRendered: true,
            getXML: true,
            getDataAsCSV: true,
            print: true,
            exportChart: true,
            signature: true,
            link: true
        };

        // Iterate through the FusionCharts object and add its variables to the
        // HTMLNode object.
        for (var prop in event.sender) {
            // Discontinue adding this object in case the property is marked to
            // be ignored.
            if (ignore[prop] === true || args.ref[prop] !== undefined) {
                continue;
            }
            try {
                args.ref[prop] = jsCall(event.sender, prop);
            }
            // Suppress error while updating DOM events.
            catch (e) { }
        }
    });

}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global Array: false, FusionCharts, RegExp: false, FusionChartsDataFormats: false */

/*members JSON, animation, chart, extend, getChartAttribute,
    getChartData, graph, length, raiseError, setChartAttribute,
    setChartData, toLowerCase, toString
*/

/**
 * -----------------------------------------------------------------------------
 * Dynamic Chart Attributes Module
 * -----------------------------------------------------------------------------
 * This module contains codes required to get and set chart attributes from a
 * FusionCharts object using simple getter and setter functions. These method
 * completely bypasses the data transfer from the SWF and computes chart
 * attributes based on the last set data.
 */
(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'DynamicChartAttributes']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    global.extend({
        /**
         * Updates a FusionCharts object's XML root's attribute with the new
         * attribute-value pair. In case the attribute does not exist, it adds
         * it.
         *
         * @param attributes {object} The attributes to be updated.
         *
         * @type string
         * @return Updated FusionCharts DataXML with the new attribute added or
         * updated
         */
        setChartAttribute: function (attributes) {

            // In case attribute is sent as separate arguments, combine them
            // to one object.
            if (arguments.length > 1 && typeof attributes === 'string') {
                var temp = arguments[0];
                attributes = {};
                attributes[temp] = arguments[1];
            }
            // In case user sends invalid parameters for attributes.
            else if (attributes === null || typeof attributes !== 'object') {
                return;
            }

            // Get chart attributes.
            var i = 0, json = this.getChartData(FusionChartsDataFormats.JSON), prop,
                attList = json.chart || json.graph || {};

            // Iterate through attributes and update them.
            for (prop in attributes) {
                i += 1;
                attList[prop.toLowerCase()] = attributes[prop];
            }
            // Update chart's XML.
            if (i > 0) {
                // In case animation is not specified, then turn it off.
                if (typeof attList.animation === 'undefined') {
                    attList.animation = '0';
                }
                this.setChartData(json, FusionChartsDataFormats.JSON);
            }
        },

        /**
         * Returns the value of a specific chart attribute.
         *
         * @param attribute {string} The attributes to be fetched.
         *
         * @type string
         * @return The value of the attribute.
         */
        getChartAttribute: function (attribute) {

            // Get chart attributes.
            var attList = (attList = 
                this.getChartData(FusionChartsDataFormats.JSON)).chart || attList.graph;
            
            // In case no argument is passed, we return the entire set of
            // chart attributes object.
            if (arguments.length === 0 || attribute === undefined || attList === undefined) {
                return attList;
            }

            // Create a variable that will store reference to the parameter that
            // contains attributes. This helps in case user sends one attribute
            // as string, we covert it to an array witj one element.
            var value, i;

            // Convert single attribute to array with one element or directly
            // send the value as return.
            if (typeof attribute === 'string') {
                value = attList[attribute.toString().toLowerCase()];
            }

            // In case user sends an array of attributes, we compile an object
            // for the same and return.
            else if (attribute instanceof Array) {
                value = {};
                for (i = 0; i < attribute.length; i += 1) {
                    value[attribute[i]] =
                        attList[attribute[i].toString().toLowerCase()];
                }
            }

            // If all above conditions fail, there must be some issue with the
            // parameters.
            else {
                global.raiseError(this, '25081429', 'param', '~getChartAttribute()',
                    'Unexpected value of "attribute"');
            }

            // We return 'value' variable here as because it is equivalent to
            // sending '{}' in case above conditions fail.
            return value;
        }
    }, true);
}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, immed: true */

/*global Array: false, FusionCharts, window: false,
    FusionChartsDataFormats: false */

/*members DOMId, InvalidXMLText, XML, XMLURL, __fusioncharts_dimension,
    __fusioncharts_event, __fusioncharts_vars, addEventListener,
    addVariable, align, allowScriptAccess, animation, appendChild,
    attributes, attrs, autoInstallRedirect, base, bgColor,
    cancelDataLoadRequest, chartHeight, chartWidth, config, configure,
    confirm, core, createElement, dataFormat, dataInvokedOnSWF, dataSource,
    dataURL, dataXML, debugMode, display, embedSWF, encodeURIComponent,
    error, escaped, exec, extend, flashVars, format, getChartData,
    getElementsByTagName, getSWFHTML, getTime, getXML,
    hasFlashPlayerVersion, height, href, id, init, innerHTML,
    installRedirectMessage, isActive, items, lang, location, menu,
    offsetHeight, offsetWidth, options, params, parentNode, policies,
    protocol, prototype, quality, raiseError, raiseEvent, raiseWarning, ref,
    register, registerWithJS, removeChild, removeEventListener, removeSWF,
    render, renderer, replace, requiredFlashPlayerVersion, resize, safeMode,
    salign, scale, scaleMode, sender, setAttribute, setChartData,
    setDataURL, setDataXML, setDefault, setTransparent, showChartMessage,
    source, src, stallLoad, stopPropagation, style, swLiveConnect,
    swfobject, test, toString, toUpperCase, type, unescape, update, url,
    userAgent, wMode, width, data
*/

/**
 * -----------------------------------------------------------------------------
 * Flash Renderer Module
 * -----------------------------------------------------------------------------
 */
(function () {

    // Register the module with FusionCharts and als oget access to a global
    // variable within the core's scope.
    var global = FusionCharts(['private', 'Flash_Renderer']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    // Include swfobject in local scope if not already present in the global
    // scope. Make sure to double test original swfobject by testing expected
    // properties of swfobject.
    var swfobject = global.swfobject = function () {

            var UNDEF = "undefined",
                    OBJECT = "object",
                    SHOCKWAVE_FLASH = "Shockwave Flash",
                    SHOCKWAVE_FLASH_AX = "ShockwaveFlash.ShockwaveFlash",
                    FLASH_MIME_TYPE = "application/x-shockwave-flash",
                    EXPRESS_INSTALL_ID = "SWFObjectExprInst",
                    ON_READY_STATE_CHANGE = "onreadystatechange",

                    win = window,
                    doc = document,
                    nav = navigator,

                    plugin = false,
                    domLoadFnArr = [main],
                    regObjArr = [],
                    objIdArr = [],
                    listenersArr = [],
                    storedAltContent,
                    storedAltContentId,
                    storedCallbackFn,
                    storedCallbackObj,
                    isDomLoaded = false,
                    isExpressInstallActive = false,
                    dynamicStylesheet,
                    dynamicStylesheetMedia,
                    autoHideShow = true,

            /* Centralized function for browser feature detection
                    - User agent string detection is only used when no good alternative is possible
                    - Is executed directly for optimal performance
            */
            ua = function() {
                    var w3cdom = typeof doc.getElementById != UNDEF && typeof doc.getElementsByTagName != UNDEF && typeof doc.createElement != UNDEF,
                            u = nav.userAgent.toLowerCase(),
                            p = nav.platform.toLowerCase(),
                            windows = p ? /win/.test(p) : /win/.test(u),
                            mac = p ? /mac/.test(p) : /mac/.test(u),
                            webkit = /webkit/.test(u) ? parseFloat(u.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : false, // returns either the webkit version or false if not webkit
                            ie = !+"\v1", // feature detection based on Andrea Giammarchi's solution: http://webreflection.blogspot.com/2009/01/32-bytes-to-know-if-your-browser-is-ie.html
                            playerVersion = [0,0,0],
                            d = null;
                    if (typeof nav.plugins != UNDEF && typeof nav.plugins[SHOCKWAVE_FLASH] == OBJECT) {
                            d = nav.plugins[SHOCKWAVE_FLASH].description;
                            if (d && !(typeof nav.mimeTypes != UNDEF && nav.mimeTypes[FLASH_MIME_TYPE] && !nav.mimeTypes[FLASH_MIME_TYPE].enabledPlugin)) { // navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin indicates whether plug-ins are enabled or disabled in Safari 3+
                                    plugin = true;
                                    ie = false; // cascaded feature detection for Internet Explorer
                                    d = d.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
                                    playerVersion[0] = parseInt(d.replace(/^(.*)\..*$/, "$1"), 10);
                                    playerVersion[1] = parseInt(d.replace(/^.*\.(.*)\s.*$/, "$1"), 10);
                                    playerVersion[2] = /[a-zA-Z]/.test(d) ? parseInt(d.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0;
                            }
                    }
                    else if (typeof win.ActiveXObject != UNDEF) {
                            try {
                                    var a = new ActiveXObject(SHOCKWAVE_FLASH_AX);
                                    if (a) { // a will return null when ActiveX is disabled
                                            // Fix for /bug#462
                                            //!// d = a.GetVariable("$version");
                                            try {
                                                d = a.GetVariable("$version");
                                            }
                                            catch (e) { }
                                            // End fix for /bug#462
                                            if (d) {
                                                    ie = true; // cascaded feature detection for Internet Explorer
                                                    d = d.split(" ")[1].split(",");
                                                    playerVersion = [parseInt(d[0], 10), parseInt(d[1], 10), parseInt(d[2], 10)];
                                            }
                                    }
                            }
                            catch(e) {}
                    }
                    return {w3:w3cdom, pv:playerVersion, wk:webkit, ie:ie, win:windows, mac:mac};
            }(),

            /* Cross-browser onDomLoad
                    - Will fire an event as soon as the DOM of a web page is loaded
                    - Internet Explorer workaround based on Diego Perini's solution: http://javascript.nwbox.com/IEContentLoaded/
                    - Regular onload serves as fallback
            */
            onDomLoad = function() {
                    if (!ua.w3) {return;}
                    if ((typeof doc.readyState != UNDEF && doc.readyState == "complete") || (typeof doc.readyState == UNDEF && (doc.getElementsByTagName("body")[0] || doc.body))) { // function is fired after onload, e.g. when script is inserted dynamically
                            callDomLoadFunctions();
                    }
                    if (!isDomLoaded) {
                            if (typeof doc.addEventListener != UNDEF) {
                                    doc.addEventListener("DOMContentLoaded", callDomLoadFunctions, false);
                            }
                            if (ua.ie && ua.win) {
                                    doc.attachEvent(ON_READY_STATE_CHANGE, function() {
                                            if (doc.readyState == "complete") {
                                                    doc.detachEvent(ON_READY_STATE_CHANGE, arguments.callee);
                                                    callDomLoadFunctions();
                                            }
                                    });
                                    if (win == top) { // if not inside an iframe
                                            (function(){
                                                    if (isDomLoaded) {return;}
                                                    try {
                                                            doc.documentElement.doScroll("left");
                                                    }
                                                    catch(e) {
                                                            setTimeout(arguments.callee, 0);
                                                            return;
                                                    }
                                                    callDomLoadFunctions();
                                            })();
                                    }
                            }
                            if (ua.wk) {
                                    (function(){
                                            if (isDomLoaded) {return;}
                                            if (!/loaded|complete/.test(doc.readyState)) {
                                                    setTimeout(arguments.callee, 0);
                                                    return;
                                            }
                                            callDomLoadFunctions();
                                    })();
                            }
                            addLoadEvent(callDomLoadFunctions);
                    }
            }();

            function callDomLoadFunctions() {
                    if (isDomLoaded) {return;}
                    try { // test if we can really add/remove elements to/from the DOM; we don't want to fire it too early
                            var t = doc.getElementsByTagName("body")[0].appendChild(createElement("span"));
                            t.parentNode.removeChild(t);
                    }
                    catch (e) {return;}
                    isDomLoaded = true;
                    var dl = domLoadFnArr.length;
                    for (var i = 0; i < dl; i++) {
                            domLoadFnArr[i]();
                    }
            }

            function addDomLoadEvent(fn) {
                    if (isDomLoaded) {
                            fn();
                    }
                    else {
                            domLoadFnArr[domLoadFnArr.length] = fn; // Array.push() is only available in IE5.5+
                    }
            }

            /* Cross-browser onload
                    - Based on James Edwards' solution: http://brothercake.com/site/resources/scripts/onload/
                    - Will fire an event as soon as a web page including all of its assets are loaded
             */
            function addLoadEvent(fn) {
                    if (typeof win.addEventListener != UNDEF) {
                            win.addEventListener("load", fn, false);
                    }
                    else if (typeof doc.addEventListener != UNDEF) {
                            doc.addEventListener("load", fn, false);
                    }
                    else if (typeof win.attachEvent != UNDEF) {
                            addListener(win, "onload", fn);
                    }
                    else if (typeof win.onload == "function") {
                            var fnOld = win.onload;
                            win.onload = function() {
                                    fnOld();
                                    fn();
                            };
                    }
                    else {
                            win.onload = fn;
                    }
            }

            /* Main function
                    - Will preferably execute onDomLoad, otherwise onload (as a fallback)
            */
            function main() {
                    if (plugin) {
                            testPlayerVersion();
                    }
                    else {
                            matchVersions();
                    }
            }

            /* Detect the Flash Player version for non-Internet Explorer browsers
                    - Detecting the plug-in version via the object element is more precise than using the plugins collection item's description:
                      a. Both release and build numbers can be detected
                      b. Avoid wrong descriptions by corrupt installers provided by Adobe
                      c. Avoid wrong descriptions by multiple Flash Player entries in the plugin Array, caused by incorrect browser imports
                    - Disadvantage of this method is that it depends on the availability of the DOM, while the plugins collection is immediately available
            */
            function testPlayerVersion() {
                    var b = doc.getElementsByTagName("body")[0];
                    var o = createElement(OBJECT);
                    o.setAttribute("type", FLASH_MIME_TYPE);
                    var t = b.appendChild(o);
                    if (t) {
                            var counter = 0;
                            (function(){
                                    if (typeof t.GetVariable != UNDEF) {
                                            // Fix for /bug#462
                                            //!// var d = t.GetVariable("$version");
                                            var d;
                                            try {
                                                d = t.GetVariable("$version");
                                            }
                                            catch (e) { }
                                            // End fix for /bug#462
                                            if (d) {
                                                    d = d.split(" ")[1].split(",");
                                                    ua.pv = [parseInt(d[0], 10), parseInt(d[1], 10), parseInt(d[2], 10)];
                                            }
                                    }
                                    else if (counter < 10) {
                                            counter++;
                                            setTimeout(arguments.callee, 10);
                                            return;
                                    }
                                    b.removeChild(o);
                                    t = null;
                                    matchVersions();
                            })();
                    }
                    else {
                            matchVersions();
                    }
            }

            /* Perform Flash Player and SWF version matching; static publishing only
            */
            function matchVersions() {
                    var rl = regObjArr.length;
                    if (rl > 0) {
                            for (var i = 0; i < rl; i++) { // for each registered object element
                                    var id = regObjArr[i].id;
                                    var cb = regObjArr[i].callbackFn;
                                    var cbObj = {success:false, id:id};
                                    if (ua.pv[0] > 0) {
                                            var obj = getElementById(id);
                                            if (obj) {
                                                    if (hasPlayerVersion(regObjArr[i].swfVersion) && !(ua.wk && ua.wk < 312)) { // Flash Player version >= published SWF version: Houston, we have a match!
                                                            setVisibility(id, true);
                                                            if (cb) {
                                                                    cbObj.success = true;
                                                                    cbObj.ref = getObjectById(id);
                                                                    cb(cbObj);
                                                            }
                                                    }
                                                    else if (regObjArr[i].expressInstall && canExpressInstall()) { // show the Adobe Express Install dialog if set by the web page author and if supported
                                                            var att = {};
                                                            att.data = regObjArr[i].expressInstall;
                                                            att.width = obj.getAttribute("width") || "0";
                                                            att.height = obj.getAttribute("height") || "0";
                                                            if (obj.getAttribute("class")) {att.styleclass = obj.getAttribute("class");}
                                                            if (obj.getAttribute("align")) {att.align = obj.getAttribute("align");}
                                                            // parse HTML object param element's name-value pairs
                                                            var par = {};
                                                            var p = obj.getElementsByTagName("param");
                                                            var pl = p.length;
                                                            for (var j = 0; j < pl; j++) {
                                                                    if (p[j].getAttribute("name").toLowerCase() != "movie") {
                                                                            par[p[j].getAttribute("name")] = p[j].getAttribute("value");
                                                                    }
                                                            }
                                                            showExpressInstall(att, par, id, cb);
                                                    }
                                                    else { // Flash Player and SWF version mismatch or an older Webkit engine that ignores the HTML object element's nested param elements: display alternative content instead of SWF
                                                            displayAltContent(obj);
                                                            if (cb) {cb(cbObj);}
                                                    }
                                            }
                                    }
                                    else {	// if no Flash Player is installed or the fp version cannot be detected we let the HTML object element do its job (either show a SWF or alternative content)
                                            setVisibility(id, true);
                                            if (cb) {
                                                    var o = getObjectById(id); // test whether there is an HTML object element or not
                                                    if (o && typeof o.SetVariable != UNDEF) {
                                                            cbObj.success = true;
                                                            cbObj.ref = o;
                                                    }
                                                    cb(cbObj);
                                            }
                                    }
                            }
                    }
            }

            function getObjectById(objectIdStr) {
                    var r = null;
                    var o = getElementById(objectIdStr);
                    if (o && o.nodeName == "OBJECT") {
                            if (typeof o.SetVariable != UNDEF) {
                                    r = o;
                            }
                            else {
                                    var n = o.getElementsByTagName(OBJECT)[0];
                                    if (n) {
                                            r = n;
                                    }
                            }
                    }
                    return r;
            }

            /* Requirements for Adobe Express Install
                    - only one instance can be active at a time
                    - fp 6.0.65 or higher
                    - Win/Mac OS only
                    - no Webkit engines older than version 312
            */
            function canExpressInstall() {
                    return !isExpressInstallActive && hasPlayerVersion("6.0.65") && (ua.win || ua.mac) && !(ua.wk && ua.wk < 312);
            }

            /* Show the Adobe Express Install dialog
                    - Reference: http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=6a253b75
            */
            function showExpressInstall(att, par, replaceElemIdStr, callbackFn) {
                    isExpressInstallActive = true;
                    storedCallbackFn = callbackFn || null;
                    storedCallbackObj = {success:false, id:replaceElemIdStr};
                    var obj = getElementById(replaceElemIdStr);
                    if (obj) {
                            if (obj.nodeName == "OBJECT") { // static publishing
                                    storedAltContent = abstractAltContent(obj);
                                    storedAltContentId = null;
                            }
                            else { // dynamic publishing
                                    storedAltContent = obj;
                                    storedAltContentId = replaceElemIdStr;
                            }
                            att.id = EXPRESS_INSTALL_ID;
                            if (typeof att.width == UNDEF || (!/%$/.test(att.width) && parseInt(att.width, 10) < 310)) {att.width = "310";}
                            if (typeof att.height == UNDEF || (!/%$/.test(att.height) && parseInt(att.height, 10) < 137)) {att.height = "137";}
                            doc.title = doc.title.slice(0, 47) + " - Flash Player Installation";
                            var pt = ua.ie && ua.win ? "ActiveX" : "PlugIn",
                                    fv = "MMredirectURL=" + win.location.toString().replace(/&/g,"%26") + "&MMplayerType=" + pt + "&MMdoctitle=" + doc.title;
                            if (typeof par.flashvars != UNDEF) {
                                    par.flashvars += "&" + fv;
                            }
                            else {
                                    par.flashvars = fv;
                            }
                            // IE only: when a SWF is loading (AND: not available in cache) wait for the readyState of the object element to become 4 before removing it,
                            // because you cannot properly cancel a loading SWF file without breaking browser load references, also obj.onreadystatechange doesn't work
                            if (ua.ie && ua.win && obj.readyState != 4) {
                                    var newObj = createElement("div");
                                    replaceElemIdStr += "SWFObjectNew";
                                    newObj.setAttribute("id", replaceElemIdStr);
                                    obj.parentNode.insertBefore(newObj, obj); // insert placeholder div that will be replaced by the object element that loads expressinstall.swf
                                    obj.style.display = "none";
                                    (function(){
                                            if (obj.readyState == 4) {
                                                    obj.parentNode.removeChild(obj);
                                            }
                                            else {
                                                    setTimeout(arguments.callee, 10);
                                            }
                                    })();
                            }
                            createSWF(att, par, replaceElemIdStr);
                    }
            }

            /* Functions to abstract and display alternative content
            */
            function displayAltContent(obj) {
                    if (ua.ie && ua.win && obj.readyState != 4) {
                            // IE only: when a SWF is loading (AND: not available in cache) wait for the readyState of the object element to become 4 before removing it,
                            // because you cannot properly cancel a loading SWF file without breaking browser load references, also obj.onreadystatechange doesn't work
                            var el = createElement("div");
                            obj.parentNode.insertBefore(el, obj); // insert placeholder div that will be replaced by the alternative content
                            el.parentNode.replaceChild(abstractAltContent(obj), el);
                            obj.style.display = "none";
                            (function(){
                                    if (obj.readyState == 4) {
                                            obj.parentNode.removeChild(obj);
                                    }
                                    else {
                                            setTimeout(arguments.callee, 10);
                                    }
                            })();
                    }
                    else {
                            obj.parentNode.replaceChild(abstractAltContent(obj), obj);
                    }
            }

            function abstractAltContent(obj) {
                    var ac = createElement("div");
                    if (ua.win && ua.ie) {
                            ac.innerHTML = obj.innerHTML;
                    }
                    else {
                            var nestedObj = obj.getElementsByTagName(OBJECT)[0];
                            if (nestedObj) {
                                    var c = nestedObj.childNodes;
                                    if (c) {
                                            var cl = c.length;
                                            for (var i = 0; i < cl; i++) {
                                                    if (!(c[i].nodeType == 1 && c[i].nodeName == "PARAM") && !(c[i].nodeType == 8)) {
                                                            ac.appendChild(c[i].cloneNode(true));
                                                    }
                                            }
                                    }
                            }
                    }
                    return ac;
            }

            /* Cross-browser dynamic SWF creation
            */
            function createSWF(attObj, parObj, id) {
                    var r, el = getElementById(id);
                    if (ua.wk && ua.wk < 312) {return r;}
                    if (el) {
                            if (typeof attObj.id == UNDEF) { // if no 'id' is defined for the object element, it will inherit the 'id' from the alternative content
                                    attObj.id = id;
                            }
                            if (ua.ie && ua.win) { // Internet Explorer + the HTML object element + W3C DOM methods do not combine: fall back to outerHTML
                                    var att = "";
                                    for (var i in attObj) {
                                            if (attObj[i] != Object.prototype[i]) { // filter out prototype additions from other potential libraries
                                                    if (i.toLowerCase() == "data") {
                                                            parObj.movie = attObj[i];
                                                    }
                                                    else if (i.toLowerCase() == "styleclass") { // 'class' is an ECMA4 reserved keyword
                                                            att += ' class="' + attObj[i] + '"';
                                                    }
                                                    else if (i.toLowerCase() != "classid") {
                                                            att += ' ' + i + '="' + attObj[i] + '"';
                                                    }
                                            }
                                    }
                                    var par = "";
                                    for (var j in parObj) {
                                            if (parObj[j] != Object.prototype[j]) { // filter out prototype additions from other potential libraries
                                                    par += '<param name="' + j + '" value="' + parObj[j] + '" />';
                                            }
                                    }
                                    el.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + att + '>' + par + '</object>';
                                    objIdArr[objIdArr.length] = attObj.id; // stored to fix object 'leaks' on unload (dynamic publishing only)
                                    r = getElementById(attObj.id);
                            }
                            else { // well-behaving browsers
                                    var o = createElement(OBJECT);
                                    o.setAttribute("type", FLASH_MIME_TYPE);
                                    for (var m in attObj) {
                                            if (attObj[m] != Object.prototype[m]) { // filter out prototype additions from other potential libraries
                                                    if (m.toLowerCase() == "styleclass") { // 'class' is an ECMA4 reserved keyword
                                                            o.setAttribute("class", attObj[m]);
                                                    }
                                                    else if (m.toLowerCase() != "classid") { // filter out IE specific attribute
                                                            o.setAttribute(m, attObj[m]);
                                                    }
                                            }
                                    }
                                    for (var n in parObj) {
                                            if (parObj[n] != Object.prototype[n] && n.toLowerCase() != "movie") { // filter out prototype additions from other potential libraries and IE specific param element
                                                    createObjParam(o, n, parObj[n]);
                                            }
                                    }
                                    el.parentNode.replaceChild(o, el);
                                    r = o;
                            }
                    }
                    return r;
            }

            function createObjParam(el, pName, pValue) {
                    var p = createElement("param");
                    p.setAttribute("name", pName);
                    p.setAttribute("value", pValue);
                    el.appendChild(p);
            }

            /* Cross-browser SWF removal
                    - Especially needed to safely and completely remove a SWF in Internet Explorer
            */
            function removeSWF(id) {
                    var obj = getElementById(id);
                    if (obj && obj.nodeName == "OBJECT") {
                            if (ua.ie && ua.win) {
                                    obj.style.display = "none";
                                    (function(){
                                            if (obj.readyState == 4) {
                                                    removeObjectInIE(id);
                                            }
                                            else {
                                                    setTimeout(arguments.callee, 10);
                                            }
                                    })();
                            }
                            else {
                                    obj.parentNode.removeChild(obj);
                            }
                    }
            }

            function removeObjectInIE(id) {
                    var obj = getElementById(id);
                    if (obj) {
                            for (var i in obj) {
                                    if (typeof obj[i] == "function") {
                                            obj[i] = null;
                                    }
                            }
                            obj.parentNode.removeChild(obj);
                    }
            }

            /* Functions to optimize JavaScript compression
            */
            function getElementById(id) {
                    var el = null;
                    try {
                            el = doc.getElementById(id);
                    }
                    catch (e) {}
                    return el;
            }

            function createElement(el) {
                    return doc.createElement(el);
            }

            /* Updated attachEvent function for Internet Explorer
                    - Stores attachEvent information in an Array, so on unload the detachEvent functions can be called to avoid memory leaks
            */
            function addListener(target, eventType, fn) {
                    target.attachEvent(eventType, fn);
                    listenersArr[listenersArr.length] = [target, eventType, fn];
            }

            /* Flash Player and SWF content version matching
            */
            function hasPlayerVersion(rv) {
                    var pv = ua.pv, v = rv.split(".");
                    v[0] = parseInt(v[0], 10);
                    v[1] = parseInt(v[1], 10) || 0; // supports short notation, e.g. "9" instead of "9.0.0"
                    v[2] = parseInt(v[2], 10) || 0;
                    return (pv[0] > v[0] || (pv[0] == v[0] && pv[1] > v[1]) || (pv[0] == v[0] && pv[1] == v[1] && pv[2] >= v[2])) ? true : false;
            }

            /* Cross-browser dynamic CSS creation
                    - Based on Bobby van der Sluis' solution: http://www.bobbyvandersluis.com/articles/dynamicCSS.php
            */
            function createCSS(sel, decl, media, newStyle) {
                    if (ua.ie && ua.mac) {return;}
                    var h = doc.getElementsByTagName("head")[0];
                    if (!h) {return;} // to also support badly authored HTML pages that lack a head element
                    var m = (media && typeof media == "string") ? media : "screen";
                    if (newStyle) {
                            dynamicStylesheet = null;
                            dynamicStylesheetMedia = null;
                    }
                    if (!dynamicStylesheet || dynamicStylesheetMedia != m) {
                            // create dynamic stylesheet + get a global reference to it
                            var s = createElement("style");
                            s.setAttribute("type", "text/css");
                            s.setAttribute("media", m);
                            dynamicStylesheet = h.appendChild(s);
                            if (ua.ie && ua.win && typeof doc.styleSheets != UNDEF && doc.styleSheets.length > 0) {
                                    dynamicStylesheet = doc.styleSheets[doc.styleSheets.length - 1];
                            }
                            dynamicStylesheetMedia = m;
                    }
                    // add style rule
                    if (ua.ie && ua.win) {
                            if (dynamicStylesheet && typeof dynamicStylesheet.addRule == OBJECT) {
                                    dynamicStylesheet.addRule(sel, decl);
                            }
                    }
                    else {
                            if (dynamicStylesheet && typeof doc.createTextNode != UNDEF) {
                                    dynamicStylesheet.appendChild(doc.createTextNode(sel + " {" + decl + "}"));
                            }
                    }
            }

            function setVisibility(id, isVisible) {
                    if (!autoHideShow) {return;}
                    var v = isVisible ? "visible" : "hidden";
                    if (isDomLoaded && getElementById(id)) {
                            getElementById(id).style.visibility = v;
                    }
                    else {
                            createCSS("#" + id, "visibility:" + v);
                    }
            }

            /* Filter to avoid XSS attacks
            */
            function urlEncodeIfNecessary(s) {
                    var regex = /[\\\"<>\.;]/;
                    var hasBadChars = regex.exec(s) != null;
                    return hasBadChars && typeof encodeURIComponent != UNDEF ? encodeURIComponent(s) : s;
            }

            /* Release memory to avoid memory leaks caused by closures, fix hanging audio/video threads and force open sockets/NetConnections to disconnect (Internet Explorer only)
            */
            var cleanup = function() {
                    if (ua.ie && ua.win) {
                            window.attachEvent("onunload", function() {
                                    // remove listeners to avoid memory leaks
                                    var ll = listenersArr.length;
                                    for (var i = 0; i < ll; i++) {
                                            listenersArr[i][0].detachEvent(listenersArr[i][1], listenersArr[i][2]);
                                    }
                                    // cleanup dynamically embedded objects to fix audio/video threads and force open sockets and NetConnections to disconnect
                                    var il = objIdArr.length;
                                    for (var j = 0; j < il; j++) {
                                            removeSWF(objIdArr[j]);
                                    }
                                    // cleanup library's main closures to avoid memory leaks
                                    for (var k in ua) {
                                            ua[k] = null;
                                    }
                                    ua = null;
                                    for (var l in swfobject) {
                                            swfobject[l] = null;
                                    }
                                    swfobject = null;
                            });
                    }
            }();

            return {
                    /* Public API
                            - Reference: http://code.google.com/p/swfobject/wiki/documentation
                    */
                    registerObject: function(objectIdStr, swfVersionStr, xiSwfUrlStr, callbackFn) {
                            if (ua.w3 && objectIdStr && swfVersionStr) {
                                    var regObj = {};
                                    regObj.id = objectIdStr;
                                    regObj.swfVersion = swfVersionStr;
                                    regObj.expressInstall = xiSwfUrlStr;
                                    regObj.callbackFn = callbackFn;
                                    regObjArr[regObjArr.length] = regObj;
                                    setVisibility(objectIdStr, false);
                            }
                            else if (callbackFn) {
                                    callbackFn({success:false, id:objectIdStr});
                            }
                    },

                    getObjectById: function(objectIdStr) {
                            if (ua.w3) {
                                    return getObjectById(objectIdStr);
                            }
                    },

                    embedSWF: function(swfUrlStr, replaceElemIdStr, widthStr, heightStr, swfVersionStr, xiSwfUrlStr, flashvarsObj, parObj, attObj, callbackFn) {
                            var callbackObj = {success:false, id:replaceElemIdStr};
                            if (ua.w3 && !(ua.wk && ua.wk < 312) && swfUrlStr && replaceElemIdStr && widthStr && heightStr && swfVersionStr) {
                                    setVisibility(replaceElemIdStr, false);
                                    addDomLoadEvent(function() {
                                            widthStr += ""; // auto-convert to string
                                            heightStr += "";
                                            var att = {};
                                            if (attObj && typeof attObj === OBJECT) {
                                                    for (var i in attObj) { // copy object to avoid the use of references, because web authors often reuse attObj for multiple SWFs
                                                            att[i] = attObj[i];
                                                    }
                                            }
                                            att.data = swfUrlStr;
                                            att.width = widthStr;
                                            att.height = heightStr;
                                            var par = {};
                                            if (parObj && typeof parObj === OBJECT) {
                                                    for (var j in parObj) { // copy object to avoid the use of references, because web authors often reuse parObj for multiple SWFs
                                                            par[j] = parObj[j];
                                                    }
                                            }
                                            if (flashvarsObj && typeof flashvarsObj === OBJECT) {
                                                    for (var k in flashvarsObj) { // copy object to avoid the use of references, because web authors often reuse flashvarsObj for multiple SWFs
                                                            if (typeof par.flashvars != UNDEF) {
                                                                    par.flashvars += "&" + k + "=" + flashvarsObj[k];
                                                            }
                                                            else {
                                                                    par.flashvars = k + "=" + flashvarsObj[k];
                                                            }
                                                    }
                                            }
                                            if (hasPlayerVersion(swfVersionStr)) { // create SWF
                                                    var obj = createSWF(att, par, replaceElemIdStr);
                                                    if (att.id == replaceElemIdStr) {
                                                            setVisibility(replaceElemIdStr, true);
                                                    }
                                                    callbackObj.success = true;
                                                    callbackObj.ref = obj;
                                            }
                                            else if (xiSwfUrlStr && canExpressInstall()) { // show Adobe Express Install
                                                    att.data = xiSwfUrlStr;
                                                    showExpressInstall(att, par, replaceElemIdStr, callbackFn);
                                                    return;
                                            }
                                            else { // show alternative content
                                                    setVisibility(replaceElemIdStr, true);
                                            }
                                            if (callbackFn) {callbackFn(callbackObj);}
                                    });
                            }
                            else if (callbackFn) {callbackFn(callbackObj);}
                    },

                    switchOffAutoHideShow: function() {
                            autoHideShow = false;
                    },

                    ua: ua,

                    getFlashPlayerVersion: function() {
                            return {major:ua.pv[0], minor:ua.pv[1], release:ua.pv[2]};
                    },

                    hasFlashPlayerVersion: hasPlayerVersion,

                    createSWF: function(attObj, parObj, replaceElemIdStr) {
                            if (ua.w3) {
                                    return createSWF(attObj, parObj, replaceElemIdStr);
                            }
                            else {
                                    return undefined;
                            }
                    },

                    showExpressInstall: function(att, par, replaceElemIdStr, callbackFn) {
                            if (ua.w3 && canExpressInstall()) {
                                    showExpressInstall(att, par, replaceElemIdStr, callbackFn);
                            }
                    },

                    removeSWF: function(objElemIdStr) {
                            if (ua.w3) {
                                    removeSWF(objElemIdStr);
                            }
                    },

                    createCSS: function(selStr, declStr, mediaStr, newStyleBoolean) {
                            if (ua.w3) {
                                    createCSS(selStr, declStr, mediaStr, newStyleBoolean);
                            }
                    },

                    addDomLoadEvent: addDomLoadEvent,

                    addLoadEvent: addLoadEvent,

                    getQueryParamValue: function(param) {
                            var q = doc.location.search || doc.location.hash;
                            if (q) {
                                    if (/\?/.test(q)) {q = q.split("?")[1];} // strip question mark
                                    if (param == null) {
                                            return urlEncodeIfNecessary(q);
                                    }
                                    var pairs = q.split("&");
                                    for (var i = 0; i < pairs.length; i++) {
                                            if (pairs[i].substring(0, pairs[i].indexOf("=")) == param) {
                                                    return urlEncodeIfNecessary(pairs[i].substring((pairs[i].indexOf("=") + 1)));
                                            }
                                    }
                            }
                            return "";
                    },

                    // For internal usage only
                    expressInstallCallback: function() {
                            if (isExpressInstallActive) {
                                    var obj = getElementById(EXPRESS_INSTALL_ID);
                                    if (obj && storedAltContent) {
                                            obj.parentNode.replaceChild(storedAltContent, obj);
                                            if (storedAltContentId) {
                                                    setVisibility(storedAltContentId, true);
                                                    if (ua.ie && ua.win) {storedAltContent.style.display = "block";}
                                            }
                                            if (storedCallbackFn) {storedCallbackFn(storedCallbackObj);}
                                    }
                                    isExpressInstallActive = false;
                            }
                    }
            };
    }();


    // Set flags for minimum version of flash-player needed.
    global.core.options.requiredFlashPlayerVersion = '8';
    // Message to show upon auto install redirect.
    global.core.options.installRedirectMessage = 'You need Adobe Flash ' +
        'Player 8 (or above) to view the charts on this page. It is a free, ' +
        'lightweight and safe installation from Adobe Systems Incorporated.' +
        '\n\nWould you like to go to Adobe\'s website and install Flash Player?';

    var noFlashRedirectNotified = false;

    // To avoid XSS attacks, we URLEncode FlashVars.
    var checkBadChars = /[\\\"<>;]/;
    var urlEncodeIfNecessary = function (s) {
        return (checkBadChars.exec(s) !== null) &&
            typeof window.encodeURIComponent !== undefined ?
            window.encodeURIComponent(s) : s;
    };

        /**
     * Event listener that updates local chart data when there has
     * been a remote data update on renderer.
     */
    var syncDataStore =  function (e, a) {

        // Verify whether the event has been raised by JS or by Flash.
        // If event has been raised by JS, we do not need to do
        // anything further.

        if (a && a.source === 'XmlHttpRequest') {
            return;
        }

        // Reference to chartObj.
        var obj = e.sender;

        // Test whether the required functions are available.
        if (obj.ref && typeof obj.ref.dataInvokedOnSWF === 'function' &&
            obj.ref.dataInvokedOnSWF() && typeof obj.ref.getXML === 'function') {

            // Raise a warning for the same.
            global.raiseWarning(obj, '08300116', 'run',
                '::DataHandler~__fusioncharts_vars',
                'Data was set in UTF unsafe manner');

            // Silently update/sync the internal data of FusionCharts JS
            // objects with the new data that was directly sent to flash.
            obj.setChartData(window.unescape(e.sender.ref.getXML({
                escaped: true
            })), FusionChartsDataFormats.XML, true);

            // Update the flashVars as well.
            obj.flashVars.dataXML = obj.getChartData(FusionChartsDataFormats.XML);

            // Since further data communication involves the presence of
            // data-xml in state, we remove the dataURL from flashVars and
            // keep XML fetched from chart.
            delete obj.flashVars.dataURL;
        }

        // Remove ebent handler association so that normal dataLoad is not
        // intercepted.
        e.sender.removeEventListener('DataLoaded', syncDataStore);
    };


    // This code-block exposes a function that assists FusionCharts swf objects
    // to receive updated and accurate dimension information in case of
    // percentage size.
    window.__fusioncharts_dimension = (function () {

        // Regular expression to match whether a right trimmedstring ends with
        // percentage sign or not.
        var isPercentRegex = /.*?\%\s*?$/g;

        // No point documenting this piece of unreadable code! In short, it
        // simply calculates the width of the chart based on the offsetWidth of
        // the container. Same is for height.
        return function (id) {
            var obj, parent;
            return !((obj = global.core(id)) instanceof global.core &&
                obj.ref && (parent = obj.ref.parentNode)) ? {} : {
                width: parent.offsetWidth * (isPercentRegex.test(obj.width) ?
                    parseInt(obj.width, 10) / 100 : 1),
                height: parent.offsetHeight * (isPercentRegex.test(obj.height) ?
                    parseInt(obj.height, 10) / 100 : 1)
            };
        };
    }());

    // Routines to manage state between SWF and FusionCharts JS Object
    window.__fusioncharts_vars = function (id, vars) {

        var obj = global.core.items[id];

        // Verify whether corresponding FusionCharts object exists.
        if (!(obj instanceof global.core)) {
            // Throw error when FusionCharts obj not found.
            global.raiseError(global.core, '25081621', 'run', '::FlashRenderer',
                'FusionCharts Flash object is accessing flashVars of non-existent object.');
            return false;
        }

        // When 'vars' parameter is sent, we sync the local vars object
        if (typeof vars === 'object') {

            // We check whether there was a direct update of data on SWF. In
            // case it is true, we proceed with datastore sync.
            if (obj.ref && typeof obj.ref.dataInvokedOnSWF === 'function' &&
                obj.ref.dataInvokedOnSWF()) {

                // In case dataURL has been updated, we probe into an event that
                // updates local dataStore
                if (vars.dataURL !== undefined) {
                    obj.addEventListener('DataLoaded', syncDataStore);
                }
                // Specifically unescape the dataXML updated
                else if (vars.dataXML !== undefined) {
                    vars.dataXML = window.unescape(vars.dataXML);
                }
            }
            else {
                // Clear data related variables to prevent sync with local
                // store.
                delete vars.dataURL;
                delete vars.dataXML;
            }

            // If corresponding FusionCharts object is found, we update the
            // vars.
            global.extend(obj.flashVars, vars);

            return true;
        }

        return obj.flashVars;
    };

    // Adjust flash dimension based upon flashVars
    global.addEventListener('BeforeInitialize', function (event) {
        // Get short reference to the event sender.
        var obj = event.sender;

        // Filter objects that are generated by flash renderer only.
        if (obj.options.renderer !== 'flash') {
            return;
        }

        // Prevention is better than cure. So is precaution!
        if (obj.width === undefined) {
            obj.width = global.renderer.policies.flashVars.chartWidth[1];
        }
        if (obj.height === undefined) {
            obj.height = global.renderer.policies.flashVars.chartHeight[1];
        }
        if (obj.flashVars.DOMId === undefined) {
            obj.flashVars.DOMId = obj.id;
        }

        // Default flashVars that are to be set.
        global.extend(obj.flashVars, {
            registerWithJS: '1',
            chartWidth: obj.width,
            chartHeight: obj.height,
            InvalidXMLText: 'Invalid data.'
        });
 
        // AutoInstallRedirect action routine.
        if (Boolean(obj.options.autoInstallRedirect) === true &&
            !global.swfobject.hasFlashPlayerVersion(
                global.core.options.requiredFlashPlayerVersion.toString()) &&
            noFlashRedirectNotified === false) {
            noFlashRedirectNotified = true;
            if (window.confirm(global.core.options.installRedirectMessage)) {
                window.location.href = 'http://get.adobe.com/flashplayer/';
            }
        }

        // Set initial state of charts
        if (obj.options.dataFormat === undefined && obj.options.dataSource === undefined) {
            obj.options.dataFormat = FusionChartsDataFormats.XMLURL;
            obj.options.dataSource = 'Data.xml';
        }
    });

    
    // Add method to make sure to delete all fusioncharts objects when
    // dispose method is invoked
    global.addEventListener('Disposed', function (e) {
        // Process these events only for objects that have flash renderer!
        if (e.sender.options.renderer !== 'flash') {
            return;
        }
        // Managed removal of chart using swfObject library
        global.swfobject.removeSWF(e.sender.id);
    });

    global.addEventListener('Loaded', function (e) {
        // Process these events only for objects that have flash renderer!
        if (e.sender.options.renderer !== 'flash') {
            return;
        }

        // Upon render completion, disable animation of the chart by updating the
        // 'animation' flag in flashVars. This fixes the issue of FusionCharts
        // loosing its state when it is re-rendered after being hidden.
        // Set disable animation flag for state management.
        e.sender.flashVars.animation = '0';
    });

    global.addEventListener('DataLoadRequested', function (event, args) {
        // Reference to event sender.
        var obj = event.sender;

        // Process these events only for objects that have flash renderer!
        if (obj.options.renderer !== 'flash') {
            return;
        }
        if (window.location.protocol === 'file:' && args.dataFormat === 
            FusionChartsDataFormats.XML && Boolean(obj.options.safeMode) === true) {
            if (obj.ref && obj.ref.setDataURL) {
                obj.ref.setDataURL(args.url, false);
            }
            else {
                obj.flashVars.dataURL = args.url;
            }
            // Stop further activities on this event.
            event.stopPropagation();
            
            // Cancel the AJAX data-load request.
            args.cancelDataLoadRequest();
            obj.addEventListener('DataLoaded', syncDataStore);
        }

        // In case we have an active chart, we show the loading
        // message in chart itself.
        if (obj.ref && typeof obj.showChartMessage === 'function') {
            delete obj.flashVars.stallLoad;
            obj.ref.showChartMessage('XMLLoadingText');
        }
        // In case chart object is not available, we set a flashvar saying that
        // loading is to be stalled.
        else {
            obj.flashVars.stallLoad = true;
        }
    });

    global.addEventListener('DataLoadRequestCancelled', function (event) {
        // Reference to event sender.
        var obj = event.sender;

        // Process these events only for objects that have flash renderer!
        if (obj.options.renderer !== 'flash') {
            return;
        }

        // In case we have an active chart, we hide the loading message in chart
        // itself.
        if (obj.ref && typeof obj.showChartMessage === 'function') {
            obj.ref.showChartMessage();
        }
        // In case chart object is not available, we set a flashvar saying that
        // loading is to be stalled.
        delete obj.flashVars.stallLoad;
    });

    global.addEventListener('DataLoadError', function (event, args) {
        // Reference to event sender.
        var obj = event.sender;

        // Process these events only for objects that have flash renderer!
        if (obj.options.renderer !== 'flash') {
            return;
        }

        // On data load error, one needs to display "No Data To Display" on
        // charts.
        if (obj.ref && typeof obj.ref.showChartMessage === 'function' &&
            args.source === 'XmlHttpRequest') {
            // Show Data Load Error Message.
            obj.ref.showChartMessage('LoadDataErrorText');
        }
        else {
            delete obj.flashVars.dataURL;// = 'XmlHttpRequestDataLoadError: ' + args.url;
            obj.flashVars.dataXML = '<JSON parsing error>';
            delete obj.flashVars.stallLoad;
        }

    });

    global.addEventListener('DataLoadRequestCompleted', function (event, args) {
        // Reference to event sender.
        var obj = event.sender;

        // Process these events only for objects that have flash renderer!
        if (obj.options.renderer !== 'flash' || args.source !== 'XmlHttpRequest') {
            return;
        }

        // Clear load prevention flag.
        delete obj.flashVars.stallLoad;

    });
    

    // Bind EventTarget with the FusionCharts global event handler
    window.__fusioncharts_event = function (event, args) {
        // Create an abstraction layer so that the try-catch / error suppression
        // of flash can be avoided.
        setTimeout(function () {
            global.raiseEvent(event.type, args, global.core.items[event.sender]);
        }, 0);
    };

    var renderer = {
        // Default data format supported by this renderer
        dataFormat: 'xml',
        
        init: function () {

        },
        
        policies: {
            // Add construction policies specific to ActiveX parameters.
            params: {
                scaleMode: ['scaleMode', 'noScale'],
                scale: ['scaleMode', 'noScale'],
                wMode: ['wMode', 'opaque'],
                menu: ['menu', undefined],
                bgColor: ['bgColor', undefined],
                allowScriptAccess: ['allowScriptAccess', 'always'],
                quality: ['quality', 'best'],
                swLiveConnect: ['swLiveConnect', undefined],
                base: ['base', undefined],
                align: ['align', undefined],
                salign: ['sAlign', undefined]
            },
            /**
             * @var vars {object} Contains all the veriables that are local to every
             * renderer. This has a direct implication to the FlashVars of the
             * 'flash' renderer.
             */
            flashVars: {
                lang: ['lang', 'EN'],
                debugMode: ['debugMode', undefined],
                // @note: Deprecated and hardcoded in beforeinitialize event.
                // registerWithJS: ['registerWithJS', '1'],
                animation: ['animate', undefined]
            },

            options: {
                autoInstallRedirect: ['autoInstallRedirect', false]
            }
        },

        render: function (container, callBack) {

            // Remove the 'animation' flag of the chart.
            if (Boolean(this.flashVars.animation) === true) {
                delete this.flashVars.animation;
            }

            // Check for valid 'src' attribute
            if (!this.src) {
                global.raiseError(this, '03102348', 'run', '::FlashRenderer.render',
                    'Could not find a valid "src" attribute. swfUrl or chart ' +
                    'type missing.');
            }

            // Copy the flashVars and encodeURIComponent all of them before
            // sending to swfObject
            var encodedVars = {}, dataXML = this.flashVars.dataXML,
                dataURL = this.flashVars.dataURL;
            global.extend(encodedVars, this.flashVars);

            // For backward compatibility, set the flashVar to have reference
            // to xml or xmlurl in case render is called while the charts are
            // flagged to be stalled.
            if (this.flashVars.stallLoad === true) {
                if (this.options.dataFormat === FusionChartsDataFormats.XML) {
                    dataXML = this.options.dataSource;
                }
                if (this.options.dataFormat === FusionChartsDataFormats.XMLURL) {
                    dataURL = this.options.dataSource;
                }
            }

            // Encode dataXML and dataURL
            encodedVars.dataXML = window.encodeURIComponent(dataXML || '');
            encodedVars.dataURL = urlEncodeIfNecessary(dataURL || '');

            // Call swfobject API to render the chart
            global.swfobject.embedSWF(this.src, this.id, this.width,
                this.height, '8.0.0', undefined, encodedVars, this.params,
                this.attributes, callBack);

        },

        // Listen to the dataUpdated event, so that charts can be re-rendered with
        // new data, when data is updated on JS variable post render
        update: function (vars) {
            // Point to direct SWFObject and also get the latest data
            var chart = this.ref, data = vars.data;

            // When updating states that dataXML has been updated
            // Update FlashVars
            this.flashVars.dataXML = data;

            // Check whether there was an error or not.
            if (vars.error === undefined) {
                // Call ExternalInterface method and update the data
                if (this.isActive() && typeof chart.setDataXML === 'function') {
                    chart.setDataXML(data, false);
                }
                else {
                    // Remove the 'animation' flag of the chart while data was
                    // updated during unavailability of SWF
                    delete this.flashVars.dataURL;
                    delete this.flashVars.animation;
                }
            }
            // Show error message.
            else {
                // Call ExternalInterface method to show message
                if (this.isActive() && typeof chart.showChartMessage === 'function') {
                    chart.showChartMessage('InvalidXMLText');
                }
                else {
                    // Remove the 'animation' flag of the chart while data was
                    // updated during unavailability of SWF
                    this.flashVars.dataXML = '<Invalid' + vars.format.toUpperCase() + '>';
                    delete this.flashVars.dataURL;
                    delete this.flashVars.animation;
                }
            }
        },
        
        // Handle renderer resize.
        resize: function () {
            // Updated flashVars with new dimension
            this.flashVars.chartWidth = this.width;
            this.flashVars.chartHeight = this.height;

            if (this.ref !== undefined) {
                // Set sizes of DOM elements.
                this.ref.width = this.width;
                this.ref.height = this.height;
                if (typeof this.ref.resize === 'function') {
                    // Force resize on charts.
                    this.ref.resize(this.ref.offsetWidth, this.ref.offsetHeight);
                }
            }
        },

        // Send chart configuration
        config: function (items) {
            global.extend(this.flashVars, items);
        }
    };

    // Add flash renderer legacy functions.
    renderer.prototype = {
        // Legacy function
        getSWFHTML: function () {

            // Create temporary elements and temporary access Id
            var outElm = document.createElement('span'),
                inElm = document.createElement('span'),
                tempId = 'RnVzaW9uQ2hhcnRz' + (new Date()).getTime();

            // Create DOM hierarchy
            outElm.appendChild(inElm);
            // Specify temp access Id
            inElm.setAttribute('id', tempId);
            // Hide this element from user.
            outElm.style.display = 'none';
            // Add the element to DOM.
            document.getElementsByTagName('body')[0].appendChild(outElm);

            // Temporarily embed the swf in the above-created element.
            global.swfobject.embedSWF(this.src, tempId, this.width,
                this.height, '8.0.0', undefined, this.flashVars, this.params,
                this.attrs);

            // Get the HTML from the temp objects.
            var html = outElm.innerHTML.replace(tempId, this.id);

            // Clean up the dirty work.
            global.swfobject.removeSWF(tempId);
            outElm.parentNode.removeChild(outElm);

            // Return the HTML with its ID set to normal.
            return html;
        },

        // Add legacy setTransparent function
        setTransparent: function (transparency) {
            // Sets chart to transparent mode when isTransparent (wMode) is true
            // (default). When no parameter is passed, we assume transparent to
            // be true.
            if (typeof transparency !== 'boolean' && transparency !== null) {
                transparency = true;
            }

            // Set the property.
            this.params.wMode = transparency === null ? 'window' :
                (transparency === true ? 'transparent' : 'opaque');
        },

        addVariable: global.core.prototype.configure
    };

    // Add the renderer to FusionCharts core repository
    global.renderer.register('flash', renderer);

    // Check for flash and iphone/ipad
    // Register the swf renderer in case we have flashPlayer.
    if (!/\(iPhone;|\(iPad;/i.test(navigator.userAgent)) {
        // Set default renderer
        global.renderer.setDefault('flash');
    }


}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  plusplus: true, bitwise: true, regexp: true, immed: true */

/*global Array: false, FusionCharts, RegExp: false, jQuery: false, window: false,
  $: true, Highcharts: false, FusionChartsDataFormats: false, FC_Loaded, FC_DataLoaded, FC_Rendered, FC_DrawComplete */

/**
 * -----------------------------------------------------------------------------
 * HTML5 Renderer Module
 * -----------------------------------------------------------------------------
 */
(function () {
    var global, FCC = {}, baseURL = 'JSClass/', covertToFCC, renderArray = [],
    FCCready = false, i, scriptTags, jsConf = {};


    // Register the module with FusionCharts and als oget access to a global
    // variable within the core's scope.
    global = FusionCharts(['private', 'Canvas_Renderer']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    var FCCCREDITSTATE = true;

    //function that will return undefined
    //when any flash proparty has no effect in js chart
    //then add this methode reference for that property
    function noEffect() {
        return undefined;
    }

    
    //initialize Highcharts
    //this is the base function function to initialize javascript charts renderer


    FCC.init = function () {
        var passThroughStyle = function (name, suf, pref) {
                        return function (value, obj) {
                            obj.style[name] = (pref ? pref : '') + value + (suf ? suf : '');
                        };
                    },
        //variable to store various default values
        FCFCC = {
            color: ["AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526", "B3AA00", "008ED6", "9D080D", "A186BE", "CC6600", "FDC689", "ABA000", "F26D7D", "FFF200", "0054A6", "F7941C", "CC3300", "006600", "663300", "6DCFF6"],
            seriesName : {
                'Column2D' : 1,
                'Column3D' : 1,
                'Line' : 1,
                'Area2D' : 1,
                'Bar2D' : 1,
                'Pie2D' : 1,
                'Pie3D' : 1,
                'Doughnut2D' : 1,
                'Doughnut3D' : 1,
                'Pareto2D' : 1.5,
                'Pareto3D' : 1.5,
                'MSColumn2D' : 2,
                'MSColumn3D' : 2,
                'MSLine' : 2,
                'ZoomLine': 2.5,
                'MSBar2D' : 2,
                'MSBar3D' : 2,
                'MSArea' : 2,
                'InverseMSLine' : 2.2,
                'InverseMSColumn2D' : 2.2,
                'InverseMSArea' : 2.2,
                'StackedColumn3D' : 3,
                'Marimekko': 3,
                'StackedColumn2D' : 3,
                'StackedColumn2DLine': 3,
                'StackedColumn3DLine': 3,
                'StackedBar2D' : 3,
                'StackedBar3D' : 3,
                'StackedArea2D' : 3,
                'MSCombi3D' : 4,
                'MSCombi2D' : 4,
                'MSCombiDY2D' : 5,
                'MSColumnLine3D' : 4,
                'MSColumn3DLineDY' : 5,
                'MSStackedColumn2D' : 6,
                'MSStackedColumn2DLineDY' : 6.5,
                'StackedColumn3DLineDY' : 5.5,
                'Scatter' : 7,
                'Bubble' : 7.5,
                'ScrollColumn2D' : 2,
                'ScrollLine2D' : 2,
                'ScrollArea2D' : 2,
                'ScrollStackedColumn2D' : 3,
                'ScrollCombi2D' : 4,
                'ScrollCombiDY2D' : 5,
                'SSGrid' : 9,
                'Spline': 1,
                'SplineArea': 1,
                'MSSpline': 2,
                'MSSplineArea': 2,
                'MultiAxisLine': 8
            },
            highCharts : {
                'Column2D' : 'column',
                'Column3D' : 'column',
                'Line' : 'line',
                'Area2D' : 'area',
                'Bar2D' : 'bar',
                'Pie2D' : 'pie',
                'Pie3D' : 'pie',
                'Doughnut2D' : 'pie',
                'Doughnut3D' : 'pie',
                'Pareto2D' : 'column',
                'Pareto3D' : 'column',
                'MSColumn2D' : 'column',
                'MSColumn3D' : 'column',
                'MSLine' : 'line',
                'ZoomLine': 'line',
                'MSBar2D' : 'bar',
                'MSBar3D' : 'bar',
                'MSArea' : 'area',
                'InverseMSLine' : 'line',
                'InverseMSColumn2D' : 'column',
                'InverseMSArea' : 'area',
                'StackedColumn3D' : 'column',
                'StackedColumn2D' : 'column',
                'Marimekko': 'column',
                'StackedColumn2DLine': 'column',
                'StackedColumn3DLine': 'column',
                'StackedBar2D' : 'bar',
                'StackedBar3D' : 'bar',
                'StackedArea2D' : 'area',
                'MSCombi3D' : 'column',
                'MSCombi2D' : 'column',
                'MSCombiDY2D' : 'column',
                'MSColumnLine3D' : 'column',
                'MSColumn3DLineDY' : 'column',
                'MSStackedColumn2D' : '',
                'MSStackedColumn2DLineDY' : '',
                'StackedColumn3DLineDY' : 'column',
                'Scatter' : 'scatter',
                'Bubble' : 'scatter',
                'ScrollColumn2D' : 'column',
                'ScrollLine2D' : 'line',
                'ScrollArea2D' : 'area',
                'ScrollStackedColumn2D' : 'column',
                'ScrollCombi2D' : 'column',
                'ScrollCombiDY2D' : 'column',
                'SSGrid' : '',
                'Spline': 'spline',
                'SplineArea': 'areaspline',
                'MSSpline': 'spline',
                'MSSplineArea': 'areaspline',
                'MultiAxisLine': ''
            },
            combi : {
                'column2d' : 'column',
                'column3d' : 'column',
                'line' : 'line',
                'area' : 'area'
            },
            valueAbs: {
                'Pie2D' : true,
                'Pie3D' : true,
                'Doughnut2D' : true,
                'Doughnut3D' : true,
                'Marimekko' : true
            },
            exportFormat: {
                png: 'image/png',
                jpg: 'image/jpeg',
                pdf: 'application/pdf',
                svg: 'image/svg+xml'
            },
            JSONconf: {
                blankChart: {
                    chart: {
                        events: {},
                        margin: [0, 0, 0, 0]
                    },
                    credits: {
                        href: 'http://www.fusioncharts.com?BS=FCHSEvalMark',
                        text: 'FusionCharts - HighCharts',
                        enabled: FCCCREDITSTATE
                    },
                    legend: {
                        enabled: false
                    },
                    title: {
                        text: 'No data to display',  //default caption nodata to display
                        style: {
                            fontFamily: 'Verdana',
                            fontSize:  '10px',
                            color: '#666666'
                        }
                    },
                    plotOptions: {
                        series: {}
                    },
                    exporting: {
                        enabled: false
                    }
                }
            },
            commonMethodCSV: 'saveAsImage,print,exportChart,getXML,getChartAttribute,getDataAsCSV,hasRendered,signature,cancelExport',
            methodCSV: {
                'pie3d': ',togglePieSlice',
                'pie2d': ',togglePieSlice',
                'doughnut2D': ',togglePieSlice',
                'doughnut3D': ',togglePieSlice',
                'mscombi3d': ',view2D,view3D,resetView,rotateView,getViewAngles,fitToStage',
                'zoomline': ',zoomTo,setZoomMode,zoomOut,resetChart'
            },

            method: {
                getExternalInterfaceMethods: function (chartType) {
                    return FCFCC.commonMethodCSV +
                    (FCFCC.methodCSV[chartType || this.FusionCharts.chartType()] || '');
                },

                //print the chart
                print: function () {
                    var id = this.id;
                    FCC.items[id].FCCObj.FCC.print();
                },
                exportChart: function (conf) {//xeport the chart
                    var id = this.id, HCConf = {}, HCObj = FCC.items[id].FCCObj.FCC;
                    if (HCObj.options.exporting.enabled) {//check whether exporting is enabled or not
                        if (typeof conf === 'object') {
                            for (var x in conf) {
                                if (x.toLowerCase() === 'exportformat' && FCFCC.exportFormat[conf[x].toLowerCase()]) {
                                    HCConf.type = FCFCC.exportFormat[conf[x].toLowerCase()];
                                } else if (x.toLowerCase() === 'exportfilename') {
                                    HCConf.filename = conf[x];
                                }
                            }
                        }
                        HCObj.exportChart(HCConf);
                    }
                },
                getXML: function () {
                    var id = this.id;
                    return global.core.items[id].getXMLData();
                },
                //function to chech the signature /vertion
                signature : function () {
                    return 'FusionCharts/3.2.0/JS';
                },
                hasRendered: function () {
                    var id = this.id;
                    if (typeof FCC.items[id] === 'object') {
                        return true;
                    } else {
                        return false;
                    }
                },
                togglePieSlice: function (index) {
                    if (this.ref && this.ref.FCC && this.ref.FCC.series &&
                        this.ref.FCC.series[0] && this.ref.FCC.series[0].data &&
                        this.ref.FCC.series[0].data[index] &&
                        this.ref.FCC.series[0].data[index].slice()) {
                        //call the function
                        this.ref.FCC.series[0].data[index].slice();
                    }
                }
            },
            
            supportedStyle: {
                font: function (HC, toObj, style) {
                    var styleobject, x, map = {
                        font: passThroughStyle('font-family'),
                        size: passThroughStyle('font-size', 'px'),
                        color: passThroughStyle('color', undefined, '#'),
                        align: function (value, obj) {
                            obj.align = value;
                        },
                        bgColor: passThroughStyle('background-color', undefined, '#'),
                        borderColor: passThroughStyle('border-color', undefined, '#'),
                        isHTML: '',
                        leftMargin: passThroughStyle('margin-left', 'px'),
                        letterSpacing: passThroughStyle('letter-spacing', 'px'),
                        bold: function (value, obj) {
                            obj['font-weight'] = value == '1' ? 'bold' : 'normal';
                        },
                        italic: function (value, obj) {
                            obj['font-style'] = value == '1' ? 'italic' : 'normal';
                        },
                        underline: function (value, obj) {
                            obj['text-decoration'] = value == '1' ? 'underline' : 'normal';
                        }
                    };
                     
                    switch (toObj) {
                    case 'caption':
                        styleobject = HC.title;
                        break;

                    case 'datalabels':
                        styleobject = HC.xAxis.labels;
                        break;

                    case 'datavalues':
                        styleobject = HC.plotOptions.series.dataLabels;
                        break;

                    case 'subcaption':
                        styleobject = HC.subtitle;
                        break;

                    case 'tooltip':
                        styleobject = HC.tooltip;
                        break;

                    case 'trendvalues':
                        styleobject = undefined;
                        break;

                    case 'xaxisname':
                        styleobject = HC.xAxis.title;
                        break;

                    case 'yaxisname':
                        styleobject = HC.yAxis[0].title;
                        break;
                    case 'yaxisvalues':
                        styleobject = HC.yAxis[0].labels;
                        break;

                    default:
                        break;
                    }

                    if (typeof styleobject === 'object') {
                        for (x in style) {
                            if (typeof map[x] === 'function') {
                                map[x](style[x], styleobject);
                            }
                        }
                    }
                }
            }
        };

        var chartMessageStore = {};
        
        //clone an object's propaties in to another and marged
        function margeClone(obj1, obj2) {
            var item;
            if (typeof obj1 !== 'object' && typeof obj2 !== 'object') {//if none of the arguments are object then return back
                return null;
            }
            if (typeof obj1 !== 'object') {
                obj1 = {};
            }
            if (typeof obj2 !== 'object') {
                obj2 = obj1;
                obj1 = {};
            }
            //check whether obj2 is an array
            //if array then iterate through it's index
            //**** MOOTOOLS precution
            if (obj2 instanceof Array) {
                for (item = 0; item < obj2.length; item += 1) {
                    if (typeof obj2[item] !== 'object') {
                        obj1[item] = obj2[item];
                    } else {
                        obj1[item] = margeClone(obj1[item], obj2[item]);
                    }
                }
            }
            else {
                for (item in obj2) {
                    if (typeof obj2[item] !== 'object') {
                        obj1[item] = obj2[item];
                    } else {
                        obj1[item] = margeClone(obj1[item], obj2[item]);
                    }
                }
            }
            
            return obj1;
        }



        //Add the prototype for the FCC renderer
        //this contain all the methods which flash renderer add to the instance

        FCC.prototype = {
            
            // Add legacy setTransparent function

            setTransparent: function (transparency) {

                if (!this.jsVars) {
                    this.jsVars = {};
                }

                // Sets chart to transparent mode when isTransparent (wMode) is true
                // (default). When no parameter is passed, we assume transparent to
                // be true.
                if (typeof transparency !== 'boolean' && transparency !== null) {
                    transparency = true;
                }
                //set the tranparency flag
                this.jsVars.transparent = transparency;
                //using jquery set the background color of the container div
                if (typeof jQuery === 'function') {
                    jQuery('#' + this.id).css('background-color', (transparency === true) ? '' : '#FFFFFF');
                }

            },
            getSWFHTML: noEffect, //flash specific method
            
            //JS chart's function to pass a config. for js charts only
            _overrideJSChartConfiguration: function (conf) {
                //save the configuration of the chhart
                //which will be retrive during the chart creation
                jsConf[this.id] = conf;
            }

        };




        //set the baseURL
        //if user defined then use it
        if (global.core.options && global.core.options.scriptBaseUri !== undefined) {
            baseURL = global.core.options.scriptBaseUri;
        } else {//else use the baseURL FusionCharts.js
            scriptTags = document.getElementsByTagName('script');
            //iterate through all the script tag to fiend the path of the FC.JS file
            if (scriptTags) {
                for (i = 0; i < scriptTags.length; i += 1) {
                    if (scriptTags[i].src.indexOf('FusionCharts.js') !== -1) {
                        baseURL = scriptTags[i].src.split("FusionCharts.js")[0];
                    }
                }
            }
        }


        //Tha function to check whether the FCC is reday or not
        FCC.isReady = function () {
            return FCCready;
        };


        ///container for all FCC object with the base object[after convertion]
        // & chart object[FC ref.]
        FCC.items = {};



        //function  that will add a script tag in head
        function loadScript(src) {
            //create new script element
            //set the src
            //append in head
            var newele = document.createElement('script');
            newele.setAttribute('type', 'text/javascript');
            newele.setAttribute('src', src);
            var head = document.getElementsByTagName('head');
            head[0].appendChild(newele);
        }

        //function that will chaek whether FCC is ready or not

        function checkFCCReady() {
            var temp;
            if (typeof window.Highcharts === 'object') {
                FCCready = true;
                //call all the stacked render actions
                if (typeof FCC.render == 'function') {
                    while (renderArray.length > 0) {
                        temp = renderArray.splice(0, 1)[0];
                        FCC.render.call(temp[0], temp[1], temp[2]);
                    }
                }

            //call FCC onready function
            } else {
                setTimeout(checkFCCReady, 300);
            }
        }

        //load HIGFCCHARTS
        function loadFCC() {
            if (typeof jQuery === 'function') {
                jQuery.noConflict();
                //check whether there no one is using $ then please revert $ to it
                if ($ === undefined) {
                    $ = jQuery;
                }
                
                if (typeof window.Highcharts !== 'object') {
                    loadScript(baseURL + 'highcharts.js');
                    setTimeout(checkFCCReady, 300);
                } else {
                    checkFCCReady();
                }
            } else {
                setTimeout(loadFCC, 300);
            }
        }



        //load jquery
        if (typeof jQuery !== 'function') {
            loadScript(baseURL + 'jquery.min.js');
            setTimeout(loadFCC, 300);
        } else {
            loadFCC();
        }




        ////////// List of tools(functions) needed//////////

        //calculat axis max/min

        var getAxisLimits  = function (maxValue, minValue, yAxisMaxValue, yAxisMinValue, stopMaxAtZero, setMinAsZero) {

                var maxPowerOfTen, minPowerOfTen, powerOfTen, y_interval, rangePowerOfTen, rangeInterval, y_topBound, y_lowerBound, yMaxGiven, yMinGiven, yMax, yMin, range, interval;

                //First check if both maxValue and minValue are proper numbers.
                //Else, set defaults as 90,0
                maxValue = (typeof maxValue !== 'number') ? 0.1 : maxValue;
                minValue = (typeof minValue  !== 'number') ? 0 : minValue;
                //maxValue = (isNaN (maxValue) == true || maxValue == undefined) ? 0.1 : maxValue;
                //minValue = (isNaN (minValue) == true || minValue == undefined) ? 0 : minValue;


                //Or, if only 0 data is supplied
                if ((maxValue === minValue) && (maxValue === 0)) {
                    maxValue = 0.1;
                }

                //Defaults for stopMaxAtZero and setMinAsZero
                //stopMaxAtZero = getFirstValue (stopMaxAtZero, false);
                //setMinAsZero = getFirstValue (setMinAsZero, true);
                if (typeof stopMaxAtZero === 'undefined' || typeof stopMaxAtZero === 'null' || stopMaxAtZero === '') {
                    stopMaxAtZero = false;
                }
                if (typeof setMinAsZero === 'undefined' || typeof setMinAsZero === 'null' || setMinAsZero === '') {
                    setMinAsZero = true;
                }

                //Get the maximum power of 10 that is applicable to maxvalue
                //The Number = 10 to the power maxPowerOfTen + x (where x is another number)
                //For e.g., in 99 the maxPowerOfTen will be 1 = 10^1 + 89
                //And for 102, it will be 2 = 10^2 + 2
                maxPowerOfTen = Math.floor(Math.log(Math.abs(maxValue)) / Math.LN10);
                //Get the minimum power of 10 that is applicable to maxvalue
                minPowerOfTen = Math.floor(Math.log(Math.abs(minValue)) / Math.LN10);

                //Find which powerOfTen (the max power or the min power) is bigger
                //It is this which will be multiplied to get the y-interval
                powerOfTen = Math.max(minPowerOfTen, maxPowerOfTen);
                y_interval = Math.pow(10, powerOfTen);

                //For accomodating smaller range values (so that scale doesn't represent too large an interval
                if (Math.abs(maxValue) / y_interval < 2 && Math.abs(minValue) / y_interval < 2) {
                    powerOfTen -= 1;
                    y_interval = Math.pow(10, powerOfTen);
                }

                //If the y_interval of min and max is way more than that of range.
                //We need to reset the y-interval as per range
                rangePowerOfTen = Math.floor(Math.log(maxValue - minValue) / Math.LN10);
                rangeInterval = Math.pow(10, rangePowerOfTen);
                //Now, if rangeInterval is 10 times less than y_interval, we need to re-set
                //the limits, as the range is too less to adjust the axis for max,min.
                //We do this only if range is greater than 0 (in case of 1 data on chart).
                if (((maxValue - minValue) > 0) && ((y_interval / rangeInterval) >= 10)) {
                    y_interval = rangeInterval;
                    powerOfTen = rangePowerOfTen;
                }
                //Calculate the y-axis upper limit
                y_topBound = (Math.floor(maxValue / y_interval) + 1) * y_interval;
                //Calculate the y-axis lower limit
                //y_lowerBound
                //If the min value is less than 0
                if (minValue < 0) {
                    //Then calculate by multiplying negative numbers with y-axis interval
                    y_lowerBound = - 1 * ((Math.floor(Math.abs(minValue / y_interval)) + 1) * y_interval);
                } else {
                    //Else, simply set it to 0.
                    if (setMinAsZero) {
                        y_lowerBound = 0;
                    } else {
                        y_lowerBound = Math.floor(Math.abs(minValue / y_interval) - 1) * y_interval;
                        //Now, if minValue>=0, we keep x_lowerBound to 0 - as for values like minValue 2
                        //lower bound goes negative, which is not required.
                        y_lowerBound = (y_lowerBound < 0) ? 0 : y_lowerBound;

                    }
                }
                //MaxValue cannot be less than 0 if stopMaxAtZero is set to true
                if (stopMaxAtZero && maxValue <= 0) {
                    y_topBound = 0;
                }

                //Now, we need to make a check as to whether the user has provided an upper limit
                //and lower limit.
                if (yAxisMaxValue === null || yAxisMaxValue === undefined || yAxisMaxValue === "") {
                    yMaxGiven = false;
                } else {
                    yMaxGiven = true;
                }
                if (yAxisMinValue === null || yAxisMinValue === undefined || yAxisMinValue === "" || typeof Number(yAxisMinValue) === 'NaN') {
                    yMinGiven = false;
                } else {
                    yMinGiven = true;
                }
                //If he has provided it and it is valid, we leave it as the upper limit
                //Else, we enforced the value calculate by us as the upper limit.
                if (yMaxGiven === false || (yMaxGiven === true && Number(yAxisMaxValue) < maxValue)) {
                    yMax = y_topBound;
                } else {
                    yMax = Number(yAxisMaxValue);
                }
                //Now, we do the same for y-axis lower limit
                if (yMinGiven === false || (yMinGiven === true && Number(yAxisMinValue) > minValue)) {
                    yMin = y_lowerBound;
                } else {
                    yMin = Number(yAxisMinValue);
                }
                //Store axis range
                range = Math.abs(yMax - yMin);
                //Store interval
                interval = y_interval;

                return {Max: yMax, Min: yMin, Range: range, interval: interval};
            };




        //function to create thecontainer element for chart
        function createContainer(contanerId, chartId, width, height, cleanCopy) {
            //get the container
            var container = document.getElementById(chartId), __container = document.getElementById(contanerId),
            /**
             * @var {RegExp} lengthPercentageRegExp determines whether a string has
             * trailing percentage character or not. Ignoring white-spaces.
             */
            lengthPercentageRegExp = /\%\s*?$/ig;
            ////
            //check whether there needs an clean div
            if (cleanCopy === true) {
                ///delete the span created by FC.js
                if (container) {
                    __container.removeChild(container);
                }
                //create a div as per chart id
                // set the height and width and relative position for the back button
                //set the background white for transparency 0
                container = document.createElement('div');
                container.setAttribute('id', chartId);
                __container.appendChild(container);
            }
            //add all attributes
            for (var x in this.attributes) {
                container[x] = this.attributes[x];
            }
            //add the className fix for IE
            if (this.attributes['class']) {
                container.className = this.attributes['class'];
            }
            container.setAttribute('style', 'display: inline-block; zoom: 1; *display: inline;');
            ////check whether width and hight are string
            //otherwise don't math the reg just add px
            container.style.width = width + (width.match(lengthPercentageRegExp) ? '' : 'px');
            container.style.height = height + (height.match(lengthPercentageRegExp) ? '' : 'px');
        }

        //function that will remove a chart
        function removeChart(id, deleteDom) {
            //destroy the HC obj from HC methode
            if (FCC.items[id]) {
                if (FCC.items[id].FCCObj && FCC.items[id].FCCObj.FCC) {
                    FCC.items[id].FCCObj.FCC.destroy();
                }
                //cleare the mimer for resize chart event
                clearTimeout(FCC.items[id].timeChach);
               
                //remove the div element if requred
                if (deleteDom === true) {
                    // @var refObj {HTMLNode} Create reference to the chart object.
                    var refObj = FCC.items[id].FCCObj;

                    // Check whether HTML Node reference exist or not. If it exists,
                    // climb up to its parent node in DOM and delete the element.
                    if (refObj && refObj.parentNode) {
                        refObj.parentNode.removeChild(refObj);
                    }
                }
                //delete the HC obj from local store
                delete FCC.items[id];
            }
        }

        ///function to create bank serease
        function createSeries(FCtype, color, type, name, yAxis, showvalue, linecolor, linethickness, marker) {
            return {
                data: [],
                FCtype: FCtype,
                color: color,
                type: type,
                name: name ? name: ' ',
                yAxis: yAxis ? 1: undefined,
                showvalue: showvalue,
                lineColor: linecolor,
                lineWidth : linethickness,
                marker: marker
            };
        }

        //parse a string as per configuration
        function parseStr(str) {
            if (typeof str === 'string') {
                return str.replace(/\{br\}/ig, '<br/>');
            }
            else {
                return '';
            }
        }
        /////function that will create a hc object to show a message
        function createMsg(id, msgTxt, event) {
            //coppy the blank chart object
            var HCJson = FCFCC.JSONconf.blankChart;
            //set the title y pos. for the chart so that the msg get into the cender
            HCJson.title.y = parseInt(document.getElementById(id).offsetHeight, 10) / 2;
            //set the renderTo id
            HCJson.chart.renderTo = id;
            ////set the chart message
            HCJson.title.text = chartMessageStore[id][msgTxt] ? chartMessageStore[id][msgTxt] : msgTxt;
            //raise the nodata event
            if (typeof event === 'string') {
                global.raiseEvent(event, {}, global.core.items[id]);
            }
            return HCJson;
        }


        //function that will show a message in achart
        function showChartMessage(msg) {
            var id = this.id, HCJson, FC = global.core.items[id];
            //check wheteher the container exist
            //==> false if a linked chart is opened
            if (FC.ref) {
                //save the chart message
                chartMessageStore[id].msgTxt = msg;
                //destroy the old HC chart
                FC.ref.FCC.destroy();
                //create new HC json to show the chart
                HCJson = createMsg(id, 'msgTxt', undefined);
                
                //create the ref of new high charts
                FC.ref.FCC = new Highcharts.Chart(HCJson);
                
                //save the chart's various objects in local store
                FCC.items[id].baseObj =  HCJson;
            }
            
            if (FC.link.root === FC) {//check whether it is not an linked chart
                return;
            }
            //if linked chart show the back button
            var config = global.extend({
                show: true
            }, this.FusionCharts.link.parent.options.overlayButton);
            global.extend(config, this.FusionCharts.link.parent.link.configuration().overlayButton || {});

            this.drawOverlayButton(config);
        }

        //function that will draw a overlay button
        function drawOverlayButton(conf) {
            if (conf.show) {//if have to show the overlay button
                //createw a span for back button
                //add the text
                //set border color onclick function etc.
                var back = document.createElement('span');
                back.innerHTML = conf.message ? conf.message : "Back";
                back.style.border = '1px solid #' + (conf.borderColor ? conf.borderColor : "7f8975");
                back.style.backgroundColor = '#' + (conf.bgColor ? conf.bgColor : "edefec");
                back.style.fontFamily = conf.font ? conf.font : "Verdana";
                back.style.color = '#' + conf.fontColor ? conf.fontColor:"49563a";
                back.style.fontSize = (conf.fontSize ? conf.fontSize : '10') + 'px';
                back.style.padding = (conf.padding ? conf.padding : '3') + 'px';
                back.style.fontWeight = parseInt(conf.bold, 10) === 0 ? 'normal' : 'bold';
                back.style.position = 'absolute';
                back.style.top = '1px';
                back.style.right = '1px';
                back.style._cursor = 'hand';
                back.style.cursor = 'pointer';
                back.onclick = (function (sender) {
                    return function () {
                        global.raiseEvent('OverlayButtonClick', {}, sender.FusionCharts);
                    };
                }(this));
                document.getElementById(this.id).childNodes[0].appendChild(back);
            }
        }


        //this functon will map chart series type with its name  and return it
        function getSeriesName(chartName) {
            return FCFCC.seriesName[chartName];
        }

        //reduce gradent color take only first color
        //converts color to compatable color format
        //**** rgba must be rgbs(r,g,b,a) format
        function convertColor(color, alpha, rgba) {
            var R = 0, G = 0, B = 0, colorStr, tempArr;
            
            
            if (rgba && rgba.match(/^rgba/ig)) {
                tempArr = rgba.split(',');
                R = tempArr[0].slice(tempArr[3].indexOf('(') + 1);
                G = tempArr[1];
                B = tempArr[2];
                if (!alpha) {
                    alpha = parseInt(tempArr[3].slice(0, tempArr[3].indexOf(')')), 10) * 100;
                }
            }
            if (color) {
                colorStr = color.replace(/[#\s]/ig, '').split(',')[0];
                switch (colorStr.length) {
                case 3:
                    colorStr = colorStr[0] + colorStr[0] + colorStr[1] + colorStr[1] + colorStr[2] + colorStr[2];
                    break;
                case 6:
                    break;
                default:
                    colorStr = (colorStr + 'FFFFFF').slice(0, 6);
                    break;
                }
                R = parseInt(colorStr.slice(0, 2), 16);
                G = parseInt(colorStr.slice(2, 4), 16);
                B = parseInt(colorStr.slice(4, 6), 16);
            }
            
            if (!alpha) {
                alpha = 100;
            }
            if (typeof alpha === 'string') {
                alpha = alpha.split(',')[0];
            }
            alpha = parseInt(alpha, 10) / 100;
            return 'rgba(' + R + ',' + G + ',' + B + ',' + alpha + ')';
        }


        ///get The Gradient Point
        var getCordinate = function (x, y, width, height, angle) {
            x = x ? x : 0;
            y = y ? y : 0;
            width = (typeof width !== 'undefined') ? width : 400;
            height = (typeof height !== 'undefined') ? height : 400;
            angle = (typeof angle !== 'undefined') ? angle : 0;

            var x1, x2, y1, y2, tan, temp;
            
            tan = Math.tan((angle * Math.PI) / 180);
            y1 = Math.round(height / 2 - ((width / 2) * tan));
            x1 = Math.round(width / 2 - ((height / 2) / tan));
            x1 = (x1 < 0) ? 0 : x1;
            x1 = (x1 > width) ? width : x1;
            y1 = (y1 < 0) ? 0 : y1;
            y1 = (y1 > height) ? height : y1;
            x2 = width - x1;
            y2 = height - y1;
            if (angle > 90 && angle <= 270) {
                temp = y1;
                y1 = y2;
                y2 = temp;
            }
            if (angle > 180 && angle <= 360) {
                temp = x1;
                x1 = x2;
                x2 = temp;
            }
            return [x1 + x, y1 + y, x2 + x, y2 + y];
        };


        ///Create The Color Stop
        var fillGcolorPoint = function (bgColor, bgAlpha, bgRatio) {
            var x, first, count = 0, blendAt, rgb, alpha, colorStop = [];
            bgAlpha = bgAlpha ? bgAlpha.split(",") : undefined;
            bgRatio = bgRatio ? bgRatio.split(",") : undefined;
            bgColor = bgColor.split(",");

            first = (typeof bgRatio === 'object') ? bgRatio[0] : undefined;

            for (x = 0; x < bgColor.length; x += 1) {
                rgb = bgColor[x];
                blendAt = bgRatio ? bgRatio[x] : undefined;
                alpha = bgAlpha ? bgAlpha[x] :100;
                alpha = alpha ? alpha : 100;

                if (!blendAt) {//blendAt is undefined
                    first = 1;
                    blendAt = (x !== 0) ? ((100 - count) / (bgColor.length - x)) : 0;
                }
                if (first) {
                    count += parseInt(blendAt, 10);
                }
                else {
                    count = parseInt(blendAt, 10);
                }
                if (count >= 100) {
                    colorStop.push([1, convertColor(rgb, alpha)]);
                    break;
                }
                else {
                    colorStop.push([count / 100, convertColor(rgb, alpha)]);
                }
            }
            return colorStop;
        };


        ////////Main Function To Create Color
        var CreateColor = function (x, y, width, height, angle, bgColor, bgAlpha, bgRatio) {
            var colorObj = {};

            ///Fiend The Gradient Point
            colorObj.linearGradient = getCordinate(x, y, width, height, angle);
            //set the coloe stops
            colorObj.stops = fillGcolorPoint(bgColor, bgAlpha, bgRatio);
            if ('\v' === 'v') {
                if (2 * colorObj.linearGradient[0] > width) {
                    colorObj.stops.reverse();
                    for (var i = 0; i < colorObj.stops.length; i += 1) {
                        colorObj.stops[i][0] = 1 - colorObj.stops[i][0];
                    }
                }
                //set 1 stop for IE if not exist
                if (colorObj.stops[colorObj.stops.length - 1][0] !== 1) {
                    colorObj.stops.push([1, colorObj.stops[colorObj.stops.length - 1][1]]);
                }
                //set 0 stop for IE if not exist
                if (colorObj.stops[0][0] !== 0) {
                    colorObj.stops.splice(0, 0, [0, colorObj.stops[0][1]]);
                }
            }
            return colorObj;
        };

        //this function will map FC name to FCC name and return the corosponding FCC name
        var convertNames = function (chartName) {
            return FCFCC.highCharts[chartName];
        };


        //this function will datsa to parcentage of total for pareto line
        var paretoConvert = function (data) {
            var x, psum = 0, pareto = {
                yAxis: 1,
                data: [],
                type: 'line',
                color: 'rgba(00,00,00,1)',
                FCtype: 1.5
            }, sum = 0;
            if (data instanceof Array) {
                for (x = 0; x < data.length; x += 1) {
                    sum += data[x].y;
                }
                for (x = 0; x < data.length; x += 1) {
                    psum += data[x].y;
                    pareto.data.push({
                        y : Math.round((psum / sum) * 10000) / 100
                    });
                }
            }
            return pareto;
        };

        //return the name of the chart swf file from it's src'
        //e.g. charts/Column2D.swf will return Column2D

        var convertSWFtoAlias = function (swf) {
            var alias = swf.substring(swf.indexOf('.swf'), 0);
            return alias.substring(alias.lastIndexOf('/') + 1);
        };


        var formatNumber = function (num, obj, type) {
            // If type is undefined initialize type with 1
            type = type === undefined ? 1 : type;
            /* if (typeof num == 'string') {
                num = parseFloat(num);
            }*/

            // Defines FusionCharts default values and attributes
            var chart = {
                formatnumber: 1,
                formatnumberscale: 1,
                defaultnumberscale: '',
                numberscaleunit: 'K,M',
                numberscalevalue: '1000,1000',
                numberprefix: "",
                numbersuffix: "",
                decimalseparator: ".",
                thousandseparator: ",",
                indecimalseparator: "",
                inthousandseparator: "",
                decimals: "",
                forcedecimals: "0",
                yaxisvaluedecimals: "",
                sformatnumber: "1",
                sformatnumberscale: "0",
                sdefaultnumberscale: "",
                snumberscaleunit: "K,M",
                snumberscalevalue: "1000,1000",
                snumberprefix: "",
                snumbersuffix: "",
                sdecimals: "",
                sforcedecimals: "0",
                syaxisvaluedecimals: "0"
            };

            var numberScaleValue, numberScaleUnit, result, i, res, power, 
                formatedNum = '', c = 0, initialVal, decimalVal, splitVal,
                decimalLength, last;

            // Replace default attributes with new attributes
            for (i in obj) {
                chart[i.toLowerCase()] = obj[i];
            }
          

            // Replace the indecimalseparator value with decimal to perform numeric operations
            num = chart.indecimalseparator !== '' ? 
                num.toString().replace(chart.indecimalseparator, '.') : num;

            // Replace the inthousandseparator value with decimal to perform numeric operations
            num = chart.inthousandseparator !== '' ? 
                num.toString().replace(chart.inthousandseparator, '') : num;

            if (type !== 1) {
                chart.formatnumber = chart.sformatnumber;
                chart.formatnumberscale = chart.sformatnumberscale;
                chart.defaultnumberscale = chart.sdefaultnumberscale;
                chart.numberscaleunit = chart.snumberscaleunit;
                chart.numberscalevalue = chart.snumberscalevalue;
                chart.numberprefix = chart.snumberprefix;
                chart.numbersuffix = chart.snumbersuffix;
                chart.decimals = chart.sdecimals;
                chart.forcedecimals = chart.sforcedecimals;
                chart.yaxisvaluedecimals = chart.syaxisvaluedecimals;
            }

            // Creates array of numberScaleValue and numberScaleUnit to format the number
            numberScaleValue = chart.numberscalevalue.split(',');
            numberScaleUnit = chart.numberscaleunit.split(',');

            result = res = num.toString();

            // format the number with,
            if (chart.formatnumber == 1) {
                if (res.indexOf('.') !== -1) {
                    splitVal = res.split('.');
                    initialVal = splitVal[0];
                    decimalVal = '.' + splitVal[1];
                }
                else {
                    initialVal = res;
                    decimalVal = '';
                }
                for (i = initialVal.length; i > 0; i -= 1) {
                    if (c % 3 === 0 && c !== 0) {
                        formatedNum = chart.thousandseparator + formatedNum;
                        c = 0;
                
                    }
                    //formatedNum = res[i - 1] + formatedNum;
                    formatedNum = res.charAt(i - 1) + formatedNum;
                    c += 1;
                }

                

                result = (splitVal === undefined) || (decimalVal === 0) ? formatedNum : formatedNum + decimalVal;
            }

            // Places K, M, B number format
            if (chart.formatnumberscale == 1) {
                res = num;
                for (i = 0; i < numberScaleValue.length && res / numberScaleValue[i] >= 1; i += 1) {
                    res = res / numberScaleValue[i];
                }
                i -= 1;

                if (chart.decimals === '') {
                    chart.decimals = '2';
                }
                power = Math.pow(10, chart.decimals);
                result = (i === -1) ? Math.round(res * power) / power :
                    Math.round(res * power) / power;
            }

            // force decimal is true
            if (chart.forcedecimals == 1) {
                // checkes wather the result number is in decimal or not
                if (result.toString().indexOf(chart.decimalseparator) !== -1) {
                    decimalVal = result.toString().split(chart.decimalseparator)[1];
                    decimalLength = decimalVal.length;
                }
                else {
                    decimalLength = 0;
                }
                if (decimalLength === 0) {
                    result += chart.decimalseparator;
                }
                if (decimalLength > chart.decimals) {
                    if (chart.decimals == 1 && decimalVal[0] >= 5) {
                        last = result[result.substring(0, result.indexOf('.')).length - 1];
                        last += 1;
                        result = result.substring(0, (result.indexOf('.') - 1)) + last;
                        result = result + '.' + '0';
                    }
                    else if (decimalVal[chart.decimals - 1] >= 5) {
                        last = decimalVal[chart.decimals] >= 5 ? 
                            (parseInt(decimalVal[chart.decimals - 1], 10) + 1) :
                            decimalVal[chart.decimals - 1];
                        decimalVal = decimalVal.substring(0, chart.decimals - 1) +
                            last;
                        result = result.substring(0, result.indexOf('.')) + '.' +
                            decimalVal;
                    }
                }
                else {
                    while (decimalLength < chart.decimals) {
                        result += '0';
                        decimalLength += 1;
                    }
                }
            }

            if (chart.formatnumberscale == 1) {
                result += (i === -1) ?
                    chart.defaultnumberscale : numberScaleUnit[i];
            }

            result = chart.numberprefix + result + chart.numbersuffix;

            if (chart.decimalseparator !== "." && chart.decimalseparator !== "") {
                result = result.toString().replace('.', chart.decimalseparator);
            }
            return result;
        };

        //map FusionCharts anchor sides to HC anchor symbol
        //anchorside > 4 will show the down triangle
        var convertAncorSide = function (num) {
            var x = 'circle';
            switch (num) {
            case 3:
                x = 'triangle';
                break;
            case 4:
                x = 'diamond';
                break;
            default:
                x = 'square';
                break;
            }
            if (isNaN(num)) {
                x = 'circle';
            }

            return x;
        };


        var newwindow;///variable for popup
        ///this function will open a link

        var pointClick = function (flag, unescape) {

            var link = '', arr, chartId, sender;
            if ((flag === 2 && this.link) ||
                (flag === 1 && this.options.chart.link)) {

                if (flag === 2 && this.link) {
                    link = unescape == '0' ?
                        this.link : window.decodeURIComponent(this.link);
                    link.replace(/^[\s]*/, '');
                    arr = this.link.split('-');
                    chartId = this.options.id;
                    sender = global.core.items[chartId];
                }

                else if (flag === 1 && this.options.chart.link) {
                    link = unescape == '0' ?
                        this.options.chart.link : window.decodeURIComponent(this.options.chart.link);
                    link.replace(/^[ ]*/, '');
                    arr = this.options.chart.link.split('-');
                    chartId = this.options.chart.renderTo;
                    sender = global.core.items[chartId];
                }
                switch (arr[0].toLowerCase()) {
                case 'n':
                    window.open(arr[1]);
                    break;
                case 'f':
                    if (frames[arr[1]]) {
                        frames[arr[1]].location = arr[2];
                    }
                    else {
                        window.open(arr[2], arr[1]);
                    }
                    break;
                case 'j':
                    try {
                        window[arr[1]](arr[2]);
                    }
                    catch (er) {

                    }
                    break;
                case 'p':
                    newwindow = window.open(arr[2], arr[1].match(/[^,]+/i),
                        arr[1].replace(/[^,]+,/i, ''));
                    if (window.focus) {
                        newwindow.focus();
                    }
                    break;
                case 'newchart':
                    var linkType = arr[1].toLowerCase().search('url') != -1 ?
                        'URL' : FusionChartsDataFormats.JSON, data, x, chartJson, linkchartId;

                    if (linkType === 'URL') {
                        data = link.replace(/[^\-]+-[^\-]+-/i, '');
                    }

                    else {
                        chartJson = global.core.items[chartId].getChartData(FusionChartsDataFormats.JSON);
                        linkchartId = link.replace(/[^\-]+-[^\-]+-/i, '');
                        if (chartJson.linkeddata) {
                            for (x = 0; x < chartJson.linkeddata.length; x += 1) {
                                if (chartJson.linkeddata[x].id == linkchartId) {
                                    data = chartJson.linkeddata[x].linkedchart;
                                }
                            }
                        }
                    }
                    global.raiseEvent('LinkedChartInvoked', {
                        linkType : linkType,
                        data : data
                    }, sender);
                    break;
                default:
                    arr = link.split(':');
                    if (arr[0].toLowerCase() == 'javascript') {
                        try {
                            eval(arr[1]);
                        } catch (err) { }
                    }
                    else {
                        window.location.href = link;
                    }
                    break;
                }
            }
        };


        ///fiend the max z value among all the data
        var maxZVal = function (obj) {
            var max = 0, x, y, temp;
            if (obj instanceof Array) {
                for (x = 0; x < obj.length; x += 1) {
                    if (obj[x].data instanceof Array) {
                        for (y = 0; y < obj[x].data.length; y += 1) {
                            temp =  parseInt(obj[x].data[y].z, 10);
                            max = (max > temp) ? max : temp;
                        }
                    }
                }
            }
            return max;
        };

        ///function to determine max redious for buble chart depending on the chart width and height
        var maxRedious = function (width, height) {
            var lower, max = 5;
            lower = (width > height) ? height : width;
            if (lower > 100) {
                max = lower / 8;
            }
            return max;
        };

        ///function that will create a HCJSON
        var createHCJson = function (__containerId, cleanCopy) {
            var width = this.width, height = this.height,
                chart = convertSWFtoAlias(this.src), id = this.id,
                jsonObj, container, dataComp, HCJson;

            //destroy the old chart
            removeChart(id, cleanCopy);

            //create container
            createContainer.call(this, __containerId, id, width, height, cleanCopy);

            //get the chart data
            dataComp = this.getChartData(FusionChartsDataFormats.JSON, true);
            jsonObj = dataComp.data;

            //chart not supported
            if (!FCFCC.highCharts[chart]) {
                HCJson = createMsg(id, 'ChartNotSupported', 'featurenotsupported');
            }
            ////if chart has any msg to show
            else if (chartMessageStore[id].msgTxt) {
                HCJson = createMsg(id, 'msgTxt', undefined);
            }
            ////if chart has data loading error
            else if (this.jsVars && this.jsVars.LoadError) {
                HCJson = createMsg(id, 'LoadDataErrorText', undefined);
            }
            ////if chart is retriving data
            else if (this.jsVars && this.jsVars.stallLoad) {
                HCJson = createMsg(id, 'XMLLoadingText', undefined);
            }
            ////if chart will show xml parse error
            else if (dataComp.error instanceof Error) {
                HCJson = createMsg(id, 'InvalidXMLText', 'nodatatodisplay');
            }
            //convert the chart's JSON into HC compatable data
            else {
                //get the container
                container = document.getElementById(id);
                //convart it into FCC data
                HCJson = covertToFCC(id, chart, jsonObj, id, container.offsetWidth,
                    container.offsetHeight);
                
                //if no data to display
                if (HCJson.series.length === 0) {
                    HCJson = createMsg(id, 'NoDataText', 'nodatatodisplay');
                }

            }
            //return the HC object
            return HCJson;

        };

        ///add the event listner to add the chart specific functions
        var addEIMethods = function (ref, obj) {

            // Process these events only for objects that have flash renderer!
            if (obj.options.renderer !== 'javascript') {
                return;
            }

            // The externalInterfaceMethods names are parsed from CSV to
            // Array.
            var eiItems = FCFCC.method.getExternalInterfaceMethods(obj.chartType()).split(','), i;

            // We iterate through all the externalInterface method names and
            // create an extensible API object that is added to main
            // FusionCharts object as reference.
            for (i = 0; i < eiItems.length; i += 1) {

                if (typeof ref[eiItems[i]] === 'function') {
                    continue;
                }

                if (FCFCC.method[eiItems[i]] === undefined) {
                    ref[eiItems[i]] = noEffect;
                }
                else {
                    ref[eiItems[i]] = FCFCC.method[eiItems[i]];
                }
            }
        };

          ////////function that will create the chart
        ///this will represent the Fusionchart object
        function createChart(callBack, HCJson) {
            var chart = convertSWFtoAlias(this.src), id = this.id;

            //set the trancparency
            if (!(this.jsVars && this.jsVars.transparent)) {
                jQuery('#' + this.id).css('background-color', '#FFFFFF');
            }

            //create the ref
            var ref = document.getElementById(id);

            ref.drawOverlayButton =  drawOverlayButton;
            ref.showChartMessage = showChartMessage;
            ref.getExternalInterfaceMethods = FCFCC.method.getExternalInterfaceMethods;
            addEIMethods(ref, this);

            //call the call back function
            if (typeof callBack === 'function') {
                try {
                    callBack({
                        success: true,
                        ref: ref,
                        id: id
                    });
                }catch (err) {
                }
            }

            ref.FCC = new Highcharts.Chart(HCJson);

            //arrange vlines & trendlines trendZones etc.
            var verticalPlotElements = jQuery('.highcharts-grid')[0];
            var horizontalPlotElements = jQuery('.highcharts-grid')[1];
            if (verticalPlotElements && horizontalPlotElements) {
                verticalPlotElements.parentNode.insertBefore(horizontalPlotElements, verticalPlotElements);
            }
            //save the chart's various objects in local store
            FCC.items[id] = {
                'baseObj': HCJson,
                'FCCObj': ref,
                'chartType': chart
            };


        }
        

        //function that will be called on window resize if there has a size difference occure
        var onWindowResize = function (FCCChart, id) {
            var FCObj;
            //Clear the timer for the previous prevoius frequent resize function
            clearTimeout(FCCChart.timeChach);
            //FusionCharts OBJECT
            FCObj = global.core.items[id];
            //render the chart after 500 ms
            FCCChart.timeChach = setTimeout((function (FCObj) {
                return function () {
                    var id = FCObj.id, __containerId = FCObj.options.containerElementId, HCJson;
                    // check whether chart already rendered and update the data
                    if (typeof __containerId !== 'undefined' &&
                        typeof FCC.items[id] !== 'undefined') {
                        //create the HC json
                        HCJson = createHCJson.call(FCObj, __containerId);
                        //add the load event
                        HCJson.chart.events.load = function () {
                            global.raiseEvent('loaded', {}, FCObj);
                            global.raiseEvent('resized', {}, FCObj);
                        };
                        //stop the animation
                        HCJson.plotOptions.series.animation = false;

                        //render the new chart
                        createChart.call(FCObj, undefined, HCJson);

                    }
                };
            }(FCObj)), 500);
        };


        //function that will call if the window resize ocured
        var FCCresize = function () {

            var FCCChart, FCCContainer, id;
            //iterate through all FusionCharts Html5 Items From Store
            for (var x in FCC.items) {   ///it's alwayes an object
                id = x;
                //take the in a local a vriable
                FCCChart = FCC.items[x];
                //get the container Element of the chart
                FCCContainer = document.getElementById(x);

                //Check whether the size of the container has Changed
                //container will be null if the chart has invocked a linked chart
                if (FCCContainer &&
                    (FCCContainer.offsetWidth != FCCChart.FCCObj.FCC.chartWidth ||
                        FCCContainer.offsetHeight != FCCChart.FCCObj.FCC.chartHeight)) {
                    onWindowResize(FCCChart, id);
                }
            }
        };



        ///add the resize event

        if (window.addEventListener) {//for nonIE
            window.addEventListener("resize", FCCresize, false);
        }
        else if (window.attachEvent) {//for IE
            window.attachEvent("onresize", FCCresize);
        }
        else {//older browser
            window.onresize = FCCresize;
        }


        ////////////////  Working Functions /////////////////////

        //this function will create a default FCC object
        //
        var newFCCstub = function (cObj) {
            return {
                FCconf: {
                    negative: false,//for inverse chart whether there has any negative value or not
                    stackValue: []
                },
                chart: {
                    alignTicks: false,
                    renderTo: '',
                    ignoreHiddenSeries: false,
                    events: {
                        click: function () {
                            pointClick.call(this, 1, cObj.chart.unescapelinks);
                        }
                    },
                    margin: [15, 15, 50, 60],
                    borderRadius: 0,
                    plotBackgroundColor : '#FFFFFF'
                },
                colors: [],
                credits: {
                    href: 'http://www.fusioncharts.com?BS=FCHSEvalMark',
                    text: 'FusionCharts - HighCharts',
                    enabled: FCCCREDITSTATE
                },
                global: {},
                labels: {
                    items: []
                },
                lang: {},
                legend: {
                    enabled : true,
                    symbolWidth: 5,
                    borderRadius: 0,
                    itemStyle: {}
                },
                loading: {},
                plotOptions: {
                    series: {
                        borderColor : '#333333',
                        events: {},
                        dataLabels : {
                            enabled : true,
                            color: '#555555',
                            style: {},
                            formatter : function () {
                                var x, y, showvalue = this.point.options.showvalue ||
                                    this.series.options.showvalue ||
                                    this.series.chart.options.chart.showvalue;
                                if (this.series.type == 'pie') {
                                    x = (cObj.chart.showlabels === '0' || !this.point.FCname ? '' : this.point.FCname);
                                    y = showvalue === '1' ? (Math.round(this.percentage * 100) / 100) + (cObj.chart.showpercentagevalues === '1' ? '%' : ''): '';
                                    
                                    return parseStr(x + (x !== '' && y !== '' ? cObj.chart.tooltipsepchar : '') + y);
                                }
                                else if (showvalue === '1') {
                                    if (this.point.options.displayvalue) {
                                        x = this.point.options.displayvalue;
                                    } else {
                                        x = formatNumber(this.y, cObj.chart, 1);
                                        if (this.series.options.FCtype == 1.5 && this.series.type == 'line') {
                                            x = this.y + '%';
                                        }
                                        if (this.series.options.FCtype == 7.5) {
                                            x = '';
                                        }
                                    }
                                    return parseStr(x);
                                } else {
                                    return '';
                                }
                            }
                        },
                        point: {
                            events: {
                                click:  function () {
                                    pointClick.call(this, 2, cObj.chart.unescapelinks);
                                }
                            }
                        }
                    },
                    area: {
                        states: {
                            hover: {
                                enabled: false
                            }
                        },
                        marker: {
                            lineWidth: 1,
                            radius: 3,
                            states: {
                                hover: {
                                    enabled: false
                                },
                                select: {
                                    enabled: false
                                }
                            }
                        }
                    },
                    areaspline: {
                        states: {
                            hover: {
                                enabled: false
                            }
                        },
                        marker: {
                            lineWidth: 1,
                            radius: 3,
                            states: {
                                hover: {
                                    enabled: false
                                },
                                select: {
                                    enabled: false
                                }
                            }
                        }
                    },
                    line: {
                        states: {
                            hover: {
                                enabled: false
                            }
                        },
                        marker: {
                            lineWidth: 1,
                            radius: 3,
                            states: {
                                hover: {
                                    enabled: false
                                },
                                select: {
                                    enabled: false
                                }
                            }
                        }
                    },
                    spline: {
                        states: {
                            hover: {
                                enabled: false
                            }
                        },
                        marker: {
                            lineWidth: 1,
                            radius: 3,
                            states: {
                                hover: {
                                    enabled: false
                                },
                                select: {
                                    enabled: false
                                }
                            }
                        }
                    },
                    pie: {
                        size: '80%',
                        allowPointSelect: true,
                        cursor: 'pointer'
                    },
                    column: {}
                },
                point: {},
                series: [{}],
                subtitle: {
                    text: '',
                    style : {}
                },
                symbols: [],
                title: {
                    text : '',
                    style : {}
                },
                toolbar: {},
                tooltip: {
                    borderRadius: 0,
                    style: {},
                    formatter : function () {
                        var x = '', charSep = cObj.chart.tooltipsepchar;
                        if (this.point.options.tooltext) {
                            x = this.point.options.tooltext;
                        } else {
                            if (this.series.options.FCtype >= 2 && this.series.name !== ' ') {
                                x += this.series.name + charSep;
                            }
                            x += this.x ? this.x + charSep: '';
                            if (this.series.options.FCtype == 1.5 && this.series.type == 'line') {
                                x = this.x + charSep + this.y + '%';
                            }
                            else if (this.series.type == 'pie') {
                                x = (this.point.FCname ? this.point.FCname + charSep: '') + (Math.round(this.percentage * 100) / 100) + (cObj.chart.showpercentintooltip === '0' ? '' : '%');
                            }
                            else if (this.series.options.FCtype === 2.5) {
                                x = formatNumber(this.y, cObj.chart, 1);
                            }
                            else {
                                x += formatNumber(this.y, cObj.chart, 1);
                            }
                            if (this.point.z) {
                                x += charSep + this.point.z;
                            }
                        }
                        return parseStr(x);
                    }
                },
                xAxis: {
                    labels: {
                        rotation: -25,
                        style: {},
                        formatter : function () {
                            return parseStr(typeof this.value === 'string' ? this.value : '');
                        },
                        align: 'right'
                    },
                    categories: [],
                    plotLines: [],
                    plotBands: [],
                    title : {
                        style: {},
                        text: ''
                    }
                },
                yAxis: [{
                    startOnTick: false,
                    endOnTick : false,
                    title : {
                        style: {},
                        text : ''
                    },
                    labels: {
                        style: {},
                        formatter : function () {
                            return parseStr(formatNumber(this.value, cObj.chart, 1));
                        }
                    },
                    plotBands: [],
                    plotLines: []
                }, {
                    gridLineWidth: 0,
                    startOnTick: false,
                    endOnTick : false,
                    title : {
                        style: {},
                        text : ''
                    },
                    labels: {
                        style: {},
                        enabled : false,
                        formatter : function () {
                            return parseStr(formatNumber(this.value, cObj.chart, 2));
                        }
                    },
                    opposite: true
                }],
                exporting: {
                    buttons: {
                        exportButton: {},
                        printButton: {
                            enabled: false
                        }
                    }
                }
            };

        };
        
        ///add xAxis Plot Lines
        function addVline(hc, color, alpha, width, value) {
            hc.xAxis.plotLines.push({
                color: convertColor(color || '#444444', alpha || 100),
                width: width ? width : 1,
                value: value
            });
        }


        ///function to add points in a series
        function addPoint(seriesObj, pointObj, series, chartName, id, HCObj, getScolor, bubbleRatio, FCObj) {
            var x, y, isAbs = FCFCC.valueAbs[chartName], value,
            seriesType = seriesObj.type || HCObj.chart.defaultSeriesType,
            dataseperator, tempY, dataYmax, dataYmin, loopMax;

            //fiend the variable name max and min calculation
            if (!seriesObj.yAxis) {
                dataYmax = 'dataY0max';
                dataYmin = 'dataY0min';
            }
            else {
                dataYmax = 'dataY' + seriesObj.yAxis + 'max';
                dataYmin = 'dataY' + seriesObj.yAxis + 'min';
            }

            //set the looping limit depending upon chart type
            if (series < 7 && series >= 2 && series != 2.5) {
                loopMax = HCObj.xAxis.categories.length;
            }
            else {
                loopMax = pointObj.length;
            }

            //itterate through pointObj
            if (pointObj instanceof Array) {
                for (y = 0; y < loopMax; y += 1) {
                    if (typeof pointObj[y] === 'object') {
                        if (!pointObj[y].vline) {
                            if (seriesObj.cursor != 'pointer' && pointObj[y].link) {
                                seriesObj.cursor = 'pointer';
                            }
                            // add the data point
                            tempY = (isAbs ? Math.abs(parseFloat(pointObj[y].value || pointObj[y].y)):parseFloat(pointObj[y].value || pointObj[y].y));
                            //check whether it is an negative value
                            if (tempY < 0) {
                                HCObj.FCconf.negative = true;
                            }
                            //check whether it is the max value
                            if (typeof HCObj.FCconf[dataYmax] === 'undefined' || tempY > HCObj.FCconf[dataYmax]) {
                                HCObj.FCconf[dataYmax] = tempY;
                            }
                            //check whether it the min value
                            if (typeof HCObj.FCconf[dataYmin] === 'undefined' || tempY < HCObj.FCconf[dataYmin]) {
                                HCObj.FCconf[dataYmin] = tempY;
                            }

                            //set the stacking value
                            if (typeof HCObj.FCconf.stackValue[y] === 'undefined') {
                                HCObj.FCconf.stackValue[y] = tempY;
                            }
                            else {
                                HCObj.FCconf.stackValue[y] += tempY;
                            }
                            seriesObj.data.push({
                                y: tempY || 0,
                                x: (isAbs ? Math.abs(parseFloat(pointObj[y].x)):parseFloat(pointObj[y].x)) || undefined,
                                z: (isAbs ? Math.abs(parseFloat(pointObj[y].z)):parseFloat(pointObj[y].z)) || undefined,
                                color: (series < 2 || pointObj[y].color || pointObj[y].alpha) ? convertColor(pointObj[y].color || (series < 2 && getScolor(y)), pointObj[y].alpha, seriesObj.color): undefined,
                                FCname: series < 2 ? pointObj[y].label : undefined,// for single serease add the label as name
                                link: pointObj[y].link,
                                sliced: pointObj[y].issliced == '1',
                                id: id,
                                displayvalue: pointObj[y].displayvalue,
                                showvalue: pointObj[y].showvalue,
                                tooltext: pointObj[y].tooltext,
                                marker: (seriesType === 'areaspline' || seriesType === 'spline' || seriesType === 'area' || seriesType === 'line') ? {
                                    enabled : pointObj[y].drawanchors ? (pointObj[y].drawanchors == '1' ? true : false) : undefined,
                                    radius: pointObj[y].anchorradius ? pointObj[y].anchorradius : undefined,
                                    symbol: convertAncorSide(parseInt(pointObj[y].anchorsides, 10)),
                                    fillColor: pointObj[y].anchorbgcolor ? convertColor(pointObj[y].anchorbgcolor || 'FFFFFF', pointObj[y].anchorbgalpha || 100) : undefined,
                                    lineColor: pointObj[y].anchorbordercolor ? convertColor(pointObj[y].anchorbordercolor || '767575', pointObj[y].anchoralpha || 100) : undefined,
                                    lineWidth: pointObj[y].anchorborderthickness ? pointObj[y].anchorborderthickness : undefined
                                } : (series === 7.5 ? {
                                    radius: pointObj[y].z ? Math.round((pointObj[y].z / bubbleRatio) * 10) / 10: undefined
                                } : null)
                            });


                            //add category for single series chart
                            if (series < 2) {
                                HCObj.xAxis.categories.push(pointObj[y].showlabel === '0' ? '' : (pointObj[y].label || ''));
                            }
                        }
                        else if (series < 2) {
                            ///if it is a vline then add plotlone or band for it
                            addVline(HCObj, pointObj[y].color, pointObj[y].alpha, pointObj[y].thickness, y - 0.5);
                        }
                    } else if (typeof pointObj[y] === 'string' && FCObj.chart.compactdatamode == '1') {
                        dataseperator = FCObj.chart.dataseparator || '|';
                        value = pointObj[y].split(dataseperator);
                        for (x = 0; x < value.length; x += 1) {
                            tempY = isAbs ? Math.abs(parseFloat(value[x])):parseFloat(value[x]);
                            //check whether it is an negative value
                            if (tempY < 0) {
                                HCObj.FCconf.negative = true;
                            }
                            //check whether it is the max value
                            if (typeof HCObj.FCconf[dataYmax] === 'undefined' || tempY > HCObj.FCconf[dataYmax]) {
                                HCObj.FCconf[dataYmax] = tempY;
                            }
                            //check whether it the min value
                            if (typeof HCObj.FCconf[dataYmin] === 'undefined' || tempY < HCObj.FCconf[dataYmin]) {
                                HCObj.FCconf[dataYmin] = tempY;
                            }
                            seriesObj.data.push({
                                y : tempY || 0
                            });
                        }
                    }
                    else {
                        seriesObj.data.push({y: 0});
                    }
                }
            }
        }


///////function to add category

        function addCategory(obj, series, hc) {
            var x, dataseperator, countCat = 0, zoomCat = [];
            if (obj.categories && obj.categories[0].category) {
                for (x = 0; x < obj.categories[0].category.length; x += 1) {
                    if (typeof obj.categories[0].category[x] === 'object') {
                        if (!obj.categories[0].category[x].vline) {
                            if (series == 2.5) {
                                zoomCat.push(obj.categories[0].category[x].label ? obj.categories[0].category[x].label: '');
                            } else {
                                hc.xAxis.categories.push(obj.categories[0].category[x].label ? obj.categories[0].category[x].label: '');
                            }

                            countCat += 1;
                        }
                        else {
                            addVline(hc, obj.categories[0].category[x].color, obj.categories[0].category[x].alpha, obj.categories[0].category[x].thickness, countCat - 0.5);
                        }
                    } else if (typeof obj.categories[0].category[x] === 'string' && obj.chart.compactdatamode == '1') {
                        dataseperator = obj.chart.dataseparator ? obj.chart.dataseparator : '|';
                        if (series == 2.5) {
                            zoomCat = zoomCat.concat(obj.categories[0].category[x].split(dataseperator));
                        } else {
                            hc.xAxis.categories = hc.xAxis.categories.concat(obj.categories[0].category[x].split(dataseperator));
                        }
                    }
                }
            }
           ///fix of bug #510
            if (series === 2.5) {
                hc.xAxis.maxZoom = 2;
                hc.chart.zoomType = 'x';
                hc.chart.showvalue = obj.chart.showvalues === '1' ? '1' : '0';
                hc.xAxis.categories = undefined;
                hc.xAxis.min = 0;
                hc.xAxis.max = zoomCat.length - 1;
                hc.xAxis.endOnTick = false;
                hc.xAxis.labels.formatter = function () {
                    return  parseStr(zoomCat[this.value]);
                };
            }

        }


        //create MSStacked Chart

        function MSStacked(obj, series, chartName, HCObj, width, hight, id, getScolor) {
            var x, y, z, newSeries, count_set = 0, max_cat = 0, a = [],
            plotWidth = width - (HCObj.chart.margin[1] + HCObj.chart.margin[3]),
            plotHight = hight - (HCObj.chart.margin[0] + HCObj.chart.margin[2]),
            noStack, pointWidth, catWidth, tempheight, maxHight = 0, pointValue = 0;

            // get the no of category
            if (obj.categories && obj.categories[0] && obj.categories[0].category) {
                max_cat = obj.categories[0].category.length;
            }

            //if there has parent dataset trhen ierate bthrough it

            if (obj.dataset && obj.dataset instanceof Array) {
                noStack = obj.dataset.length;
                catWidth = plotWidth / max_cat;
                pointWidth = catWidth / (noStack + 2);
                for (x = 0; x < obj.dataset.length; x += 1) {
                    tempheight = [];
                    //if it has child dataset then itterate through it
                    if (obj.dataset[x].dataset && obj.dataset[x].dataset instanceof Array) {
                        for (y = 0; y < obj.dataset[x].dataset.length ; y += 1) {
                            newSeries = {
                                FCtype: series,
                                type: obj.dataset[x].dataset[y].renderas ? FCFCC.combi[obj.dataset[x].dataset[y].renderas.toLowerCase()]: undefined,
                                name: obj.dataset[x].dataset[y].seriesname ? obj.dataset[x].dataset[y].seriesname: ' ',
                                data: [],
                                color: convertColor(obj.dataset[x].color || getScolor(count_set), obj.dataset[x].dataset[y].alpha || 100),
                                yAxis: obj.dataset[x].dataset[y].parentyaxis == 'S' ? 1 : undefined,
                                showvalue: obj.dataset[x].dataset[y].showvalues,
                                mColum: x,
                                mStack: y
                            };

                            if (obj.dataset[x].dataset[y].data instanceof Array) {
                                for (z = 0; z < max_cat; z += 1) {
                                    tempheight[z] = tempheight[z] ? tempheight[z] : 0;
                                    pointValue = (obj.dataset[x].dataset[y].data[z] && obj.dataset[x].dataset[y].data[z].value) ? parseFloat(obj.dataset[x].dataset[y].data[z].value): '';
                                    tempheight[z] += pointValue ? pointValue : 0;
                                    newSeries.data.push({
                                        MWidth: pointWidth,
                                        MX: (z * catWidth) + ((x + 1) * pointWidth),
                                        MY: tempheight[z],
                                        y: pointValue,
                                        link:  obj.dataset[x].dataset[y].data[z].link,
                                        id: id,
                                        color: obj.dataset[x].dataset[y].data[z].color ? convertColor(obj.dataset[x].dataset[y].data[z].color || getScolor(count_set), obj.dataset[x].dataset[y].data[z].alpha || 100) : undefined
                                    });
                                }
                            }

                            a.push(newSeries);
                            //at the end increament the serease counter
                            count_set += 1;
                        }
                    }
                    for (z = 0; z < max_cat; z += 1) {
                        if (maxHight < tempheight[z]) {
                            maxHight = tempheight[z];
                        }
                    }
                }
            }


            HCObj.yAxis[0].max = maxHight + 5;


            return a;
        }
        

         ///Function To add data & categories
        function addData(obj, series, chartName, hc, width, height, id) {
            var a = [], x, y, z, count_set, getScolor, pareto, yCalTemp;
            //this function widecide the default color for a series or data;
            getScolor = function (index) {
                var palett;
                //if palett color is supplyed then use it
                //check whether it is an coma seperated string then split it into array
                //else use default color palett
                palett = (obj.chart.palettecolors) ? ((typeof obj.chart.palettecolors === 'string') ? obj.chart.palettecolors.split(',') : obj.chart.palettecolors) : FCFCC.color;
                ///return color for the index position
                return palett[index % palett.length];

            };

            //switch depending on the chart's type/series
            switch (series) {
            case 1:  //single series
            case 1.5:  //pareto
                ///options that have to change for pareto chart
                if (series == 1.5) {
                    //set the secondary y axis value max as 100
                    hc.yAxis[1].max = 100;
                    //set the label formater for line series
                    hc.yAxis[1].labels.formatter = function () {
                        return parseStr(this.value + '%');
                    };
                    if (obj.data instanceof Array) {
                        obj.data.sort(function (a, b) {
                            return parseFloat(b.value) - parseFloat(a.value);
                        });
                    }
                }


                    ///// special setings for single series /////
                    //disable the legend for chhart
                hc.legend.enabled = false;


                    //////category  &&  data\\\\\\\

                    //iterate through all the data
                if (obj.data) {
                    z = createSeries(series);
                    addPoint(z, obj.data, series, chartName, id, hc, getScolor);
                     
                     ///pie speciffic functions
                    if (convertNames(chartName) == 'pie') {
                        //revarse the series for pie/douggnut to make it clockwise
                        z.data.reverse();
                        //remove the serease color to avoid onclick discolored slice
                        z.color = undefined;
                        //remove the plotboder
                        hc.chart.plotBorderWidth = 0;
                        //set the max margin
                        x = hc.chart.borderWidth + 1;
                        hc.chart.margin = [hc.chart.margin[0], x, x, x];
                    }
                    a.push(z);
                    //for pareto add the line serease
                    if ((chartName == 'Pareto2D' || chartName == 'Pareto3D') && obj.chart.showcumulativeline !== '0') {
                        pareto = paretoConvert(z.data);
                        a.push(pareto);
                    }
                }
                break;
            case 2:
            case 2.2:
            case 2.5:                
            case 3:                
            case 4:
            case 5:
            case 5.5:
            case 6:
            case 6.5:
                switch (series) {
                case 2.2:
                    hc.yAxis[0].reversed = true;
                    //set the PC reverse chart flag
                    hc.yAxis[0].PCreversed = true;
                    break;
                case 3:
                    if (obj.chart.stack100percent == '1') {
                        hc.plotOptions.series.stacking = 'percent';
                        hc.yAxis[0].labels.formatter = function () {
                            return parseStr(this.value + '%');
                        };
                    } else {
                        hc.plotOptions.series.stacking = 'normal';
                    }
                    break;
                case 5.5:
                    hc.plotOptions.column.stacking = obj.chart.stack100percent == '1' ? 'percent' : 'normal';
                }
                  ////////category////////

                addCategory(obj, series, hc);
                   ////////data////////
                if (series !== 6) {
                    if (obj.dataset) {
                        count_set = 0;
                        for (x = 0;x < obj.dataset.length;x += 1) {
                            z = createSeries(series, convertColor(obj.dataset[x].color ||  getScolor(count_set), obj.dataset[x].alpha ||  100),
                            obj.dataset[x].renderas ? FCFCC.combi[obj.dataset[x].renderas.toLowerCase()]: ((obj.dataset[x].parentyaxis == 'S' && (series == 5 || series == 5.5)) ? 'line': undefined),
                            obj.dataset[x].seriesname, obj.dataset[x].parentyaxis == 'S', obj.dataset[x].showvalues,
                            obj.dataset[x].plotbordercolor ? convertColor(obj.dataset[x].plotbordercolor, obj.dataset[x].plotborderalpha || 95) : undefined,
                            parseInt(obj.dataset[x].plotborderthickness || obj.dataset[x].linethickness, 10) || undefined);

                            var seriesType = z.type || hc.chart.defaultSeriesType;
                            z.marker = (seriesType === 'area' || seriesType === 'line' || seriesType === 'spline' || seriesType === 'areaspline') ? {
                                    enabled : obj.dataset[x].drawanchors ? (obj.dataset[x].drawanchors == '1' ? true : false) : undefined,
                                    radius: obj.dataset[x].anchorradius ? obj.dataset[x].anchorradius : undefined,
                                    symbol: convertAncorSide(parseInt(obj.dataset[x].anchorsides, 10)),
                                    fillColor: obj.dataset[x].anchorbgcolor ? convertColor(obj.dataset[x].anchorbgcolor || 'FFFFFF', obj.dataset[x].anchorbgalpha || 100) : undefined,
                                    lineColor: obj.dataset[x].anchorbordercolor ? convertColor(obj.dataset[x].anchorbordercolor || '767575', obj.dataset[x].anchoralpha || 100) : undefined,
                                    lineWidth: obj.dataset[x].anchorborderthickness ? obj.dataset[x].anchorborderthickness : undefined
                                } : null;



                            count_set += 1;
                            if (obj.dataset[x].data) {
                                addPoint(z, obj.dataset[x].data, series, chartName, id, hc, undefined, undefined, obj);
                            }
                            a.push(z);
                        }
                        if (series == 3 || series == 5.5 || convertNames(chartName) == 'bar') {
                            var tempArr = [];
                            for (x = 0; x < a.length; x += 1) {
                                if (a[x].type) {
                                    tempArr.push(a[x]);
                                } else {
                                    tempArr.splice(0, 0, a[x]);
                                }
                            }
                            a = tempArr;
                            hc.legend.reversed = true;
                        }
                    }
                } else {
                    a = MSStacked(obj, series, chartName, hc, width, height, id, getScolor);
                }
                 

                break;
            case 7:
            case 7.5:
                    ////////special conf. for x-y plot charts
                hc.chart.showvalue = obj.chart.showvalues == '1' ? '1' : '0';

                    ////////category////////

                if (obj.categories && obj.categories[0] && obj.categories[0].category) {
                    for (x = 0;x <  obj.categories[0].category.length; x += 1) {
                        if (obj.categories[0].category[x].showverticalline && obj.categories[0].category[x].showverticalline == '1') {
                            addVline(hc, obj.categories[0].verticallinecolor, obj.categories[0].verticallinealpha, obj.categories[0].verticallinethickness, obj.categories[0].category[x].x);
                        }
                        hc.xAxis.categories[obj.categories[0].category[x].x] = obj.categories[0].category[x].label ? obj.categories[0].category[x].label: '';
                    }
                }

                    ////////data////////

                var bubbleRRatio = maxZVal(obj.dataset) / maxRedious(width, height);
                if (obj.dataset) {
                    count_set = 0;
                    for (x = 0; x < obj.dataset.length; x += 1) {
                        z = createSeries(series, convertColor(obj.dataset[x].color || getScolor(count_set), obj.dataset[x].alpha || 100), obj.dataset[x].renderas ? FCFCC.combi[obj.dataset[x].renderas]: ((obj.dataset[x].parentyaxis == 'S' && (series == 5 || series == 5.5)) ? 'line': undefined), obj.dataset[x].seriesname, obj.dataset[x].parentyaxis == 'S', obj.dataset[x].showvalues, undefined, undefined);
                        z.marker = {
                            symbol: (series == 7.5) ? 'circle' : convertAncorSide(parseInt(obj.dataset[x].anchorsides, 10)),
                            states: {
                                hover: {
                                    enabled: false
                                }
                            }
                        };
                        count_set += 1;
                        if (obj.dataset[x].data) {
                            addPoint(z, obj.dataset[x].data, series, chartName, id, hc, getScolor, bubbleRRatio);
                        }
                        a.push(z);
                    }
                }
                hc.xAxis.max = obj.chart.xaxismaxvalue ? parseInt(obj.chart.xaxismaxvalue, 10): undefined;
                hc.xAxis.min = obj.chart.xaxisminvalue ? parseInt(obj.chart.xaxisminvalue, 10): undefined;
                hc.xAxis.showLastLabel  = true;
                break;
            default:
                break;
            }
            if (!((series === 3 || series === 5.5) && obj.chart.stack100percent === '1') && chartName !== 'Marimekko') {//if it is not an 100percent stack chart
                if (series === 3 || series === 5.5) {
                    hc.FCconf.dataY0max = hc.FCconf.stackValue[0];
                    hc.FCconf.dataY0min = hc.FCconf.stackValue[0];
                    for (y = 1; y < hc.FCconf.stackValue.length; y += 1) {
                        if (hc.FCconf.stackValue[y] > hc.FCconf.dataY0max) {
                            hc.FCconf.dataY0max = hc.FCconf.stackValue[y];
                        }
                        if (hc.FCconf.stackValue[y] < hc.FCconf.dataY0min) {
                            hc.FCconf.dataY0min = hc.FCconf.stackValue[y];
                        }
                    }
                }

                if (series === 1.5 || series === 5.5 || series === 5 || series === 6.5) {
                    yCalTemp = getAxisLimits(hc.FCconf.dataY0max, hc.FCconf.dataY0min, obj.chart.pyaxismaxvalue, obj.chart.pyaxisminvalue, '', obj.chart.setadaptiveymin !== '1');
                    hc.yAxis[0].max = yCalTemp.Max;
                    hc.yAxis[0].min = yCalTemp.Min;
                    if (series !== 1.5) {
                        yCalTemp = getAxisLimits(hc.FCconf.dataY1max, hc.FCconf.dataY1min, obj.chart.syaxismaxvalue, obj.chart.syaxisminvalue, '', obj.chart.setadaptivesymin !== '1');
                        hc.yAxis[1].max = yCalTemp.Max;
                        hc.yAxis[1].min = yCalTemp.Min;
                    }
                }
                else {
                    yCalTemp = getAxisLimits(hc.FCconf.dataY0max, hc.FCconf.dataY0min, obj.chart.yaxismaxvalue, obj.chart.yaxisminvalue, '', obj.chart.setadaptiveymin !== '1');
                    hc.yAxis[0].max = yCalTemp.Max;
                    hc.yAxis[0].min = yCalTemp.Min;
                }
            }
            hc.series = a;
        }

    ///modify as per legend
        function ModyfyLegend(hc, obj) {
            var noSeries = hc.series.length, legendPos, legendWidth, legendHeight;

            if (obj.chart.showlegend == '0') {
                hc.legend.enabled = false;
            } else {
                hc.legend.labelFormatter  = function () {
                    return parseStr(this.name);
                };
                if (obj.chart.interactivelegend == '0') {
                    hc.plotOptions.series.events.legendItemClick = function (event) {
                        return false;
                    };
                    hc.legend.itemStyle.cursor = 'default';
                }
                hc.legend.shadow = obj.chart.legendshadow == '0' ? false : true;
                hc.legend.backgroundColor = convertColor(obj.chart.legendbgcolor || '#ffffff', obj.chart.legendbgalpha || 100);
                hc.legend.borderColor = convertColor(obj.chart.legendbordercolor || '#545454', obj.chart.legendborderalpha || 100);
                hc.legend.borderWidth = obj.chart.legendborderthickness || 1;
                if (obj.reverselegend == '1') {
                    hc.legend.reversed = !hc.legend.reversed;
                }

                if (obj.chart.legendposition == 'RIGHT') {
                    hc.legend.verticalAlign = 'middle';
                    hc.legend.align = 'right';
                    hc.legend.width = 70;
                    hc.legend.x = -15;
                    hc.chart.margin[1] += 85;
                } else {
                    hc.legend.x = 0;
                    hc.chart.margin[2] += 46;
                }
            }

        }

        ////function to enter a text in chart
        function createText(text, top, left) {
            return {
                html: text,
                style: {
                    left: left + 'px',
                    top: top + 'px'
                }
            };

        }


 //function to convert a marimekko chart
        function convertMarimeko(HCObj, FCObj, chartWidth, chartHight, FCCategories) {
            var plotWidth = chartWidth - (HCObj.chart.margin[1] + HCObj.chart.margin[3]),
            plotHight = chartHight - (HCObj.chart.margin[0] + HCObj.chart.margin[2]),
            y, z, arr = [], total = 0, temp, setPersent = false,
            catCountFlag = true, temp2, totalpercent = 0;

            if (HCObj.xAxis.labels.enabled === false && FCObj.chart.showxaxispercentvalues != '0') {
                HCObj.xAxis.title.margin += 15;
                HCObj.chart.margin[2] += 15;

            }
            //set the plotHight quit bigger to addajust xaxispercentvalues
            if (HCObj.xAxis.labels.enabled !== false && FCObj.chart.showxaxispercentvalues != '0') {
                plotHight += 15;
            }
            if (FCCategories && FCCategories[0] && FCCategories[0].category instanceof Array) {
                temp = 0;
                for (y = 0; y < FCCategories[0].category.length ; y += 1) {
                    if (FCCategories[0].category[y].widthpercent) {
                        temp += parseFloat(FCCategories[0].category[y].widthpercent);
                    } else {
                        break;
                    }
                }
                if (y === FCCategories[0].category.length && Math.round(temp) === 100) {
                    setPersent = true;
                }
            }
            for (y = 0; y < HCObj.series.length ; y += 1) {
                for (z = 0; z < HCObj.series[y].data.length ; z += 1) {
                    if (typeof HCObj.series[y].data[z] == 'object') {
                        temp = parseFloat(HCObj.series[y].data[z].y);
                    } else {
                        temp = parseFloat(HCObj.series[y].data[z]);
                    }
                    arr[z] = arr[z] ? arr[z] + temp : temp;
                    total += temp;
                }
            }

            for (y = 0; y < HCObj.series.length ; y += 1) {
                temp = 0;
                for (z = 0; z < HCObj.series[y].data.length ; z += 1) {
                    if (typeof HCObj.series[y].data[z] !== 'object') {
                        HCObj.series[y].data[z] = {
                            y: HCObj.series[y].data[z]
                        };
                    }
                    HCObj.series[y].data[z].MX = temp;
                    if (catCountFlag) {
                        if (HCObj.xAxis.labels.enabled !== false) {
                            HCObj.labels.items.push(createText(HCObj.xAxis.categories[z], plotHight, temp));
                        }
                        if (FCObj.chart.showsum != '0') {
                            HCObj.labels.items.push(createText(formatNumber(arr[z], FCObj.chart), '-14', temp));
                        }
                    }
                    temp2 = setPersent ? parseFloat(FCCategories[0].category[z].widthpercent) / 100: (arr[z] / total);
                    totalpercent += temp2;
                    temp += HCObj.series[y].data[z].MWidth = Math.round(plotWidth * temp2);
                    if (catCountFlag && FCObj.chart.showxaxispercentvalues != '0' && totalpercent < 0.9) {
                        HCObj.labels.items.push(createText((Math.round(totalpercent * 10000) / 100) + '%', plotHight - 15, temp - 20));
                    }
                }
                catCountFlag = false;
            }
            
            HCObj.xAxis.labels.enabled = false;

        }


        /**
         * Create a HighCharts compatible object depending upon FusonChrts JSON object
         *
         * @param renderTo {string} The Id of the Div where the chart will render.
         * @param chartname {string} name of the chart e.g. Column3D, Pie2D etc.
         * @param obj {json} FusionChrarts JSON data.
         * @param id {string} The Id of the chart.
         * @param width {string} Chart's width
         * @param hight {string} Chrart's hight
         *
         * @type json
         * @return json object compatable to HighChrats
         * updated
         */

        function covertToFCC(renderTo, chartname, obj, id, width, hight) {
            var hc, y = getSeriesName(chartname), x, z, l, j, styleName, tempstyle = {}, styleArr;
            //clone the chart obj from graph or blank object
            obj.chart = obj.chart || obj.graph || {};
            delete obj.graph;
            //set the default tooltip charecter seperator if not defined
            obj.chart.tooltipsepchar = obj.chart.tooltipsepchar || ',';

            //creade defaule stub
            hc = newFCCstub(obj);
            //add chart container
            hc.chart.renderTo = renderTo;
            //fiend defaultseries type depemding upon chart's name
            hc.chart.defaultSeriesType = convertNames(chartname);
            //set the startontick and end on tick for the line, spline,area, areaspline serease
            if (hc.chart.defaultSeriesType === 'line' || hc.chart.defaultSeriesType === 'spline' || hc.chart.defaultSeriesType === 'area' || hc.chart.defaultSeriesType === 'areaspline') {
                hc.xAxis.startOnTick = true;
                hc.xAxis.endOnTick = true;
                hc.xAxis.showLastLabel = true;
            }
            ////////shadow//////////
            hc.plotOptions.series.shadow = obj.chart.showshadow === '1';

            ////////// chart specific options  //////////////////////
            if (chartname == 'Doughnut2D' || chartname == 'Doughnut3D') {
                hc.plotOptions.pie.innerSize = '50%';
            }

            /////////// Chart Element Attributes ////////////////////
            if (obj.chart.clickurl) {
                hc.chart.link = obj.chart.clickurl;
                hc.chart.id = id;
            }
            hc.plotOptions.series.animation = obj.chart.animation === '0' ? false : true;
            if (obj.chart.showlabels == '0') {
                hc.xAxis.labels.enabled = false;
                hc.chart.margin[2] = 15;
            }
            if (obj.chart.showyaxisvalues == '0' || obj.chart.showdivlinevalues == '0' || obj.chart.showdivlinevalue == '0') {
                hc.yAxis[0].labels.enabled = false;
                hc.chart.margin[3] = 15;
            }
            if (((y == 5 || y == 5.5) && obj.chart.showyaxisvalues != '0' && obj.chart.showdivlinesecondaryvalue != '0') || (y === 1.5 && obj.chart.showdivlinesecondaryvalue != '0')) {
                hc.yAxis[1].labels.enabled = true;
                hc.chart.margin[1] = 50;
            }


            //////////Chart font style////////////////////
            hc.xAxis.labels.style = {//style for x axis
                fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
            };
            hc.yAxis[0].labels.style = {//style for pyasis
                fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
            };
            hc.yAxis[1].labels.style = {//style for syaxis
                fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
            };
            //legend
            hc.legend.itemStyle = {//style for syaxis
                fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
            };
            ///datalabels
            hc.plotOptions.series.dataLabels.style = {//style for syaxis
                fontFamily: obj.chart.basefont || 'Verdana',
                fontSize:  (obj.chart.basefontsize || '10') + 'px',
                color: obj.chart.basefontcolor || '#555555'
            };
            ///tooltip
            hc.tooltip.style = {//style for syaxis
                fontFamily: obj.chart.basefont || 'Verdana',
                fontSize:  (obj.chart.basefontsize || '10') + 'px',
                color: obj.chart.basefontcolor || '#555555'
            };



            



            ////////// Anchors //////////////////////

            if (obj.chart.drawanchors == '0') {
                hc.plotOptions.area.marker.enabled = hc.plotOptions.line.marker.enabled = hc.plotOptions.areaspline.marker.enabled = hc.plotOptions.spline.marker.enabled = false;
                hc.tooltip.enabled = false;
            }
            if (obj.chart.anchorradius) {
                hc.plotOptions.area.marker.radius = hc.plotOptions.line.marker.radius = hc.plotOptions.areaspline.marker.radius = hc.plotOptions.spline.marker.radius = obj.chart.anchorradius;
            }
            hc.plotOptions.line.marker.lineColor = hc.plotOptions.areaspline.marker.lineColor = hc.plotOptions.spline.marker.lineColor = convertColor(obj.chart.anchorbordercolor || '767575', obj.chart.anchoralpha || 100);
            hc.plotOptions.area.marker.lineColor = convertColor(obj.chart.anchorbordercolor || '767575', obj.chart.anchoralpha || 0);
            if (obj.chart.anchorborderthickness) {
                hc.plotOptions.area.marker.lineWidth = hc.plotOptions.line.marker.lineWidth = hc.plotOptions.areaspline.marker.lineWidth = hc.plotOptions.spline.marker.lineWidth = obj.chart.anchorborderthickness;
            }
            hc.plotOptions.line.marker.fillColor = hc.plotOptions.spline.marker.fillColor = convertColor(obj.chart.anchorbgcolor || 'FFFFFF', obj.chart.anchorbgalpha || 100);
            hc.plotOptions.area.marker.fillColor = hc.plotOptions.areaspline.marker.fillColor = convertColor(obj.chart.anchorbgcolor || 'FFFFFF', obj.chart.anchorbgalpha || 0);
            if (obj.chart.anchorsides) {
                hc.plotOptions.area.marker.symbol = hc.plotOptions.line.marker.symbol = hc.plotOptions.areaspline.marker.symbol = hc.plotOptions.spline.marker.symbol = convertAncorSide(parseInt(obj.chart.anchorsides, 10));
            }

            //////////////line option/////////////
            hc.plotOptions.line.lineWidth =  hc.plotOptions.spline.lineWidth = parseInt(obj.chart.linethickness, 10) || (y === 1.5 ? 2 : 3);
            hc.plotOptions.line.color = hc.plotOptions.spline.color = convertColor(obj.chart.linecolor || '767575', obj.chart.linealpha || 100);

            //////////////area option/////////////
            hc.plotOptions.area.color = hc.plotOptions.areaspline.color = convertColor(obj.chart.plotfillcolor || '767575', obj.chart.plotfillalpha || 90);
            hc.plotOptions.area.lineColor  = hc.plotOptions.areaspline.lineColor  = convertColor(obj.chart.plotbordercolor || "333333", obj.chart.plotborderalpha || 95);
            hc.plotOptions.area.lineWidth   = hc.plotOptions.areaspline.lineWidth   = parseInt(obj.chart.plotborderthickness, 10) || 1;


            //////////////////////////  Divisional Lines & Grids  //////////////
            hc.yAxis[0].alternateGridColor = convertColor(obj.chart.alternatehgridcolor || 'EEEEEE', obj.chart.alternatehgridalpha || 50);
            hc.yAxis[0].gridLineColor = convertColor(obj.chart.divlinecolor || '717170', obj.chart.divlinealpha || 40);
            hc.yAxis[0].gridLineWidth = obj.chart.divlinethickness  ? obj.chart.divlinethickness : 1;


            //////////titles captions////////////
            if (obj.chart.caption) {
                hc.title.text = parseStr(obj.chart.caption);
                hc.chart.margin[0] = 35;
                ///set the caption font style
                hc.title.style = {
                    fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                    fontSize:  '13px',
                    'font-weight': 'bold',
                    color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
                };
            }
            if (obj.chart.subcaption) {
                hc.subtitle.text = parseStr(obj.chart.subcaption);
                hc.chart.margin[0] = 55;
                //set the style for the subcaption
                hc.subtitle.style = {
                    fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                    fontSize:  '11px',
                    'font-weight': 'bold',
                    color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
                };
            }
            if (obj.chart.xaxisname) {
                hc.xAxis.title.text = parseStr(obj.chart.xaxisname);
                hc.xAxis.title.margin = hc.chart.margin[2];
                hc.chart.margin[2] += 20;
                //set the style for the xAxis name
                hc.xAxis.title.style = {
                    fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                    fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                    'font-weight': 'bold',
                    color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
                };
            }
            if (y == 5 || y == 5.5 || y == 1.5) {//dual y type chart and pareto chart
                if (obj.chart.pyaxisname) {
                    hc.yAxis[0].title.text = parseStr(obj.chart.pyaxisname);
                    hc.yAxis[0].title.margin = hc.chart.margin[3];
                    hc.chart.margin[3] += 20;
                    //set the style for the secinadry yAxis name
                    hc.yAxis[0].title.style = {
                        fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                        fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                        'font-weight': 'bold',
                        color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
                    };
                }
                if (obj.chart.syaxisname) {
                    hc.yAxis[1].title.text = parseStr(obj.chart.syaxisname);
                    hc.yAxis[1].title.margin = hc.chart.margin[1];
                    hc.chart.margin[1] += 20;
                    //set the style for the secinadry yAxis name
                    hc.yAxis[1].title.style = {
                        fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                        fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                        'font-weight': 'bold',
                        color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
                    };
                }
            } else if (obj.chart.yaxisname) {
                hc.yAxis[0].title.text = parseStr(obj.chart.yaxisname);
                hc.yAxis[0].title.margin = hc.chart.margin[3];
                hc.chart.margin[3] += 20;
                //set the style for the primary yAxis name
                hc.yAxis[0].title.style = {
                    fontFamily: obj.chart.outcnvbasefont || obj.chart.basefont || 'Verdana',
                    fontSize:  (obj.chart.outcnvbasefontsize || obj.chart.basefontsize || '10') + 'px',
                    'font-weight': 'bold',
                    color: obj.chart.outcnvbasefontcolor || obj.chart.basefontcolor || '#555555'
                };
            }

            ///////// tooltip Options//////////////
            if (obj.chart.showtooltip == '0') { //area/line ancor conflict
                hc.tooltip.enabled =  false;
            }
            hc.tooltip.backgroundColor = convertColor(obj.chart.tooltipbgcolor || 'FFFFFF', obj.chart.tooltipbgalpha || 100);
            hc.tooltip.borderColor = convertColor(obj.chart.tooltipbordercolor || '545454', obj.chart.tooltipborderalpha || 100);
            hc.tooltip.shadow = obj.chart.showtooltipshadow == '1' ? true : false;


            //////// modify Lables///////
            hc.chart.showvalue = obj.chart.showvalues == '0' ? '0' : '1';

            ///////////Trend-lines /////////////////
            if (obj.trendlines) {
                for (x = 0; x < obj.trendlines.length; x += 1) {
                    if (obj.trendlines[x].line) {
                        for (z = 0; z < obj.trendlines[x].line.length; z += 1) {
                            if (obj.trendlines[x].line[z].istrendzone == '1') {
                                hc.yAxis[0].plotBands.push({
                                    color: convertColor(obj.trendlines[x].line[z].color || '333333', obj.trendlines[x].line[z].alpha || 99),
                                    from: obj.trendlines[x].line[z].startvalue ? obj.trendlines[x].line[z].startvalue : 0,
                                    to: obj.trendlines[x].line[z].endvalue
                                });
                            }
                            hc.yAxis[0].plotLines.push({
                                color: convertColor(obj.trendlines[x].line[z].color || '333333', obj.trendlines[x].line[z].alpha || 99),
                                value: obj.trendlines[x].line[z].startvalue ? obj.trendlines[x].line[z].startvalue: 0,
                                width: obj.trendlines[x].line[z].thickness ? obj.trendlines[x].line[z].thickness: 1
                            });
                        }
                    }
                }
            }

            ///////////vTrend-lines /////////////////
            if (obj.vtrendlines) {
                for (x = 0; x < obj.vtrendlines.length; x += 1) {
                    if (obj.vtrendlines[x].line) {
                        for (z = 0; z < obj.vtrendlines[x].line.length; z += 1) {
                            if (obj.vtrendlines[x].line[z].istrendzone !== '0') {
                                hc.xAxis.plotBands.push({
                                    color: convertColor(obj.vtrendlines[x].line[z].color || '333333', obj.vtrendlines[x].line[z].alpha || 99),
                                    from: obj.vtrendlines[x].line[z].startvalue ? obj.vtrendlines[x].line[z].startvalue : 0,
                                    to: obj.vtrendlines[x].line[z].endvalue
                                });
                            }
                            hc.xAxis.plotLines.push({
                                color: convertColor(obj.vtrendlines[x].line[z].color || '333333', obj.vtrendlines[x].line[z].alpha || 99),
                                value: obj.vtrendlines[x].line[z].startvalue ? obj.vtrendlines[x].line[z].startvalue: 0,
                                width: obj.vtrendlines[x].line[z].thickness ? obj.vtrendlines[x].line[z].thickness: 1
                            });
                        }
                    }
                }
            }
            //////Expprt Module/////

            hc.exporting.enabled = obj.chart.exportenabled == '1' ? true: false;
            hc.exporting.buttons.exportButton.enabled = obj.chart.exportshowmenuitem == '0' ? false : true;
            hc.exporting.filename = obj.chart.exportfilename ? obj.chart.exportfilename : 'FusionCharts';
            hc.exporting.width = width;

            //////set styles//////////
            if (obj.styles && obj.styles.definition instanceof Array && obj.styles.application instanceof Array) {
                ///retrive all style defination and store them in a temporary store
                for (j = 0; j < obj.styles.definition.length; j += 1) {
                    if (typeof FCFCC.supportedStyle[obj.styles.definition[j].type] === 'function') {
                        tempstyle[obj.styles.definition[j].name.toLowerCase()] = obj.styles.definition[j];
                    }
                }
                ///find all apply object
                for (j = 0; j < obj.styles.application.length; j += 1) {
                    styleArr = obj.styles.application[j].styles.split(',');
                    for (l = 0; l < styleArr.length; l += 1) {
                        styleName = styleArr[l].toLowerCase();
                        if (tempstyle[styleName]) {
                            FCFCC.supportedStyle[tempstyle[styleName].type](hc, obj.styles.application[j].toobject.toLowerCase(), tempstyle[styleName]);
                        }
                    }
                }
            }
            

            ////set the default angle bgcolor,alpha etc for background


            //////////background canvas plot etc [cosmetics] ///////
            hc.chart.borderWidth = obj.chart.showborder == '0' ? 0 : (obj.chart.borderthickness ? obj.chart.borderthickness: 1);
            hc.chart.borderColor = convertColor(obj.chart.bordercolor || '767575', obj.chart.borderalpha || 50);
            hc.chart.plotBorderColor = convertColor(obj.chart.canvasbordercolor || '545454', obj.chart.canvasborderalpha || 100);
            hc.chart.plotBorderWidth = obj.chart.canvasborderthickness ? obj.chart.canvasborderthickness: 2;
            /*plot*/
            hc.plotOptions.series.borderColor = convertColor(obj.chart.plotbordercolor || '333333', obj.chart.plotborderalpha || 95);
            hc.plotOptions.series.borderWidth = obj.chart.showplotborder == '0' ? 0 : (obj.chart.plotborderthickness ?  obj.chart.plotborderthickness : 1);
            hc.plotOptions.series.borderRadius = obj.chart.useroundedges == '1' ? 5 : 0;

            ///set the o position line promt
            hc.yAxis[0].plotLines.push({
                color: convertColor(obj.chart.zeroplanecolor || '717170', obj.chart.zeroplanealpha || 80),
                value: 0,
                width: obj.chart.zeroplanethickness || 2
            });
            ///modify as per series /////
            addData(obj, y, chartname, hc, width, hight, id);


            /////modify for legend //////
            if (y >= 2) {
                ModyfyLegend(hc, obj);
            }
            
            // If there has any suplyed HC specific config.
            if (typeof jsConf[id] === 'object') {
                hc = margeClone(hc, jsConf[id]);
            }
            
            //if marimeko then calculate the marimeko
            if (chartname == 'Marimekko') {
                hc.plotOptions.series.pointPadding = 0;
                hc.plotOptions.series.groupPadding = 0;
                hc.plotOptions.series.shadow = false;
                hc.plotOptions.series.dataLabels.y = 12;

                hc.tooltip.formatter = function () {
                    var x = '', charSep = obj.chart.tooltipsepchar;
                    
                    x += this.series.name !== ' ' ? this.series.name + charSep : '';
                    x += this.x ? this.x + charSep : '';

                    if (obj.chart.usepercentdistribution != '0') {
                        x += (parseInt(this.percentage * 100, 10) / 100) + '%';
                    }
                    else {
                        x += formatNumber(this.y, obj.chart, 1);
                    }
                    
                    return parseStr(x);
                };

                if (obj.chart.usepercentdistribution == '0') {
                    hc.plotOptions.series.stacking = 'normal';
                }
                else {
                    hc.plotOptions.series.stacking = 'percent';
                    hc.yAxis[0].labels.formatter = function () {
                        return parseStr(this.value + '%');
                    };
                }
                
                convertMarimeko(hc, obj, width, hight, obj.categories);
            }

            ////Finaly Set the Plot and Background color[must be modifyed al last as margins may be changed any where]
            hc.chart.backgroundColor = CreateColor(0, 0, width, hight ? hight:400, obj.chart.bgangle !== undefined ? obj.chart.bgangle: 270, obj.chart.bgcolor ? obj.chart.bgcolor:"CBCBCB,E9E9E9", obj.chart.bgalpha ? obj.chart.bgalpha:"50,50", obj.chart.bgratio ? obj.chart.bgratio:"0,100");
            var plotX, plotY;
            if ('\v' === 'v') {
                plotX = 0;
                plotY = 0;
            } else {
                plotX = hc.chart.margin[3];
                plotY = hc.chart.margin[0];
            }
            hc.chart.plotBackgroundColor = (convertNames(chartname) == 'pie') ? 'rgba(255,255,255,0)': (CreateColor(plotX, plotY, width - (hc.chart.margin[1] + hc.chart.margin[3]), (hight ? hight:400) - (hc.chart.margin[0] + hc.chart.margin[2]), obj.chart.canvasbgangle !== undefined ? obj.chart.canvasbgangle: 0, obj.chart.canvasbgcolor ? obj.chart.canvasbgcolor:"FFFFFF", obj.chart.canvasbgalpha ? obj.chart.canvasbgalpha:"100", obj.chart.canvasbgratio ? obj.chart.canvasbgratio: ""));

            //return the converted object
            return hc;
        }

        // flag notifying default data format
        FCC.dataFormat = 'json';

        //function to render the charts
        FCC.render = function (__container, callBack) {
            
            var HCJson, sender = this, events = {
                loaded: 'FC_Loaded',
                dataloaded: 'FC_DataLoaded',
                rendered: 'FC_Rendered',
                drawcomplete: 'FC_DrawComplete'
            };
            
            if (FCC.isReady()) {
                //create the HC json
                HCJson = createHCJson.call(this, __container.id, true);
                //set the onload event
                HCJson.chart.events.load = function () {

                    setTimeout(function () {
                        for (var item in events) {
                            global.raiseEvent(item, {}, sender);
                            try {
                                if (typeof window[events[item]] === 'function') {
                                    window[events[item]](sender.id);
                                }
                            }
                            catch (err) {
                                setTimeout(function () {
                                    throw err;
                                }, 0);
                            }
                        }
                    }, 0);
                };
                //finaly call the create chart
                createChart.call(this, callBack, HCJson);
            }
            else {// if FCCReady return false then save the current render status in local obj which will be called after FCCReady automaticaly
                renderArray.push([this, __container, callBack]);
            }
        };


      
        // updater function
        //don't delete the vars it will change the isResize position
        FCC.update = function (vars, isResize) {
            var id = this.id, __containerId = this.options.containerElementId,
            sender = this, HCJson, events = {
                dataloaded: 'FC_DataLoaded',
                drawcomplete: 'FC_DrawComplete'
            };

            // check whether chart already rendered and update the data
            if (typeof __containerId !== 'undefined' && typeof FCC.items[id] !== 'undefined') {
                //delete all chart message
                if (!isResize) {
                    delete chartMessageStore[id].msgTxt;
                    if (this.jsVars) {
                        delete this.jsVars.stallLoad;
                        delete this.jsVars.LoadError;
                    }
                }
                //create the HC json
                HCJson = createHCJson.call(this, __containerId);
                //if rexizeTo called then stop animation
                if (isResize) {
                    HCJson.plotOptions.series.animation = false;
                }
                //set the onload event
                HCJson.chart.events.load = function () {
                    setTimeout(function () {
                        for (var item in events) {
                            global.raiseEvent(item, {}, sender);
                            try {
                                if (typeof window[events[item]] === 'function') {
                                    window[events[item]](sender.id);
                                }
                            }
                            catch (err) {
                                setTimeout(function () {
                                    throw err;
                                }, 0);
                            }
                        }
                    }, 0);
                };

                //render the new chart
                createChart.call(this, undefined, HCJson);
            }
        };

        FCC.resize = function () {
            FCC.update.call(this, undefined, true);
        };

        // Messege configuration
        var chartMessage = function () {};
        chartMessage.prototype = {
            "LoadDataErrorText": 'Error in loading data.',
            "XMLLoadingText": 'Retrieving Data. Please Wait',
            "InvalidXMLText": 'Invalid data.',
            "NoDataText": 'No data to display.',
            "ReadingDataText": 'Reading Data. Please Wait',
            "ChartNotSupported": 'Chart Has No Javascript FallBack'
        };
        chartMessage.prototype.constructor = chartMessage;

        global.addEventListener('Disposed', function (e) {
            delete chartMessageStore[e.sender.id];
        });
        global.addEventListener('BeforeInitialize', function (e) {
            chartMessageStore[e.sender.id] = new chartMessage();
        });

        FCC.config = function (items) {
            global.extend(chartMessageStore[this.id], items);
        };

        
        //add EventListner
        global.addEventListener('DataLoadRequested', function (event) {

            // Reference to event sender.
            var obj = event.sender;

            // Process these events only for objects that have javascript renderer!
            if (obj.options.renderer !== 'javascript') {
                return;
            }

            
            //if jsVars is not present create a blank obj
            if (!obj.jsVars) {
                obj.jsVars = {};
            }

            // In case we have an active chart, we show the loading
            // message in chart itself.
            if (obj.ref && typeof obj.ref.showChartMessage === 'function') {
                obj.jsVars.stallLoad = true;
                obj.ref.showChartMessage(chartMessageStore[obj.id].XMLLoadingText);
            }
            // In case chart object is not available, we set a flashvar saying that
            // loading is to be stalled.
            else {
                obj.jsVars.stallLoad = true;
            }

        });

        global.addEventListener('DataLoadRequestCompleted', function (event, args) {
            // Reference to event sender.
            var obj = event.sender;

            // Process these events only for objects that have flash renderer!
            if (obj.options.renderer !== 'javascript') {
                return;
            }

            // Clear load prevention flag.
            //if jsVars is not present create a blank obj
            if (obj.jsVars) {
                delete  obj.jsVars.stallLoad;
                delete  obj.jsVars.LoadError;
            }


        });

      
        global.addEventListener('DataLoadError', function (event, args) {

            // Reference to event sender.
            var obj = event.sender;

            // Process these events only for objects that have flash renderer!
            if (obj.options.renderer !== 'javascript') {
                return;
            }
            //if jsVars is not present create a blank obj
            if (!obj.jsVars) {
                obj.jsVars = {};
            }
            // On data load error, one needs to display "No Data To Display" on
            // charts.
            if (obj.ref) {
                obj.jsVars.LoadError = true;
                obj.ref.showChartMessage(chartMessageStore[obj.id].LoadDataErrorText);
            }
            else {
                obj.jsVars.LoadError = true;
            }

        });

        // Add method to make sure to delete all fusioncharts objects when
        // dispose method is invoked
        global.addEventListener('BeforeDispose', function (e) {
            // Process these events only for objects that have javascript renderer!
            if (e.sender.options.renderer !== 'javascript') {
                return;
            }
            // Managed removal of chart using swfObject library
            removeChart(e.sender.id, true);
        });

    };


    // Add the renderer to FusionCharts core repository
    global.renderer.register('javascript', FCC);

    // Register the swf renderer in case we have iPad or iPhone.
    if (/\(iPhone;|\(iPod;|\(iPad;/i.test(navigator.userAgent)) {
        // Set default renderer
        global.renderer.setDefault('javascript');
    }

    // Provide API for auto fall-back to JS chart when no flash is installed.
    global.extend({
        '_fallbackJSChartWhenNoFlash': function () {
            // Check flash version.
            if (!global.swfobject.hasFlashPlayerVersion(global.core.options.requiredFlashPlayerVersion)) {
                // If flash version is missing, we fallback to javascript
                global.renderer.setDefault('javascript');
            }
        }
    });

}());






/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global FusionCharts */

/*members addDataHandler, data, decode, encode, error
*/

/**
 * -----------------------------------------------------------------------------
 * XML Data Handler Stub Module
 * -----------------------------------------------------------------------------
 * This module contains the XML transcoder. This module adds the 'XML' data
 * handler.
 */
(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'XMLDataHandler']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    /**
     * Function to convert a variable into a FusionCharts data-handler API
     * compatible object.
     *
     * @param {variant} data can be anything.
     * @type object
     */
    var stubCoder = function (data) {
        // We do not need to normalize the data while encoding as because
        // the data is saved in JS scope and is now not sent via flashVars.
        return {
            data: data,
            error: undefined
        };
    };
    
    // Add Abstract data handler as because when the primary data-type is XML,
    // it requires a transparent transcoder that has nothing to do.
    global.addDataHandler('XML', {
        encode: stubCoder,
        decode: stubCoder
    });

}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
    eqeqeq: true, plusplus: true, bitwise: true, immed: true */

/*global Array: false, FusionCharts, RegExp: false, window: false */

/*members DOMParser, addDataHandler, addEventListener, append,
    application, apply, arr, async, attr, attributes, axis, categories,
    category, charAt, chart, child, childNodes, compactdatamode, core, data,
    dataset, decode, definition, encode, error, errorObject, explode, graph,
    group, id, ins, items, length, line, linkedchart, linkeddata,
    linkedgraph, loadXML, map, nodeName, nodeType, nodeValue, parse,
    parseFromString, push, qualify, replace, sender, set, slice, style,
    styles, tag, test, text, toLowerCase, toString, trendlines, vline,
    vtrendlines, extend
*/

/**
 * -----------------------------------------------------------------------------
 * JSON Data-Handler Module
 * This module performs the routines involved to transcode FusionCharts XML and
 * JSON. The module depends upon json2.js component from json.org and the same
 * has been included
 * -----------------------------------------------------------------------------
 */

// Include JSON parser.
if (!this.JSON) {
    this.JSON = {};
}
(function(){
    function f(n){
        return n<10?'0'+n:n
    }
    if(typeof Date.prototype.toJSON!=='function'){
        Date.prototype.toJSON=function(a){
            return isFinite(this.valueOf())?this.getUTCFullYear()+'-'+f(this.getUTCMonth()+1)+'-'+f(this.getUTCDate())+'T'+f(this.getUTCHours())+':'+f(this.getUTCMinutes())+':'+f(this.getUTCSeconds())+'Z':null
        };

        String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(a){
            return this.valueOf()
        }
    }
    var e=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={
        '\b':'\\b',
        '\t':'\\t',
        '\n':'\\n',
        '\f':'\\f',
        '\r':'\\r',
        '"':'\\"',
        '\\':'\\\\'
    },rep;
    function quote(b){
        escapable.lastIndex=0;
        return escapable.test(b)?'"'+b.replace(escapable,function(a){
            var c=meta[a];
            return typeof c==='string'?c:'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4)
        })+'"':'"'+b+'"'
    }
    function str(a,b){
        var i,k,v,length,mind=gap,partial,value=b[a];
        if(value&&typeof value==='object'&&typeof value.toJSON==='function'){
            value=value.toJSON(a)
        }
        if(typeof rep==='function'){
            value=rep.call(b,a,value)
        }
        switch(typeof value){
            case'string':
                return quote(value);
            case'number':
                return isFinite(value)?String(value):'null';
            case'boolean':case'null':
                return String(value);
            case'object':
                if(!value){
                    return'null'
                }
                gap+=indent;
                partial=[];
                if(Object.prototype.toString.apply(value)==='[object Array]'){
                    length=value.length;
                    for(i=0;i<length;i+=1){
                        partial[i]=str(i,value)||'null'
                    }
                    v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';
                    gap=mind;
                    return v
                }
                if(rep&&typeof rep==='object'){
                    length=rep.length;
                    for(i=0;i<length;i+=1){
                        k=rep[i];
                        if(typeof k==='string'){
                            v=str(k,value);
                            if(v){
                                partial.push(quote(k)+(gap?': ':':')+v)
                            }
                        }
                    }
                }else{
                    for(k in value){
                        if(Object.hasOwnProperty.call(value,k)){
                            v=str(k,value);
                            if(v){
                                partial.push(quote(k)+(gap?': ':':')+v)
                            }
                        }
                    }
                }
                v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';
                gap=mind;
                return v
        }
    }
    if(typeof JSON.stringify!=='function'){
        JSON.stringify=function(a,b,c){
            var i;
            gap='';
            indent='';
            if(typeof c==='number'){
                for(i=0;i<c;i+=1){
                    indent+=' '
                }
            }else if(typeof c==='string'){
                indent=c
            }
            rep=b;
            if(b&&typeof b!=='function'&&(typeof b!=='object'||typeof b.length!=='number')){
                throw new Error('JSON.stringify');
            }
            return str('',{
                '':a
            })
        }
    }
    if(typeof JSON.parse!=='function'){
        JSON.parse=function(c,d){
            var j;
            function walk(a,b){
                var k,v,value=a[b];
                if(value&&typeof value==='object'){
                    for(k in value){
                        if(Object.hasOwnProperty.call(value,k)){
                            v=walk(value,k);
                            if(v!==undefined){
                                value[k]=v
                            }else{
                                delete value[k]
                            }
                        }
                    }
                }
                return d.call(a,b,value)
            }
            c=String(c);
            e.lastIndex=0;
            if(e.test(c)){
                c=c.replace(e,function(a){
                    return'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4)
                })
            }
            if(/^[\],:{}\s]*$/.test(c.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){
                j=eval('('+c+')');
                return typeof d==='function'?walk({
                    '':j
                },''):j
            }
            throw new SyntaxError('JSON.parse');
        }
    }
}());

/**
 * Trims a long string at lightning fast speed of less than an ms!
 * @param {string} str is the string to be trimmed.
 * @type string
 */
var fastTrim = function (str) {
    str = str.replace(/^\s\s*/, '');
    var ws = /\s/, i = str.length;
    while (ws.test(str.charAt(i -= 1))) {}
    return str.slice(0, i + 1);
};

(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'JSON_DataHandler']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    var XSSEncode = function (s) {
        if (s === null || s === undefined || typeof s.toString !== 'function') {
            return '';
        }

        // do we convert to numerical or html entity?
        s = s.toString()
            .replace(/&/g, '&amp;')
            .replace(/\'/g, '&#39;') //no HTML equivalent as &apos is not cross browser supported
            .replace(/\"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        return s;
    };

    var xml2json = (function () {

        var rules = {
            /**
             * @var {object} arr contains the nodeNames that are marked to be
             * expanded as an array.
             */
            arr: {
                set: true,
                trendlines: true,
                vtrendlines: true,
                line: true,
                data: true,
                dataset: true,
                categories: true,
                category: true,
                //styles: true, // uncomment this to disable support for compact style syntax.
                linkeddata: true,
                application: true,
                definition: true,
                axis: true
            },

            /**
             * @var {object} tag contains nodeNames that are to be transformed
             * to a different nodeName as specified within this rule's meta.
             */
            tag: {
                chart: 'linkedchart',
                map: 'linkedmap',
                graph: 'linkedgraph',
                set: 'data',
                vline: {
                    chart: 'data',
                    graph: 'data',
                    dataset: 'data',
                    categories: 'category',
                    linkedchart: 'data',
                    linkedgraph: 'data'
                },
                apply: { 
                    application: 'application'
                },
                style: {
                    definition: 'definition'
                }
            },

            /**
             * @var {object} attr is the rule that defines the default
             * JSON variables to add in case a particular XML nodeName is found
             */
            attr: {
                vline: {
                    vline: 'true'
                }
            },

            /**
             * @var {object} ins contains nodeNames, that are treated as source
             * of attributes for a grand-child with same name as that of the
             * child.
             */
            ins: {
                chart: true,
                map: true,
                graph: true
            },

            /**
             * @var {object} text contains nodeNames that are specially expected
             * to be textnodes. Useful for "compactDataMode".
             */
            text: {
                dataset: 'data',
                categories: 'category'
            },

            /**
             * @var {object} group specifies which are the tags that are to
             * be promoted/inserted into its parent node (as in rule meta).
             */
            group: {
                styles: {
                    definition: true,
                    application: true
                }
            }
        };

        /**
         * @const {number} XML_CHILDNODE
         * @const {number} XML_TEXTNODE
         */
        var XML_CHILDNODE = 1, XML_TEXTNODE = 3;

        var parse = {
            append: function (childObj, obj, nodeName) {
                // Before we append the childNode returned from the
                // previous recursion, we need to decide whether to
                // simply put that object with the nodeName as key, or
                // whether there is a qualification of 'arr' rule, by
                // which we push the data onto parent array.
                if (rules.arr[nodeName] === true) {
                    if (!(obj[nodeName] instanceof Array)) {
                        obj[nodeName] = [];
                    }
                    obj[nodeName].push(childObj);
                }
                else {
                    obj[nodeName] = childObj;
                }
            },
            
            child: function (obj, childNode, parentNodeName, baseObj) {
                var i, nodeName, childObj, temp;

                // Iterate through the children and parse it depending upon its
                // nodeType
                for (i = 0; i < childNode.length; i += 1) {

                    // Desensitize the case of the nodeName
                    nodeName = childNode[i].nodeName.toLowerCase();

                    
                    // When the child object is a child node, we need to recurse
                    // onto it and also separately parse its attributes.
                    if (childNode[i].nodeType === XML_CHILDNODE) {

                        // Parse the attributes of the XML Node.
                        childObj = parse.attr(childNode[i].attributes);
                        
                        if (rules.ins[nodeName] === true) {
                            // In case 'ins' rule is matched, we transfer the
                            // parsed attributes to a grand-child having the node
                            // name of child and rename the child to the new name
                            // specified in the meta of 'tag' rule.
                            temp = childObj;
                            childObj = {};
                            childObj[nodeName] = temp;
                            temp = undefined;
                        }

                        // Apply the "attr" rule to add defalt flag variables.
                        if (typeof rules.attr[nodeName] === 'object') {
                            global.extend(childObj, rules.attr[nodeName]);
                        }

                        // Apply nodeName transformation 'tag' rule with parent
                        // child relationship.
                        if (typeof rules.tag[nodeName] === 'object' &&
                            typeof rules.tag[nodeName][parentNodeName] === 'string') {
                            nodeName = rules.tag[nodeName][parentNodeName];

                        }
                        
                        // Apply nodeName transformation 'tag' rule with parent
                        // independent relationship.
                        if (typeof rules.tag[nodeName] === 'string') {
                            nodeName = rules.tag[nodeName];
                        }

                        // We now need to parse the rest of the childnodes as
                        // recursed into this function.
                        if (childNode[i].childNodes.length) {
                            // Match the group rule. To check whether we need to append
                            // the parsed children or treat the parsed children as siblings.
                            if (rules.group[parentNodeName] && rules.group[parentNodeName][nodeName]) {
                                parse.child(obj, childNode[i].childNodes,
                                nodeName, baseObj);
                            }
                            else {
                                parse.child(childObj, childNode[i].childNodes,
                                    nodeName, baseObj);
                            }
                        }

                        // Append the computed childObject to parent depending
                        // upon whether it has to be appended to an array or as
                        // a child object.
                        // Note: We append only when the "group" rule was not matched
                        if (!(rules.group[parentNodeName] && rules.group[parentNodeName][nodeName])) {
                            parse.append(childObj, obj, nodeName);
                        }

                    }
                    // In case the child object is a text node and meets some
                    // other requirements, we parse it as textNode
                    else if (childNode[i].nodeType === XML_TEXTNODE && 
                        baseObj.chart && parseInt(baseObj.chart.compactdatamode, 10) &&
                        typeof rules.text[parentNodeName] === 'string') {
                        
                        // Create text node
                        nodeName = rules.text[parentNodeName];
                        childObj = childNode[i].data;
                        
                        // Append the computed childObject to parent depending
                        // upon whether it has to be appended to an array or as
                        // a child object.
                        parse.append(childObj, obj, nodeName);
                    }
                }
            },

            attr: function (attrObj) {
                var i, obj = {};
                // Check whether a valid xml attr NamedNode is passed.
                if (!attrObj || !attrObj.length) {
                    return obj;
                }
                // Iterate through the attribute list and populate the return
                // object with the nodeValues.
                for (i = 0; i < attrObj.length; i += 1) {
                    obj[attrObj[i].nodeName.toLowerCase()] = attrObj[i].nodeValue;
                }

                // Finally return the converted object.
                return obj;
            }

        };

        var parser = function (xml) {

            var jsonObj = {}, xmlDoc, root, rootName;

            // Validate parameters to check that xml can be converted into a string.
            if (xml === undefined || xml === null || typeof xml.toString !== 'function') {
                parser.errorObject = new TypeError('xml2json.parse()');
                return jsonObj;
            }

            xml = xml.toString()
                .replace(/<\!--[\s\S]*?-->/g, '') // remove xml comments
                .replace(/<\?xml[\s\S]*?\?>/ig, '') // remove xml definition
                //.replace(/\<\!\[cdata[\s\S]*?\]\]\>/ig, '') // remove CDATA
                //.replace(/(=\s*?\"[\s\S]*?\")(\w)/ig, '$1 $2') // fix whitespace attr with quot
                //.replace(/(=\s*?\'[\s\S]*?\')(\w)/ig, '$1 $2') // fix whitespace attr with apos
                .replace(/&(?!([^;\n\r]+?;))/g, '&amp;$1'); // fix ampersand
            xml = fastTrim(xml);

            // Check whether unwanted data like undefined, null blank string etc.
            if (!xml) {
                //parser.errorObject = new TypeError('xml2json.parse()');
                return jsonObj;
            }
            // Get XML Parser object depending upon browser capability and
            // subsequently load xml string.
            if (window.DOMParser) {
                xmlDoc = (new window.DOMParser()).parseFromString(xml, "text/xml");
            }
            else { // Internet Explorer
                xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async = "false";
                xmlDoc.loadXML(xml);
            }

            if (!(xmlDoc.childNodes.length === 1 && (root = xmlDoc.childNodes[0]) &&
                root.nodeName && (rootName = root.nodeName.toLowerCase()) &&
                (rootName === 'chart' || rootName === 'map' || rootName === 'graph'))) {
                parser.errorObject = new TypeError('xml2json.parse()');
                return jsonObj;
            }
            
            // Do initial attribute parsing
            jsonObj[rootName] = parse.attr(root.attributes);

            // Parse all childNodes.
            if (root.childNodes) {
                parse.child(jsonObj, root.childNodes, rootName, jsonObj);
            }

            // Delete error flag.
            delete parser.errorObject;
            return jsonObj;
        };

        return function (xml) {
            // Clear error flags of parser.
            delete parser.errorObject;

            // Call JSON2XML parser to retrieve the parsed data.
            var jsonData = parser(xml);

            // Compile a return object for encoding function.
            return {
                data: jsonData,
                error: parser.errorObject
            };
        };
    }());

    /**
     * This function accepts a JSON string or object and converts it to
     * FusionCharts data XML.
     * @type string
     *
     */
    var json2xml = (function () {

        /**
         * This function verifies whether an
         */
        var rules = {

            /**
             * @var {object} items Collection of rules that are required to convert
             * JSON to FusionCharts XML.
             *
             * @note The rules are in the form:
             *       ruleType: { namespace: { nodeName: ruleMeta } }
             */
            items: {
                /**
                 * @var {object} explode Comprises of JSON attributes that needs to
                 * be converted to a particular set of nodes. Generally the value of
                 * such attributes/properties are an array of objects. Each object
                 * in these arrays are converted to a particular XML node specified
                 * within the rule meta.
                 */
                explode: {
                    data: 'set'
                },

                /**
                 * @var {object} attr Comprises of JSON attributes whose values are
                 * always added to the XML attributes of its namespace. Such nodes
                 * generally are object containing strings. The keys within them
                 * are to be converted to XML attributes and the values become XML
                 * attribute values.
                 *
                 */
                attr: {
                    chart: {
                        chart: 'chart',
                        graph: 'chart'
                    },
                    graph: {
                        graph: 'graph',
                        chart: 'graph'
                    },
                    map: {
                        map: 'map'
                    },
                    linkedchart : {
                        chart: 'chart',
                        graph: 'graph',
                        map: 'map'
                    }

                },

                /**
                 * @var {object} group Comprises of JSON attributes whose children
                 * are to be grouped under a particular node. The parentNode where
                 * it has to be grouped are specified in the rule meta.
                 */
                group: {
                    styles: {
                        definition: 'style',
                        application: 'apply'
                    }
                }
            },

            /**
             * This method verifies whether a name-item pair qualifies for a
             * rule or not. If so, it also returns the meta information of the
             * qualified rule.
             * @type string
             *
             * @param {string} rule is the name of the rule that you want to
             * verify. There must be a corresponding rule group in the items
             * object.
             * @param {variant} item
             * @param {variant} namespace
             */
            qualify: function (rule, item, namespace) {
                namespace = namespace.toLowerCase();

                return typeof this.items[rule][namespace] === 'object' ?
                    this.items[rule][namespace][item.toLowerCase()] :
                    this.items[rule][namespace];
            }
        };

        /**
         * This function accepts a JSON object and converts it to FusionCharts
         * data xml.
         *
         * @param obj {object} JSON object to be parsed.
         * @param namespace {string} is the parent/root namespace within which
         * the JSON object is contained.
         */
        var parser = function (obj, namespace) {

            // Initialize variables that stores the current namespace's XML
            // construction parameters.
            /**
             * @var {string} attrString The serialized set of attributes key:value
             *      pairs generated for this namespace.
             * @var {string} innerXML The innerXML of the node generated in this NS.
             * @var {string} nodeName The current node name devised from the NS.
             * @var {string} outerXML The outer XML generated for this JSON tree.
             * @var {string} item The current child being processed within an NS.
             * @var {string} lcaseItem The case desensitized current node item.
             * @var {string} lcaseNS The case desensitized namespace.
             */
            var attrString = '', innerXML = '', nodeName = '', outerXML = '',
            item, qualifier;

            // In case the object is an array, apply explosion rule.
            if (obj instanceof Array) {
                // Iterate through every item within the array and expand it.
                for (item = 0; item < obj.length; item += 1) {
                    // If the value of the node is string we expand its contents
                    // into the content of the node for COMPACT_MODE data
                    if (typeof obj[item] === 'string') {
                        outerXML += XSSEncode(obj[item]);
                    }
                    // If the node is not string, we process its contents as
                    // another node.
                    else {
                        outerXML += parser(obj[item], namespace);
                    }
                }
            }
            // For any other data-type other than Array we iterate through the
            // contents of the variable and parse each node.
            else {
                for (item in obj) {
                    
                    // Parse "group" rule. Here, we test whether we are to group
                    // a JSON Array under a particular XML node.
                    // @example where this rule matches x for y:
                    // x: [a, b, c] is <y><x a /><x b /><x c/></y>
                    if (obj[item] instanceof Array &&
                        (qualifier = rules.qualify('group', item, namespace))) {
                        innerXML += '<' + item + '>' + parser(obj[item],
                            qualifier) + '</' + item + '>';
                    }

                    // Parse "attr" rule. Here we test whether to use the items
                    // within a particular item as the XML attributes of the
                    // parent namespace.
                    else if (typeof obj[item] === 'object') {

                        // Check whether there is a qualifying rule for using
                        // an OBJECT as source of attributes of a namespace.
                        if ((qualifier = rules.qualify('attr', item, namespace))) {
                            nodeName = parser(obj[item], qualifier)
                                .replace(/\/\>/ig, '');
                            namespace = item;
                        }
                        // Otherwise, recurse the parser to process the object
                        // as a child JSON object.
                        else {
                            innerXML += parser(obj[item], item);
                        }
                    }
                    // Parse "vLine" and other static rules.
                    else {
                        if (item.toLowerCase() === 'vline' && Boolean(obj[item])) {
                            namespace = 'vLine';
                        }
                        else {
                            attrString += ' ' + item + '=\"' + XSSEncode(obj[item])
                            .toString().replace(/\"/ig, '&quot;') + '\"';
                        }
                    }
                }

                // When parsing completes, we need to check whether we have any
                // namespace adjustments or not.
                // Explode rule.
                if ((qualifier = rules.qualify('explode', item, namespace))) {
                    namespace = qualifier;
                }

                // Build the final XML node string.
                outerXML = (nodeName !== '' ? nodeName : '<' + namespace) +
                attrString + (innerXML !== '' ? '>' + innerXML + '</' +
                    namespace + '>' : ' />');
            }
            return outerXML;
        };

        return function (jsonData) {
            // Clear error flags of parser.
            delete parser.errorObject;

            // In case user sends the JSON data as 'string', we need to parse
            // it and convert to JSON object.
            if (jsonData && typeof jsonData === 'string') {
                // Parse the data within atry block in order to receive all
                // errors.
                try {
                    jsonData = JSON.parse(jsonData);
                }
                catch (e) {
                    parser.errorObject = e;
                }
            }

            // Call JSON2XML parser to retrieve the parsed data.
            var xmlData = parser(jsonData, jsonData && jsonData.graph ? 'graph' : 'chart');

            // Compile a return object for encoding function.
            return {
                data: xmlData,
                error: parser.errorObject
            };
        };
    }());

    // Add the data-handler to FusionCharts collection of data-handlers
    global.addDataHandler('JSON', {
        encode: function (data) {
            return json2xml(data);
        },

        decode: function (data) {
            return xml2json(data);
        }
    });

}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, immed: true */

/*global window: false, Array: false, FusionCharts: false, FusionChartsEvents: false.
    FusionChartsDataFormats: false */

/*members BeforeLinkedItemClose, BeforeLinkedItemOpen, LinkedItemClosed,
    LinkedItemOpened, addEventListener, animate, args, clone, configuration,
    configureLink, containerElement, core, data, dataFormat, dataSource,
    dispose, drawOverlayButton, extend, id, insertMode, isActive, item,
    items, length, level, link, linkType, options, overlayButton, parent,
    policies, prototype, raiseError, raiseEvent, raiseWarning, ref, render,
    root, sender, show, splice, uniqueId
*/

/* -----------------------------------------------------------------------------
 * Link Manager Module
 * -----------------------------------------------------------------------------
 * This module allows for easy drill-down of charts by handling the link 
 * attrribute of charts.
 */
(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'LinkManager']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }

    // Add parameter policy to pass link information during construction of
    // new FusionCharts object.
    global.policies.link = ['link', undefined];

    // Create a collection to store configuration of every root link.
    var store = {};

    // Store root and parent reference of every element. This would allow us to
    // save the parent and root reference of the "link" object of every chart.
    var LinkInformation = function (root, parent) {
        this.items = {};
        this.root = root;
        this.parent = parent;
        
        // Do initialization work in case this is the root link. We verify root
        // link in case parent is undefined
        if (parent instanceof global.core) {
            this.level = this.parent.link.level + 1;
        }
        // Parent is not an instance of FusionCharts, this implies this link is
        // a root link
        else {
            store[root.id] = [{}];
            this.level = 0;
        }
    };

    // This function would return the current configuration of the link that is
    // to be used for construction of a new chart.
    LinkInformation.prototype.configuration = function () {
        var param = store[this.root.id][this.level] || 
            (store[this.root.id][this.level] = {});
        // Check whether the above parameter provides a chart-id, if not then
        // generate one.
        if (typeof param.id === 'undefined') {
            param.id = store[this.root.id][this.level].id =
                global.uniqueId();
        }
        // Return the parameters
        return param;
    };

    // Add global link configuration API using which users will be able to set
    // parameters for every level of link.
    global.extend({
        configureLink: function (param, level) {
            // In case user provides an array of configuration, we assume that the
            // user wants to redefine the entire configuration train.
            if (param instanceof Array) {
                for (var i = 0; i < param.length; i += 1) {
                    // We initialize a blank configuration object for
                    // the link configuration train, in case it is not
                    // pre-defined.
                    if (typeof store[this.link.root.id][i] !== 'object') {
                        store[this.link.root.id][i] = {};
                    }
                    // The configuration is one-by-one copied two the store.
                    global.extend(store[this.link.root.id][i], param[i]);
                }
                // Delete any extra configuration.
                store[this.link.root.id].splice(param.length);
            }
            // If user has sent one object, we assume he wants to configure
            else if (typeof param === 'object') {
                // In case level is undefined, we need to assign the current
                // level of the object.
                if (typeof level !== 'number') {
                    level = this.link.level;
                }
                // Create a blank parameter object in store in case it is not
                // defined.
                if (store[this.link.root.id][level] === undefined) {
                    store[this.link.root.id][level] = {};
                }
		// Copy all parameters passed on to the store.
                global.extend(store[this.link.root.id][level], param);
            }
            else {
                global.raiseError(this, '25081731', 'param', '~configureLink()',
                    'Unable to update link configuration from set parameters');
            }
        }
    }, true);


    // Add construction routines to manage link parameters.
    global.addEventListener('BeforeInitialize', function (event) {
	
        // If LinkInformation is not present in the object, we can assume
        // that this chart is a root chart and hence we need to create
        // link related information.
        if (!(event.sender.link instanceof LinkInformation)) {
            event.sender.link = new LinkInformation(event.sender);
        }
        // In case link is predefined, we need to add the new object to the
        // 'items' collection of parent of the new object.
        else {
            // In case of root link, parent is undefined, we do not need to
            // add any type of item configuration.
            if (event.sender.link.parent instanceof global.core) {
                event.sender.link.parent.link.items[event.sender.id] = event.sender;
            }
        }
    });

    // Handle the linked-chart click event.
    global.addEventListener('LinkedChartInvoked', function (event, args) {
        var obj = event.sender, param = obj.clone({
            dataSource: args.data,
            dataFormat: args.linkType === 'URL' ? FusionChartsDataFormats.XMLURL : args.linkType,
            // Create a new link between the source chart and the to-be-created
            // new chart.
            link: new LinkInformation(obj.link.root, obj)
        }, true);

        
        // Delete certain default or post-render state related variables from
        // params.
        if (obj.args && parseInt(obj.args.animate, 10) !== 0) {
            delete param.animate;
        }
        // Update parameters by overrides set by user
        global.extend(param, obj.link.configuration());

        // Raise event to denote event that linked chart is going to be
        // rendererd.
        global.raiseEvent('BeforeLinkedItemOpen', {
            level: obj.link.level
        }, obj.link.root);

        // Delete the chart with same id, if there is one.
        if (global.core.items[param.id] instanceof global.core && param.strictLinkId === true) {
            global.core.items[param.id].dispose()
        }

        // Create a new FusionCharts object with the construction parameters of
        // the above link configuration. Also directly render the chart.
        var childObj = new global.core(param).render();

        // Raise event to denote that linked item was invoked on a chart.
        global.raiseEvent('LinkedItemOpened', {
            level: obj.link.level,
            item: childObj
        }, obj.link.root);
    });

    /**
     * This method handles the routines that are performed when a linked chart
     * is closed.
     */
    global.addEventListener('OverlayButtonClick', function (event) {
        
        var sender = event.sender,
            level = sender.link.level - 1,
            parent = sender.link.parent;

        // Raise event to denote event that linked chart is going to be disposed.
        global.raiseEvent('BeforeLinkedItemClose', {
            level: level,
            item: sender
        }, sender.link.root);

        // Dispose the item closed.
        sender.dispose();

        // Raise event that link has been closed for a root chart.
        global.raiseEvent('LinkedItemClosed', {
            level: level
        }, sender.link.root);

        // In case link item was closed for an object whose parent is not
        // active, we re-render it.
        if (!parent.isActive() && sender.options.containerElement ===
            parent.options.containerElement && sender.options.insertMode === 'replace') {
            parent.render();
        }


    });

    global.addEventListener('Loaded', function (event) {

        var obj = event.sender;
        // When Chart is rendered using HTML rendering "event.sender.link" is undefined.
        if (!obj || obj.link === undefined) {
            return;
        }
        // Verify whether the loaded item is a root item or not. In case the
        // item is a root item, we do not need to process an overlay button.
        if (obj.link.root === obj || !(obj.link.parent instanceof global.core)) {
            return;
        }

        // Verify whether overlay button API is available
        if (!(obj.ref && typeof obj.ref.drawOverlayButton === 'function')) {
            // Warn that it could not draw overlay button.
            global.raiseWarning(obj, '04091602', 'run', '::LinkManager^Loaded',
                'Unable to draw overlay button on object. -' + obj.id);
            return;
        }

        // Get configuration of overlay button
        var config = global.extend({
            show: true
        }, obj.link.parent.options.overlayButton);
        global.extend(config, obj.link.parent.link.configuration().overlayButton || {});
        obj.ref.drawOverlayButton(config);
    });

    // Add method to make sure to delete all fusioncharts objects when
    // dispose method is invoked.
    global.addEventListener('BeforeDispose', function (e) {
        var obj = e.sender;
        // Validate environment to check sender and its link exists.
        if (!(obj && obj.link instanceof LinkInformation)) {
            return;
        }

        // In case the object is not a root object, we would need to perform
        // additional cleanup.
        if (obj.link.parent instanceof global.core) {
            // Cleanup the reference to this object to the 'items' collection
            // of its parent.
            delete obj.link.parent.link.items[e.sender.id];
        }
        // Remove any configuration set as root configuration
        delete store[obj.id];
    });

    FusionChartsEvents.LinkedItemOpened = 'linkeditemopened';
    FusionChartsEvents.BeforeLinkedItemOpen = 'beforelinkeditemopen';
    FusionChartsEvents.LinkedItemClosed = 'linkeditemclosed';
    FusionChartsEvents.BeforeLinkedItemClose = 'beforelinkeditemclose';

}());



/*jslint white: true, browser: true, windows: true, forin: true,  undef: true,
  eqeqeq: true, plusplus: true, bitwise: true, regexp: true, immed: true */

/*global window: false, FusionCharts: false, FusionChartsEvents: false,
    G_vmlCanvasManager: false */

/* -----------------------------------------------------------------------------
 * Print Manager Module
 * -----------------------------------------------------------------------------
 */
(function () {

    // Register the module with FusionCharts.
    var global = FusionCharts(['private', 'PrintManager']);
    // Check whether the module has been already registered. If true, then
    // do not bother to re-register.
    if (global === undefined) {
        return;
    }
    
    // Default configuration of print manager. This can be overriden using
    // parameter of constructor.
    var config = {
        enabled: false,
        invokeCSS: true,
        processPollInterval: 2000,
        message: 'Chart is being prepared for print.',
        useExCanvas: false,
        bypass: false
    };

    // Contains all independent library functions to be used by various sections
    // of this code.
    var lib = {
        
        getCanvasElementOf: function (obj, width, height) {
            // Proceed with creating canvas only if it is not already created.
            if (obj.__fusioncharts__canvascreated !== true) {
                // Create <canvas> DOM element.
                var canvas = document.createElement('canvas'),
                    identifier = global.core.items[obj.id].attributes['class'];
                // ExCanvas initialization.
                if (config.useExCanvas && G_vmlCanvasManager) {
                    G_vmlCanvasManager.initElement(canvas);
                }

                // Set the class of the canvas to an identifyable value.
                canvas.setAttribute('class', identifier);
                canvas.__fusioncharts__reference = obj.id;

                // Insert the canvas immediately after the embed element.
                obj.parentNode.insertBefore(canvas, obj.nextSibling);
                // Mark that canvas has been created.
                obj.__fusioncharts__canvascreated = true;
            }

            // Set dimensions of canvas element
            obj.nextSibling.setAttribute('width', width || obj.offsetWidth || 2);
            obj.nextSibling.setAttribute('height', height || obj.offsetHeight || 2);

            // Return the canvas element for further manipulation.
            return obj.nextSibling;
        },

        removeCanvasElementOf: function (obj) {
            if (obj.__fusioncharts__canvascreated !== true || !obj.parentNode ||
                obj.parentNode === null) {
                return;
            }
            obj.parentNode.removeChild(obj.nextSibling);
            obj.__fusioncharts__canvascreated = false;
        },
        
        rle2rgba: function (rle, rgba, base) {

            // Check if base colour has been provided. If not then set it to
            // white.
            if (typeof base !== 'string') {
                base = "FFFFFF";
            }

            // Tokenize the incoming RLE stream data.
            var raw = rle.split(/[;,_]/), run, i, r, g, b, x = 0;
            // Process every token.
            for (i = 0; i < raw.length; i += 2) {
                // Replace missing colour with base colour.
                if (raw[i] === '') {
                    raw[i] = base;
                }

                // Padding maximum left of the colour-data by 0. This allows
                // easy string manipulation from the right.
                raw[i] = ('000000' + raw[i]).substr(-6);

                // Separate the colour components and convert decimal to hex.
                r = parseInt('0x' + raw[i].substring(0, 2), 16);
                g = parseInt('0x' + raw[i].substring(2, 4), 16);
                b = parseInt('0x' + raw[i].substring(4, 6), 16);

                // Fill the run-length with the extracted rgba data.
                for (run = 0; run < raw[i + 1]; run += 1) {
                    rgba[x] = r;
                    rgba[x + 1] = g;
                    rgba[x + 2] = b;
                    rgba[x + 3] = 255;
                    x += 4;
                }
            }
            return rgba;
        },
        
        rle2array: function (rle, base) {
            // Check if base colour has been provided. If not then set it to
            // white.
            if (typeof base !== 'string') {
                base = "FFFFFF";
            }

            // Tokenize the RLE stream.
            var raw = rle.split(';'), run, i;
            for (run in raw) {
                // Tokenize every run within the stream.
                raw[run] = raw[run].split(/[_,]/);
                for (i = 0; i < raw[run].length; i += 2) {
                    // Restore RLE sub-compression losses.
                    // As per processed value, update it with base colour or
                    // Left-pad zero.
                    raw[run][i] = raw[run][i] === '' ?
                        base : ('000000' + raw[run][i]).substr(-6);
                }
            }
            return raw;
        },

        drawRLE: function (canvas, rle, width, height, baseColor) {

            // Failsafe height and width parameters
            width = width || 2;
            height = height || 2;

            // Adjust canvas dimension
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);

            var context = canvas.getContext('2d'), imageData;

            // Prepare image data from rle sent from embed parameter.
            if (typeof context.putImageData === 'function' &&
                typeof context.createImageData === 'function') {
                imageData = context.createImageData(width, height);
                lib.rle2rgba(rle, imageData.data, baseColor);
                context.putImageData(imageData, 0, 0);
            }
            // Fall-back drawing method
            else {
                imageData = lib.rle2array(rle, baseColor);
                var x = 0, y = 0, z = 0;
                for (y in imageData) {
                    x = 0;
                    for (z = 0; z < imageData[y].length; z += 2) {
                        context.fillStyle = "#" + imageData[y][z];
                        context.fillRect(x, y, imageData[y][z + 1], 1);
                        x += parseInt(imageData[y][z + 1], 10);
                    }

                }
            }
            return true;
        },

        drawText: function (canvas, text, width, height) {
            var context = canvas.getContext('2d'), w = width || 2,
                h = height || 2;
            context.clearRect(0, 0, w, h);
            context.textBaseline = 'middle';
            context.textAlign = 'center';
            context.font = '8pt verdana';
            context.fillStyle = '#776666';

            if (typeof context.fillText === 'function') {
                context.fillText(text, w / 2, h / 2);
            }
            else if (typeof context.mozDrawText === 'function') {
                context.translate(w / 2, h / 2);
                context.mozDrawText(text);
            }
            else {
                global.raiseWarning(global.core, '25081803', 'run',
                    '::PrintManager>lib.drawText',
                    'Canvas text drawing is not supported in browser');
            }
            return true;
        },

        // Appends a given CSS Text to the page <head> element.
        appendCSS: function (css) {
            // Create a DOM style element and set its required attributes.
            var el = document.createElement('style');
            el.setAttribute('type', 'text/css');

            // Add the CSS passed as argument to the newly created style element.
            // For IE, use the 'stylesheet.cssText' property and for rest, simply
            // add the CSS as text to the style element.
            if (typeof el.styleSheet === 'undefined') {
                el.appendChild(document.createTextNode(css));
            } else {
                el.styleSheet.cssText = css;
            }

            // Append the style element to DOM head and return the same.
            return document.getElementsByTagName('head')[0].appendChild(el);
        }
    };

    // Manages the dynamically generated CSS of the page
    var css = {        
        // Available library styles.
        styles: {
            print: 'canvas.FusionCharts{display:none;}@media print{object.FusionCharts{display:none;}canvas.FusionCharts{display:block;}}',
            error: 'canvas.FusionCharts{display:none;}',
            normal: ''
        },

        // A common variable that stores a reference to the current stylesheet.
        cssNode: undefined,

        // Define function that allows switch between stylesheets.
        invoke: function (style) {
            // Check whether to use one of the available library styles.
            if (typeof this.styles[style] !== 'undefined') {
                style = this.styles[style];
            }
            // If style is not set as undefined, set the style.
            if (typeof style !== 'undefined') {
                if (this.cssNode !== undefined && this.cssNode.parentNode !== undefined) {
                    this.cssNode.parentNode.removeChild(this.cssNode);
                }
                css.cssNode = lib.appendCSS(style);
            }
        }
    }, activeItems = {}, queuedItems = {}, activeCount = 0, queueTrigger;

    var onDrawComplete = function (event) {

        // Get reference to the rendered object refetence
        var obj = event.sender.ref, w, h;

        // We just verify the external interface for safety sake. Though it is
        // very unlikely that this event will be fired with crippled EI.
        if (obj === undefined || typeof obj.prepareImageDataStream !== 'function' ||
            obj.prepareImageDataStream() === false) {
            // Request obj to prepare image data stream or queue it up in case SWF
            // is busy.
            queueTrigger(event.sender);
            return;
        }
        // Add the object to te collection of active objects post successful
        // imagestream preparation call.
        if (!activeItems[event.sender.id]) {
            activeItems[event.sender.id] = obj;
            activeCount += 1;
            if (activeCount === 1) {
                global.raiseEvent('PrintReadyStateChange', {
                    ready: false,
                    bypass: config.bypass
                }, event.sender);
            }
        }
        // While image is being prepared, render the "waiting" message.
        try {
            w = obj.offsetWidth;
            h = obj.offsetHeight;
            lib.drawText(lib.getCanvasElementOf(obj, w, h), config.message, w, h);
        }
        // In case of error, remove the CSS so that the SWF is still
        // printable.
        catch (e) {
            css.invoke('error'); // invoke the css that hides canvas
            global.raiseError(event.sender, '25081807', 'run',
                '::PrintManager>onDrawComplete',
                'There was an error while showing message to user via canvas.');
        }
    },

    onImageStreamReady = function (event, args) {
        try {
            if (lib.drawRLE(lib.getCanvasElementOf(event.sender.ref,
                args.width, args.height), args.stream, args.width, args.height,
                args.bgColor) === true) {
                // On successful canvas rendering, remove the item from active
                // item collection.
                if (activeItems[event.sender.id]) {
                    delete activeItems[event.sender.id];
                    activeCount -= 1;

                    if (activeCount === 0) {
                        global.raiseEvent('PrintReadyStateChange', {
                            ready: true,
                            bypass: config.bypass
                        }, event.sender);
                    }
                }
            }
        }
        // In case of error, remove the CSS so that the SWF is still
        // printable.
        catch (e) {
            css.invoke('error'); // invoke the css that hides canvas
            global.raiseError(event.sender, '25081810', 'run',
                '::PrintManager>onImageStreamReady',
                'There was an error while drawing canvas.');
        }
    },

    // Method to remove canvas and other print manager stuffs when an object is
    // disposed.
    onBeforeDispose = function (event) {
        lib.removeCanvasElementOf(event.sender.ref);
    },

    subscribeToEvents = function (state) {
        var eventAction = state ? 'addEventListener' :
                'removeEventListener';

        // Apply the event listener states to the eventHandlers.
        global.core[eventAction]('ImageStreamReady', onImageStreamReady);
        global.core[eventAction]('DrawComplete', onDrawComplete);
        global.core[eventAction]('BeforeDispose', onBeforeDispose);
    },

    initialize = function () {
        if (config.invokeCSS === true) {
            css.invoke('print');
        }
        // Iterate through all FusionCharts object reference and
        // do a fake call to onDrawComplete event
        for (var item in global.core.items) {
            queueTrigger(global.core.items[item]);
            queueTrigger();
        }

    },

    destroy = function () {
        // Hide any of the canvas element in case user disables
        // print manager.
        css.invoke('error');
        // Iterate through all FusionCharts object reference and remove
        // their canvases if present.
        for (var item in global.core.items) {
            if (global.core.items[item].ref === undefined) {
                continue;
            }
            lib.removeCanvasElementOf(global.core.items[item].ref);
        }
        // Finally remove all traces of CSS
        css.invoke('normal');
    };

    queueTrigger  = function (obj) {
        // In case the first argument is not undefined, it implies that an item
        // is to be queued up
        if (obj instanceof global.core) {
            queuedItems[obj.id] = obj;
            return;
        }

        // Proceed with processing the queue
        for (var item in queuedItems) {
            onDrawComplete({
                sender: queuedItems[item]
            }, {});
            delete queuedItems[item];
        }
    };

    global.extend({
        printManager: {
            // This method allows users to confiure and reconfigure the
            // configuration of this script.
            configure: function (configuration) {
                global.extend(config, configuration || {});
            },

            isReady: function () {

                // In case the printManager is bypassed, we always say that it
                // is ready. This keeps dependent scripts not to fail.
                if (config.bypass) {
                    return true;
                }

                // Check if there is any active job or not and whether printManager
                // is disabled. In either case, it is not ready.
                if (activeCount > 0 || !config.enabled) {
                    return false;
                }
                
                var item, ref;
                // Check each element to see whether it is rendered. In
                // case they are rendered, and there is no active jobs (as
                // checked earlier), it implies that all items are successfully
                // rendered.
                for (item in global.core.items) {
                    if ((ref = global.core.items[item].ref) === undefined) {
                        continue;
                    }
                    if (ref.hasRendered && ref.hasRendered() === false) {
                        return false;
                    }
                }
                return true;
            },

            // Enable or disable canvas print manager. In case browser is not
            // supported, do not allow to enable
            enabled: function (state) {
                // If no parameter is passed, it is assumed that user simply
                // needs the current status. So we return the current status.
                if (state === undefined) {
                    return config.enabled;
                }

                // Check browser capability to fulfill minimum requirements for
                // this script. Do not proceed if browser is IE
                if ('\v' === 'v' || global.renderer.currentRendererName() !== 'flash' ||
                    typeof document.createElement('canvas').getContext !== 'function') {
                    config.bypass = true;
                    global.raiseEvent('PrintReadyStateChange', {
                        ready: true,
                        bypass: config.bypass
                    });
                    global.raiseWarning(global.core, '25081816', 'run', '.printManager.enabled',
                        'printManager is not compatible with your browser');
                    return config.enabled;
                }
                config.bypass = false;
                
                // Apply the event listener states to the eventHandlers.
                subscribeToEvents(state);

                // Perform initialization or cleanup depending upon the state.
                if (state === true) {
                    initialize();
                }
                else {
                    destroy();
                }
                // Return the updated state value.
                return (config.enabled = state);
            },

            managedPrint: function () {

                // In case printManager is bypassed due to some reason, we directly
                // invoke the print function of browser.
                if (config.bypass) {
                    window.print();
                    return;
                }

                // In case managedPrint is called and PrintManager is not enabled,
                // we enable it and then wait to raise windows print function
                // as printreadystatechange returns true.
                if (!global.core.printManager.isReady()) {
                    // Enable Print Manager
                    if (global.core.printManager.enabled(true) !== true) {
                        // If enabling fails, then just invoke a print.
                        window.print();
                        return;
                    }
                    // Subscribe to the print ready state change event
                    global.addEventListener('PrintReadyStateChange',
                        global.core.printManager.managedPrint);

                    return;
                }

                // In case this function is invoked by event-handler, I check
                // when active is marked as true.
                if (typeof arguments[1] === 'object' && arguments[1].ready !== true) {
                    return;
                }

                // Remove the event that watches completion of canvas image creation
                global.removeEventListener('PrintReadyStateChange',
                    global.core.printManager.managedPrint);

                // Execute Printing.
                window.print();
            }
        }
    }, false);

    // Add the event names that this module raises to the events collection.
    FusionChartsEvents.PrintReadyStateChange = 'printreadystatechange';

}());



