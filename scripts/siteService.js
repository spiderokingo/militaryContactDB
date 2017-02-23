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

app.directive('numberConverter', function() {
  return {
    priority: 1,
    restrict: 'A',
    require: 'ngModel',
    link: function(scope, element, attr, ngModel) {
      function toModel(value) {
        return "" + value; // convert to string
      }

      function toView(value) {
        return parseInt(value); // convert to number
      }

      ngModel.$formatters.push(toView);
      ngModel.$parsers.push(toModel);
    }
  };
});

app.factory('focus', function($timeout, $window) {
    return function(id) {
      // timeout makes sure that it is invoked after any other event has been triggered.
      // e.g. click events that need to run before the focus or
      // inputs elements that are in a disabled state but are enabled when those events
      // are triggered.
      $timeout(function() {
        var element = $window.document.getElementById(id);
        if(element)
          element.focus();
      });
    };
  });

  app.directive('focusOnShow', function($timeout) {
    return {
        restrict: 'A',
        link: function($scope, $element, $attr) {
            if ($attr.ngShow){
                $scope.$watch($attr.ngShow, function(newValue){
                    if(newValue){
                        $timeout(function(){
                            $element[0].focus();
                        }, 0);
                    }
                })      
            }
        }
    };
})