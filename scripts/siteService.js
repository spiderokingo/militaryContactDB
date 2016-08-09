app.directive('fallbackSrc', function () {
  var fallbackSrc = {
    link: function postLink(scope, iElement, iAttrs) {
      iElement.bind('error', function() {
        angular.element(this).attr("src", iAttrs.fallbackSrc);
      });
    }
   }
   return fallbackSrc;
});

app.directive('ngEnter', function () {
    var ENTER_KEYCODE = 13;
    return function (scope, element, attrs) {

        element.bind("keydown keypress", function (event) {
            if(event.which === ENTER_KEYCODE) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });
                event.preventDefault();
            }
        });
    };
});

app.directive("resizeHeader", function($timeout){
    return function(scope, element, attr){
        
        $timeout(function () {
            var inputW = (element.val().length*7) + (element[0].id == 'title'? 2:20);
            element.css('width', inputW + 'px');
        }, 100);

        element.bind('keypress', function(){
            var inputW = (element.val().length*7) + (element[0].id == 'title'? 15:20);
            element.css('width', inputW + 'px');
        })
    }
});

app.directive("resizeAddress", function($timeout){
    return function(scope, element, attr){
        
        $timeout(function () {
            var inputW = (element.val().length*7) + (element.val().length==0?0:5);
            element.css('width', inputW + 'px');
        }, 100);

        element.bind('keypress', function(){
            var inputW = (element.val().length*7) + 20;
            element.css('width', inputW + 'px');
        })
    }
});
