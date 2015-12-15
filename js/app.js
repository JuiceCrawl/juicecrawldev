var app = angular.module("myApp", ['ui.bootstrap']);

app.controller("AppController", ['$scope', '$modal', '$log', function($scope, $modal, $log){
	$scope.testimonials =[
	{'name':'Jake G.', 'quote':'Had the time of my life...Wow I have never had so many varieties of juice and SO MUCH OF IT!', 'image':'jake.png'},
	{'name':'Shameka K.', 'quote':'Everyone should try it at least once. I discovered great NYC juice bars that I didn\'t even know existed, and I made some new friends.','image':'shameka.png'},
	{'name':'Anne M.', 'quote':'What a blast! I absolutely LOVE these events and look forward to going to more. I get to meet new people, connect with like minded health lovers, and I feel amazing drinking juice all day.', 'image':'anne.png'}

	];

    $scope.init = function(){
		console.log('APP CONTROLLER: INIT');
	};

	$scope.status = {
		isopen: false
	};
	
	$scope.open = function (size, templateFile) {

	    var modalInstance = $modal.open({
	      templateUrl: templateFile,
	      controller: 'ModalInstanceCtrl',
	      size: size,
	      resolve: {
	        items: function () {
	          return $scope.items;
	        }
	      }
	    });

	    modalInstance.result.then(function (selectedItem) {
	      $scope.selected = selectedItem;
	    }, function () {
	      $log.info('Modal dismissed at: ' + new Date());
	    });
	};

	
	$scope.openSub = function(){
		$scope.open('', 'myModalContent.html');
	};

	$scope.toggleDropdown = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.status.isopen = !$scope.status.isopen;
	};

}]);

app.controller('ModalInstanceCtrl', ['$scope', '$modalInstance', function ($scope, $modalInstance) {

  $scope.ok = function () {
    $modalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
}]);