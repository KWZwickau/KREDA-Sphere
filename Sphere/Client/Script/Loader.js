var Client = (function()
{
    var useDelay = 5;
    var useConfig = {};
    var setModule = function( Module, Depending )
    {
        useConfig[Module] = {
            Depending: Depending,
            Source: '/Sphere/Client/Script/' + Module + '.js',
            Test: function()
            {
                return 'undefined' != typeof jQuery.fn[Module];
            },
            isUsed: false,
            isLoaded: false,
            isReady: function( Callback )
            {
                var dependingModule;
                var dependingSize = this.Depending.length - 1;
                for (dependingSize; 0 <= dependingSize; dependingSize--) {
                    dependingModule = this.Depending[dependingSize];
                    if (!useConfig[dependingModule].Test()) {
                        loadModule( dependingModule );
                        return false;
                    }
                    else {
                        if (!useConfig[dependingModule].isReady()) {
                            loadModule( dependingModule );
                            return false;
                        }
                    }
                }
                if (this.Test()) {
                    this.isLoaded = true;
                    return true;
                } else {
                    if ('undefined' != typeof Callback) {
                        loadModule( Module, Callback );
                    }
                    return false;
                }
            }
        };
    };
    var setSource = function( Module, Source, Test )
    {
        defineSource( Module, [], Source, Test );
    };
    var defineSource = function( Module, Depending, Source, Test )
    {
        useConfig[Module] = {
            Depending: Depending,
            Source: Source,
            Test: Test,
            isUsed: false,
            isLoaded: false,
            isReady: function( Callback )
            {
                var dependingModule;
                var dependingSize = this.Depending.length - 1;
                for (dependingSize; 0 <= dependingSize; dependingSize--) {
                    dependingModule = this.Depending[dependingSize];
                    if (!useConfig[dependingModule].Test()) {
                        loadModule( dependingModule );
                        return false;
                    }
                    else {
                        if (!useConfig[dependingModule].isReady()) {
                            loadModule( dependingModule );
                            return false;
                        }
                    }
                }
                if (this.Test()) {
                    this.isLoaded = true;
                    return true;
                } else {
                    if ('undefined' != typeof Callback) {
                        loadModule( Module, Callback );
                    }
                    return false;
                }
            }
        };
    };
    var loadScript = function( Source )
    {
        var htmlElement = document.createElement( "script" );
        htmlElement.src = Source;
        document.body.appendChild( htmlElement );
    };
    var loadModule = function( Module )
    {
        if (!useConfig[Module].isUsed) {
            loadScript( useConfig[Module].Source );
            useConfig[Module].isUsed = true;
        }
    };
    var waitModule = function( Module, Callback )
    {
        if (!useConfig[Module].isReady( Callback )) {
            window.setTimeout( function()
            {
                waitModule( Module, Callback );
            }, useDelay );
        } else {
            return Callback();
        }
    };
    var setUse = function setUse( Module, Callback )
    {
        return waitModule( Module, Callback );
    };
    return {
        Module: setModule,
        Source: setSource,
        Use: setUse
    }
})();
