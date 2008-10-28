<?php
Library::import('recess.routing.RoutingResult');

/**
 * Routing nodes are used to build a routing tree which maps a requested
 * URI string and HTTP Method to a Route. Example Route paths:
 * 
 * /pages/				-> matches /pages/
 * /pages/:id			-> matches /pages/1 ... (id => 1)
 * /pages/slug/:slug	-> matches /pages/slug/some-slug-here (slug => some-slug-here)
 * 
 * For the purposes of this class a URI path is broken into parts delimited
 * with a '/'. There are two kinds of path parts: static and parametric. Static matches
 * have precedence over parametric matches. For example, if you have the following routes:
 * 
 * (1) /pages/:page_title/
 * (2) /pages/a-page/
 * (3) /pages/:page_title/:id
 * 
 * A request of "/pages/a-page/" will match (2) and the result will not contain an argument.
 * A request of "/pages/b-page/" will match (1) and the result will contain argument ("page_title" => "b_page")
 * A request of "/pages/a-page/1" will match (3) with result arguments ("page_title" => "a_page", "id" => "1")
 * 
 * @todo Add regular expression support to the parametric parts (/pages/:id(regexp-goes-here?)/)
 * 
 * @author Kris Jordan <kris@krisjordan.com>
 * @copyright Copyright (c) 2008, Kris Jordan 
 * @package recess.routing
 */
class RoutingNode {
	
	protected $condition = '';
	protected $methods = array();
	protected $static_children = array();
	protected $parametric_children = array();
	
	/**
	 * Used to add a route to the routing tree.
	 * 
	 * @param Route The route to add to this routing tree.
	 */
	public function addRoute(Route $route) {
		$pathParts = $this->getRevesedPathParts($route->path);
		$this->addRouteRecursively($pathParts, count($pathParts) - 1, $route);
	}
	
	/**
	 * The recursive method powering addRouteFor(Request).
	 * 
	 * @param array Part of a path in reverse order.
	 * @param int Current index of path part array - decrements with each step.
	 * @param Route The route being added
	 * 
	 * @return FindRouteResult
	 */
	private function addRouteRecursively(&$pathParts, $index, $route) {
		// Base Case
		if($index < 0) {
			foreach($route->methods as $method) {
				if(isset($this->methods[$method])) {
					throw new RecessException('Conflicting routes, the route: "' . $route->path . '" is defined twice.', get_defined_vars());
				}
				$this->methods[$method] = $route;
			}
			return;
		}
		
		$nextPart = $pathParts[$index];
		
		if($nextPart[0] != ':') {
			$childrenArray = &$this->static_children;
			$nextKey = $nextPart;
			$isParam = false;
		} else {
			$childrenArray = &$this->parametric_children;
			$nextKey = substr($nextPart, 1);
			$isParam = true;
		}
		
		if(!isset($childrenArray[$nextKey])) {
			$child = new RoutingNode();
			if($isParam) {
				$child->condition = $nextKey;
			}
			$childrenArray[$nextKey] = $child;
		} else {
			$child = $childrenArray[$nextKey];
		}
		
		$child->addRouteRecursively($pathParts, $index - 1, $route);
	}
	
	/**
	 * Traverses children recursively to find a matching route. First looks
	 * to see if a static (non-parametric, i.e. /this_is_static/ vs. /:this_is_dynamic/)
	 * match exists. If not, we match against dynamic children. We reverse and step backwards
	 * through the array because $index > 0 is less costly than $index < count($parts)
	 * in PHP.
	 * 
	 * @param Request The recess.http.Request object to find a matching route for.
	 * 
	 * @return RoutingResult
	 */
	public function findRouteFor(Request $request) {
		$pathParts = $this->getRevesedPathParts($request->resource);
		return $this->findRouteRecursively($pathParts, count($pathParts) - 1, $request->method);
	}
	
	/**
	 * The recursive method powering findRouteFor(Request).
	 * 
	 * @param array Part of a path in reverse order.
	 * @param int Current index of path part array - decrements with each step.
	 * @param string The HTTP METHOD desired for this route.
	 * 
	 * @return RoutingResult
	 */
	private function findRouteRecursively(&$pathParts, $index, &$method) {
		// Base Case - We've gone to the end of the path.
		if($index < 0) {
			$result = new RoutingResult();
			if(!empty($this->methods)) { // Leaf, now check HTTP Method Match
				if(isset($this->methods[$method])) {
					$result->routeExists = true;
					$result->methodIsSupported = true;
					$result->route = $this->methods[$method];
				} else {
					$result->routeExists = true;
					$result->route = array_values($this->methods);
					$result->route = $result->route[0];
					$result->methodIsSupported = false;
					$result->acceptableMethods = array_keys($this->methods);
				}
			} else { // Non-leaf, no match
				$result->routeExists = false;
			}
			return $result;
		} 
		
		// Find a child for the next part of the path.
		$nextPart = &$pathParts[$index];
		
		$result = new RoutingResult();
		
		// Check for a static match
		if(isset($this->static_children[$nextPart])) {
			$child = $this->static_children[$nextPart];
			$result = $child->findRouteRecursively($pathParts, $index - 1, $method);
		}
		
		if(!$result->routeExists) {
			foreach($this->parametric_children as $child) {
				if($child->matches($nextPart)) {
					$result = $child->findRouteRecursively($pathParts, $index - 1, $method);
					if($result->routeExists) {
						if($child->condition != '') {
							$result->arguments[$child->condition] = $nextPart;
						}
						return $result;
					}
				}
			}
		}
		
		return $result;
	}
	
	public function matches($path) {
		// TODO: Add regexp support
		return $path != '';
	}
	
	// Helper Methods
	
	/**
	 * Explodes a string by forward slashes, removes empty first/last node
	 * and finally reverses the array.
	 * @param string Path to be split and reversed.
	 */
	private function getRevesedPathParts($path) {
		$pathParts = explode('/', $path);
		if(!empty($pathParts)) {
			if($pathParts[0] == '') {
				array_shift($pathParts);
			}
		}
		if(!empty($pathParts)) {
			if($pathParts[count($pathParts)-1] == '') {
				array_pop($pathParts);
			}
		}
		$pathParts = array_reverse($pathParts);
		return $pathParts;
	}
}
?>