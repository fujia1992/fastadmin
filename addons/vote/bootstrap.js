require.config({
    paths: {
        'cityselect': '../addons/vote/js/cityselect',
        'citydata': '../addons/vote/js/citydata',
    },
    shim: {
        'citydata': {
            exports: 'cityData'
        },
        'cityselect': {
            deps: ['jquery', 'css!../addons/vote/css/cityselect.css'],
            exports: '$.fn.extend'
        },
    }
});