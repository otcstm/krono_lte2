<?php


namespace App\Http\Middleware;

use Closure;
//use UserAgentParser\Exception\NoResultFoundException;
//use UserAgentParser\Provider\WhichBrowser;

/**
 * Class DeviceDetect
 *
 * A middleware to prevent Internet Explorer from being used.
 *
 * README: In our app we only have one real HTML generating route -- it loads the VueJS front-end -- so this is only applied to that one route. I'd worry about applying this on every page load.
 *
 * @package App\Http\Middleware
 */
class NoIE
{


	/**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next)
	{
	    try {
		    /* @var $result \UserAgentParser\Model\UserAgent */
		    $result = $_SERVER['HTTP_USER_AGENT'];
	    } catch (NoResultFoundException $ex){
		    return $next($request);

	    }

	    if( false !== strpos( $result, 'Internet Explorer' ) ) {
			return redirect( '/browser-fail' );
	    }

		return $next($request);
	}

}